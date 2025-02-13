<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Lien vers le fichier CSS -->
    <title>Add a Book</title>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center text-primary mb-4">Add a New Book</h1>
        <form action="/save-book" method="POST">
            <!-- Champ Titre -->
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <!-- Champ Auteur -->
            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" id="author" name="author" required>
            </div>

            <!-- Champ ISBN -->
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn" required>
            </div>

            <!-- Champ Année de publication -->
            <div class="mb-3">
                <label for="published_year" class="form-label">Published Year</label>
                <input type="number" class="form-control" id="published_year" name="published_year" required>
            </div>

            <!-- Champ Statut -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Available">Available</option>
                    <option value="Borrowed">Borrowed</option>
                </select>
            </div>

            <!-- Champ Créé à -->
            <div class="mb-3">
                <label for="created_at" class="form-label">Created at</label>
                <input type="date" class="form-control" id="created_at" name="created_at" required>
            </div>

            <!-- Champ Mis à jour à -->
            <div class="mb-3">
                <label for="update_at" class="form-label">Updated at</label>
                <input type="date" class="form-control" id="update_at" name="update_at" required>
            </div>

            <!-- Bouton de soumission -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Save Book</button>
            </div>
        </form>
    </div>
</body>
<style>
    /* Style général du formulaire */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
    color: #333;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 20px;
    color: #007bff;
}

/* Style des champs du formulaire */
.form-control {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.form-control:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

/* Style des labels */
.form-label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
}

/* Style du bouton */
.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    font-size: 1rem;
    padding: 10px 20px;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

/* Style pour les écrans mobiles */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }

    h1 {
        font-size: 1.5rem;
    }

    .form-control {
        font-size: 0.875rem;
    }

    .btn-primary {
        width: 100%;
    }
}
</style>
</html>