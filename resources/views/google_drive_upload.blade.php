<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Drive Upload</title>
    <!-- Add Bootstrap CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Upload File to Google Drive</h2>

    <form action="{{ route('google.drive.upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Choose File:</label>
            <input type="file" class="form-control-file" name="file" id="file" accept=".jpg, .png, .pdf">
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <input type="text" class="form-control" name="description" id="description">
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</body>
</html>
