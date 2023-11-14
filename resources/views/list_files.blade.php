<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Files</title>
</head>
<body>
    <h1>List of Files in the "Project" Folder</h1>

    @if (isset($error))
        <p style="color: red;">{{ $error }}</p>
    @else
        <form action="{{ route('google.drive.delete') }}" method="post">
            @csrf
            @method('DELETE')
            @if (count($files) > 0)
                <ul>
                    @foreach ($files as $file)
                        <li>
                            <label>
                                <input type="checkbox" name="file_ids[]" value="{{ $file->id }}">
                                <a href="{{ 'https://drive.google.com/open?id=' . $file->id }}" target="_blank">
                                    {{ $file->name }}
                                </a>
                            </label>
                        </li>
                    @endforeach
                </ul>
                <button type="submit">Delete Selected Files</button>
            @else
                <p>No files found in the "Project" folder.</p>
            @endif
        </form>
        <br>
        <!-- Add a button to navigate to the file upload form -->
        <a href="{{ route('google.drive.upload.form') }}">Go to File Upload Form</a>
    @endif
</body>
</html>
