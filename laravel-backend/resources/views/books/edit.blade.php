<x-app-layout>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Edit book</title>
    </head>

    <div class="container mt-4">

        <div class="card p-4 shadow-sm">
            <form action="{{ route('books.update', $book->id) }}" method="POST">
                @csrf
                @method('PUT')

                <h1 class="text-center text-primary">Edit book</h1>
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $book->title) }}" required>
                    
                    @error('title')
                    <div class="text-danger">{{$message}}</div>
                @enderror
                </div>

                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" class="form-control" id="author" name="author" value="{{ old('author', $book->author) }}" required>

                    @error('author')
                    <div class="text-danger">{{$message}}</div>
                @enderror
                </div>

                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" class="form-control" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}" required>

                    @error('isbn')
                    <div class="text-danger">{{$message}}</div>
                @enderror
                </div>

                <div class="mb-3">
                    <label for="published_year" class="form-label">published year</label>
                    <input type="number" class="form-control" id="published_year" name="published_year" value="{{ old('published_year', $book->published_year) }}" required>

                    @error('published_year')
                    <div class="text-danger">{{$message}}</div>
                @enderror
                </div>

                {{-- <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="Available" {{ $book->status == 'Available' ? 'selected' : '' }}>Disponible</option>
                        <option value="Checked Out" {{ $book->status == 'Checked Out' ? 'selected' : '' }}>Emprunté</option>
                    </select>
                </div> --}}

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('books.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</x-app-layout>
