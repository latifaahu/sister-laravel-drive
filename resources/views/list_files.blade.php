<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Files</title>
    <!-- Add Bootstrap CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">List of Files in the "Project" Folder</h1>
    @if (session('success'))
        <p class="alert alert-success">{{ session('success') }}</p>
    @endif
    @if (isset($error))
        <p class="alert alert-danger">{{ $error }}</p>
    @else
        <form action="{{ route('google.drive.delete') }}" method="post">
            @csrf
            @method('DELETE')
            @if (count($files) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Description</th>
                            <th>Name</th>
                            <th>Pilih File Delete</th>
                            <th>Update</th>
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
                                    <input type="checkbox" class="form-check-input" name="file_ids[]" value="{{ $file->id }}">
                                </td>
                                <td>
                                    <a href="{{ url('google/drive/update', ['driveId' => $file->id]) }}" class="btn btn-warning">Update</a>
                                </td>                                                                                 
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-danger">Delete Selected Files</button>
            @else
                <p class="alert alert-info">No files found in the "Project" folder.</p>
            @endif
        </form>
        <br>
        <a href="{{ route('google.drive.upload.form') }}" class="btn btn-primary">Go to File Upload Form</a>
    @endif
</body>
</html>
