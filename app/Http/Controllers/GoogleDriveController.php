<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\File as FileDesc;

class GoogleDriveController extends Controller
{
    public $gClient;

    function __construct(){
        
        $this->gClient = new \Google_Client();
        
        $this->gClient->setApplicationName('Web Client 1'); // ADD YOUR AUTH2 APPLICATION NAME (WHEN YOUR GENERATE SECRATE KEY)
        $this->gClient->setClientId('336533562342-vgfgvqvvre9d1q4o27npj7lcig9s8rjk.apps.googleusercontent.com');
        $this->gClient->setClientSecret('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');
        $this->gClient->setRedirectUri(route('google.login'));
        $this->gClient->setDeveloperKey('AIzaSyAaryYwejqLC0ZYX4_h2iW4TqwYxUWfJHc');
        $this->gClient->setScopes(array(               
            'https://www.googleapis.com/auth/drive.file',
            'https://www.googleapis.com/auth/drive'
        ));
        
        $this->gClient->setAccessType("offline");
        
        $this->gClient->setApprovalPrompt("force");
    }
    
    public function googleLogin(Request $request)  {
        
        $google_oauthV2 = new \Google_Service_Oauth2($this->gClient);

        if ($request->get('code')){

            $this->gClient->authenticate($request->get('code'));

            $request->session()->put('token', $this->gClient->getAccessToken());
        }

        if ($request->session()->get('token')){

            $this->gClient->setAccessToken($request->session()->get('token'));
        }

        if ($this->gClient->getAccessToken()){

            //FOR LOGGED IN USER, GET DETAILS FROM GOOGLE USING ACCES
            $user = User::find(1);

            $user->access_token = json_encode($request->session()->get('token'));

            $user->save();       

            dd("Successfully authenticated");
        } else{
            
            // FOR GUEST USER, GET GOOGLE LOGIN URL
            $authUrl = $this->gClient->createAuthUrl();

            return redirect()->to($authUrl);
        }
    }

    public function googleDriveFileUpload()
    {
        $service = new \Google_Service_Drive($this->gClient);

        $user= User::find(1);

        $this->gClient->setAccessToken(json_decode($user->access_token,true));

        if ($this->gClient->isAccessTokenExpired()) {
            
            // SAVE REFRESH TOKEN TO SOME VARIABLE
            $refreshTokenSaved = $this->gClient->getRefreshToken();

            // UPDATE ACCESS TOKEN
            $this->gClient->fetchAccessTokenWithRefreshToken($refreshTokenSaved);               
            
            // PASS ACCESS TOKEN TO SOME VARIABLE
            $updatedAccessToken = $this->gClient->getAccessToken();
            
            // APPEND REFRESH TOKEN
            $updatedAccessToken['refresh_token'] = $refreshTokenSaved;
            
            // SET THE NEW ACCES TOKEN
            $this->gClient->setAccessToken($updatedAccessToken);
            
            $user->access_token=$updatedAccessToken;
            
            $user->save();                
        }
        
        $fileMetadata = new \Google_Service_Drive_DriveFile(array(
            'name' => 'project-semester7-sister',             // ADD YOUR GOOGLE DRIVE FOLDER NAME
            'mimeType' => 'application/vnd.google-apps.folder'));

        $folder = $service->files->create($fileMetadata, array('fields' => 'id'));

        printf("Folder ID: %s\n", $folder->id);
        
        $file = new \Google_Service_Drive_DriveFile(array('name' => 'cdrfile.jpg','parents' => array($folder->id)));

        $result = $service->files->create($file, array(

            'data' => file_get_contents(public_path('test.png')), // ADD YOUR FILE PATH WHICH YOU WANT TO UPLOAD ON GOOGLE DRIVE
            'mimeType' => 'application/octet-stream',
            'uploadType' => 'media'
        ));

        $url='https://drive.google.com/open?id='.$result->id;

        dd($result);
    }

    public function showUploadForm()
    {
        return view('google_drive_upload');
    }   
    
