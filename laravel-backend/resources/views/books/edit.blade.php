<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier un Livre') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <h1 class="text-center text-primary">Modifier un Livre</h1>
        <div class="card p-4 shadow-sm">
            <form action="{{ route('books.update', $book->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $book->title) }}" required>
                </div>

                <div class="mb-3">
                    <label for="author" class="form-label">Auteur</label>
                    <input type="text" class="form-control" id="author" name="author" value="{{ old('author', $book->author) }}" required>
                </div>

                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" class="form-control" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}" required>
                </div>

                <div class="mb-3">
                    <label for="published_year" class="form-label">Année de Publication</label>
                    <input type="number" class="form-control" id="published_year" name="published_year" value="{{ old('published_year', $book->published_year) }}" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Statut</label>
                    <select class="form-control" id="status" name="status">
                        <option value="Available" {{ $book->status == 'Available' ? 'selected' : '' }}>Disponible</option>
                        <option value="Checked Out" {{ $book->status == 'Checked Out' ? 'selected' : '' }}>Emprunté</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('books.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</x-app-layout>
