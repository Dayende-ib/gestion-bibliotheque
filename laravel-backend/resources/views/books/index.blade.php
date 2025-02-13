<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des livres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center text-primary">Liste des Livres</h1>
        <a href="create.html" class="btn btn-success mb-3">Add</a>
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Published Year</th>
                    <th>Status</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>The Great Gatsby</td>
                    <td>F. Scott Fitzgerald</td>
                    <td>9780743273565</td>
                    <td>1925</td>
                    <td>Available</td>
                    <td>2024-02-01</td>
                    <td>2024-02-10</td>
                    <td>
                        <a href="edit.html?id=1" class="btn btn-warning btn-sm">Edit</a>
                        
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>1984</td>
                    <td>George Orwell</td>
                    <td>9780451524935</td>
                    <td>1949</td>
                    <td>Checked Out</td>
                    <td>2024-01-15</td>
                    <td>2024-02-05</td>
                    <td>
                        <a href="edit.html?id=2" class="btn btn-warning btn-sm">Edit</a>
                        
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>To Kill a Mockingbird</td>
                    <td>Harper Lee</td>
                    <td>9780061120084</td>
                    <td>1960</td>
                    <td>Available</td>
                    <td>2024-01-20</td>
                    <td>2024-02-07</td>
                    <td>
                        <a href="edit.html?id=3" class="btn btn-warning btn-sm">Edit</a>
                        
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