    private function getGoogleDriveFolderId($folderName)
    {
        $service = new \Google_Service_Drive($this->gClient);
    
        $parameters['q'] = "mimeType='application/vnd.google-apps.folder' and name='$folderName'";
        $files = $service->files->listFiles($parameters);
    
        if (count($files->getFiles()) > 0) {
            return $files->getFiles()[0]->getId();
        }
    
        return null;
    }
    
    public function listFiles()
    {
        $service = new \Google_Service_Drive($this->gClient);
    
        $user = User::find(1);
        $this->gClient->setAccessToken(json_decode($user->access_token, true));
    
        // Check if the access token is expired
        if ($this->gClient->isAccessTokenExpired()) {
            $refreshTokenSaved = $this->gClient->getRefreshToken();
            $this->gClient->fetchAccessTokenWithRefreshToken($refreshTokenSaved);
            $updatedAccessToken = $this->gClient->getAccessToken();
            $updatedAccessToken['refresh_token'] = $refreshTokenSaved;
            $this->gClient->setAccessToken($updatedAccessToken);
    
            $user->access_token = json_encode($updatedAccessToken);
            $user->save();
        }
    
        $folderId = $this->getGoogleDriveFolderId('project-semester7-sister');
    
        if (!$folderId) {
            // Handle the case when the "project-semester7-sister" folder does not exist
            return view('google_drive_upload')->with('error', 'The "project-semester7-sister" folder does not exist.');
        }
    
        // Retrieve the list of files in the "project-semester7-sister" folder
        $parameters['q'] = "'$folderId' in parents";
        $parameters['fields'] = 'files(id, name, description)';
        $files = $service->files->listFiles($parameters);
    
        return view('list_files')->with('files', $files->getFiles());
    }
    
