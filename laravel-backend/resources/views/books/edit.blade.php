<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center text-primary">Edit Book</h1>
        <form action="/update-book" method="POST">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="bookId" name="id" value="1"> <!-- Dynamic value -->

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="The Great Gatsby" required>
            </div>

            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" id="author" name="author" value="F. Scott Fitzgerald" required>
            </div>

            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn" value="9780743273565" required>
            </div>

            <div class="mb-3">
                <label for="published_year" class="form-label">Published Year</label>
                <input type="date" class="form-control" id="published_year" name="published_year" value="1925" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Available" selected>Available</option>
                    <option value="Checked Out">Checked Out</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="created_at" class="form-label">Created At</label>
                <input type="date" class="form-control" id="created_at" name                                                                       ="created_at" value="1925" required>
            </div>
            <div class="mb-3">
                <label for="updated_year" class="form-label">Updated Year</label>
                <input type="date" class="form-control" id="updated_year" name="updated_year" value="1925" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-warning">Update</button>
                <a href="index.html" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
