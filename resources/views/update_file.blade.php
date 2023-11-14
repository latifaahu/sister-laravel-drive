<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update File</title>
    <!-- Add Bootstrap CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Update File</h1>
    @if (isset($error))
        <p class="alert alert-danger">{{ $error }}</p>
    @else
        <form action="{{ route('google.drive.update', ['driveId' => $file->google_drive_id]) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" name="description" value="{{ $file->description }}">
            </div>
            <div class="form-group">
                <label for="file">Choose a new file:</label>
                <input type="file" class="form-control-file" name="file">
            </div>
            <button type="submit" class="btn btn-success">Update File</button>
        </form>
        <br>
        <a href="{{ route('google.drive.list') }}" class="btn btn-primary">Back to List</a>    
    @endif
</body>
</html>