    public function uploadFile(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:jpg,png,pdf|max:2048',
        ]);
    
        $service = new \Google_Service_Drive($this->gClient);
    
        $user = User::find(1);
    
        $this->gClient->setAccessToken(json_decode($user->access_token, true));
    
        // Check if the access token is expired
        if ($this->gClient->isAccessTokenExpired()) {
            $refreshTokenSaved = $this->gClient->getRefreshToken();
            $this->gClient->fetchAccessTokenWithRefreshToken($refreshTokenSaved);
            $updatedAccessToken = $this->gClient->getAccessToken();
            $updatedAccessToken['refresh_token'] = $refreshTokenSaved;
            $this->gClient->setAccessToken($updatedAccessToken);
    
            $user->access_token = json_encode($updatedAccessToken);
            $user->save();
        }
    
        // Get the ID of the existing "project-semester7-sister" folder
        $folderId = $this->getGoogleDriveFolderId('project-semester7-sister');
    
        if (!$folderId) {
            return view('google_drive_upload')->with('error', 'The "project-semester7-sister" folder does not exist.');
        }
    
        $fileContent = File::get($request->file('file')->getRealPath());
    
        // Create the file in the "project-semester7-sister" folder
        $fileMetadata = new \Google_Service_Drive_DriveFile([
            'name' => $request->file('file')->getClientOriginalName(),
        ]);
    
        // Set the description
        $description = $request->input('description');
    
        if ($description) {
            $fileMetadata->setDescription($description);
        }
    
        // Add the file to the specified folder using setParents
        $fileMetadata->setParents([$folderId]);
    
        $file = $service->files->create($fileMetadata, [
            'data' => $fileContent,
            'mimeType' => $request->file('file')->getClientMimeType(),
            'uploadType' => 'media',
        ]);
    
        // ID Google Drive
        $googleDriveId = $file->id;
    
        // Periksa apakah ID Google Drive berhasil didapatkan
        if (!$googleDriveId) {
            return view('google_drive_upload')->with('error', 'Failed to get Google Drive file ID.');
        }
    
        // Simpan ke dalam database
        \App\Models\File::create([
            'name' => $file->name,
            'description' => $description,
            'google_drive_id' => $googleDriveId,
        ]);
    
        // Get the URL of the uploaded file
        $url = 'https://drive.google.com/open?id=' . $file->id;
        return redirect()->route('google.drive.list');
    }
    

    // Add a new method to delete selected files
    public function deleteFiles(Request $request)
    {
        $fileIds = $request->input('file_ids');

        if (empty($fileIds)) {
            return redirect()->route('google.drive.list')->with('error', 'Please select files to delete.');
        }

        $service = new \Google_Service_Drive($this->gClient);

        $user = User::find(1);

        $this->gClient->setAccessToken(json_decode($user->access_token, true));

        if ($this->gClient->isAccessTokenExpired()) {
            $refreshTokenSaved = $this->gClient->getRefreshToken();
            $this->gClient->fetchAccessTokenWithRefreshToken($refreshTokenSaved);
            $updatedAccessToken = $this->gClient->getAccessToken();
            $updatedAccessToken['refresh_token'] = $refreshTokenSaved;
            $this->gClient->setAccessToken($updatedAccessToken);

            $user->access_token = json_encode($updatedAccessToken);
            $user->save();
        }

        foreach ($fileIds as $fileId) {
            // Hapus setiap file yang dipilih berdasarkan ID Google Drive
            $service->files->delete($fileId);
        
            // Hapus data dari database berdasarkan ID Google Drive
            \App\Models\File::where('google_drive_id', $fileId)->delete();
        }

        return redirect()->route('google.drive.list')->with('success', 'Selected file have been deleted.');
    }

    public function showUpdateForm($driveId)
    {
        $file = \App\Models\File::where('google_drive_id', $driveId)->first();

        if (!$file) {
            return redirect()->route('google.drive.list')->with('error', 'File not found.');
        }

        return view('update_file')->with('file', $file);
    }

    public function updateFile(Request $request, $driveId)
    {
        $this->validate($request, [
            'description' => 'required|string|max:255',
            'file' => 'nullable|mimes:jpg,png,pdf|max:2048',
        ]);
    
        $file = \App\Models\File::where('google_drive_id', $driveId)->first();
    
        if (!$file) {
            return redirect()->route('google.drive.list')->with('error', 'File not found.');
        }
    
        // Update description
        $file->description = $request->input('description');
    
        // Check if a new file is provided
        if ($request->hasFile('file')) {
            $service = new \Google_Service_Drive($this->gClient);
    
            $user = User::find(1);
            $this->gClient->setAccessToken(json_decode($user->access_token, true));
    
            // Check if the access token is expired
            if ($this->gClient->isAccessTokenExpired()) {
                $refreshTokenSaved = $this->gClient->getRefreshToken();
                $this->gClient->fetchAccessTokenWithRefreshToken($refreshTokenSaved);
                $updatedAccessToken = $this->gClient->getAccessToken();
                $updatedAccessToken['refresh_token'] = $refreshTokenSaved;
                $this->gClient->setAccessToken($updatedAccessToken);
    
                $user->access_token = json_encode($updatedAccessToken);
                $user->save();
            }
    
            // Get the ID of the existing "project-semester7-sister" folder
            $folderId = $this->getGoogleDriveFolderId('project-semester7-sister');
    
            if (!$folderId) {
                return view('google_drive_upload')->with('error', 'The "project-semester7-sister" folder does not exist.');
            }
    
            $fileContent = File::get($request->file('file')->getRealPath());
    
            // Create the file in the "project-semester7-sister" folder
            $fileMetadata = new \Google_Service_Drive_DriveFile([
                'name' => $request->file('file')->getClientOriginalName(),
            ]);
    
            // Set the new description
            $fileMetadata->setDescription($request->input('description'));
    
            // Set parents as an array or an empty array if it's null
            $parents = is_array($file->parents) ? $file->parents : [];
    
            $updatedFile = $service->files->update(
                $file->google_drive_id,
                $fileMetadata,
                [
                    'data' => $fileContent,
                    'mimeType' => $request->file('file')->getClientMimeType(),
                    'uploadType' => 'media',
                    'addParents' => $folderId,
                    'removeParents' => implode(',', $parents),
                ]
            );
    
            // Update the Google Drive ID and parents in the local database
            $file->google_drive_id = $updatedFile->id;
            $file->parents = [$folderId];
        }
    
        $file->save();
    
        return redirect()->route('google.drive.list')->with('success', 'File updated successfully.');
    }
    
}