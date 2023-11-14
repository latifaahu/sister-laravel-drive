<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update File</title>
</head>
<body>
    <h1>Update File</h1>
    @if (isset($error))
        <p style="color: red;">{{ $error }}</p>
    @else
    <form action="{{ route('google.drive.update', ['driveId' => $file->google_drive_id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <label for="description">Description:</label>
        <input type="text" name="description" value="{{ $file->description }}">
        <br>
        <label for="file">Choose a new file:</label>
        <input type="file" name="file">
        <br>
        <button type="submit">Update File</button>
    </form>
    <br>
    <a href="{{ route('google.drive.list') }}">Back to List</a>    
    @endif
</body>
</html>
