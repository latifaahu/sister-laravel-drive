<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Files</title>
</head>
<body>
    <h1>List of Files in the "Project" Folder</h1>
    @if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
    @endif
    @if (isset($error))
        <p style="color: red;">{{ $error }}</p>
    @else
        <form action="{{ route('google.drive.delete') }}" method="post">
            @csrf
            @method('DELETE')
            @if (count($files) > 0)
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Description</th>
                            <th>Name</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($files as $file)
                            <tr>
                                <td>{{ $file->id }}</td>
                                <td>{{ $file->description }}</td>
                                <td>
                                    <a href="{{ 'https://drive.google.com/open?id=' . $file->id }}" target="_blank">
                                        {{ $file->name }}
                                    </a>
                                </td>
                                <td>
                                    <input type="checkbox" name="file_ids[]" value="{{ $file->id }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
