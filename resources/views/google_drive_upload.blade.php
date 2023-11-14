<!-- resources/views/google_drive_upload.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Drive Upload</title>
</head>
<body>
    <h2>Upload File to Google Drive</h2>

    <form action="{{ route('google.drive.upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <label for="file">Choose File:</label>
        <input type="file" name="file" id="file" accept=".jpg, .png, .pdf">
        
        <label for="description">Description:</label>
        <input type="text" name="description" id="description">

        <button type="submit">Upload</button>
    </form>
</body>
</html>
