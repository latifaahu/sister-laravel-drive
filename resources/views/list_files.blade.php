<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Files</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
    <script src="https://kit.fontawesome.com/f4ff5d57c5.js" crossorigin="anonymous"></script>

</head>
<body class="bg-[#F1EDE2] relative h-[630px]" x-data="fileManager()">
    
    @if (session('success'))
        <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Info</span>
        <div>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        </div>
    @endif
    @if (isset($error))
        <div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Info</span>
        <div>
            <span class="font-medium">{{ $error }}</span>
        </div>
        </div>
    @else
    
    
    <div class="bg-white shadow-sm w-1/2 mx-auto py-4 px-6 mt-16 rounded-lg">
        <!-- MODAL UPLOAD -->
        <div x-data="{ showModal: false }">
            <div class="flex items-center justify-between">
                <h1><span class="font-bold">Folder: </span>project-semester7-sister</h1>
                <button @click="showModal = true" class="btn bg-[#555F11] text-white mb-3 p-2 rounded text-xs">
                    <i class="fa-solid fa-upload"></i><span class="ml-2">Upload</span>
                </button>
            </div>
    
            <div x-show="showModal" class="fixed inset-0 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showModal" class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
    
                    <!-- This element is to trick the browser into centering the modal contents. -->
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
    
                    <div x-show="showModal" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form method="POST" action="{{ route('google.drive.upload') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Upload file
                                        </h3>
                                        <div class="my-3">
                                            <input type="file" name="file" id="file" accept=".jpg, .png, .pdf, .jpeg">
                                            <div class="form-group mt-3">
                                                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description:</label>
                                                <input type="text" name="description" id="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="description.." required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#8D7F00] text-base font-medium text-white hover:bg-[#555F11] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Submit
                                </button>
                                <button @click="showModal = false" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#C15324] text-base font-medium text-white hover:bg-[#801818] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Close
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <table cellpadding="10" cellspacing="0" class="w-full border-collapse rounded-lg overflow-hidden">
            <tr class="bg-[#CCD08A] text-sm text-left">
                <th class="font-semibold">No</th>
                <th class="font-semibold">File</th>
                <th class="font-semibold">Description</th>
                <th class="font-semibold">Action</th>
            </tr>
            <?php $i = 1; ?>
            @if (count($files) > 0)
            @foreach ($files as $file)
            <tr class="text-sm text-left {{ ($i%2==0) ? 'bg-[#F7F9E0]' : '' }} " x-data="{ modalEdit: false }">
                <td>{{$loop->iteration}}</th>
                <td>{{$file->name}}</td>
                <td>{{$file->description}}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="{{ 'https://drive.google.com/open?id=' . $file->id }}" target="_blank"><i class="fa-solid fa-eye text-[#8D7F00]"></i></a>
                        <button class="mx-3">
                            <a href="{{ url('google/drive/update', ['driveId' => $file->id]) }}"><i class="fa-solid fa-pencil cursor-pointer editbtn"></i></a>
                        </button>
                        <button>
                            <form method="POST" action="{{ route('google.drive.delete', ['file_id' => $file->id]) }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="file_ids[]" value="{{ $file->id }}" checked >
                                <button class="text-[#C15324]">
                                    <i class="fa-solid fa-trash cursor-pointer"></i>
                                </button>
                            </form>
                        </button>
                    </div>
                </td>
            </tr>
            <?php $i++; ?>
            @endforeach

            @else
                <p>No files found in the "project-semester7-sister" folder.</p>
            @endif
        </table>
    </div>
    @endif

</body>
</html>