<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Book') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Champ Titre -->
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $book->title) }}" required>
                <div class="char-counter" id="title-counter">{{ strlen(old('title', $book->title)) }} / 255</div>
                @error('title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <!-- Champ Auteur -->
            <div class="form-group">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control @error('author') is-invalid @enderror" id="author" name="author" value="{{ old('author', $book->author) }}" required>
                <div class="char-counter" id="author-counter">{{ strlen(old('author', $book->author)) }} / 255</div>
                @error('author')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <!-- Champ Description -->
            <div class="form-group">
                <label for="description">{{ __('Description') }}</label>
                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $book->description) }}</textarea>
                <div class="char-counter" id="description-counter">{{ strlen(old('description', $book->description)) }} / 191</div>
                @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <!-- Champ ISBN -->
            <div class="form-group">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}" required>
                <div class="char-counter" id="isbn-counter">{{ strlen(old('isbn', $book->isbn)) }} / 13</div>
                @error('isbn')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <!-- Champ Année de publication -->
            <div class="form-group">
                <label for="published_year" class="form-label">Published Year</label>
                <input type="number" class="form-control @error('published_year') is-invalid @enderror" id="published_year" name="published_year" value="{{ old('published_year', $book->published_year) }}" required>
                @error('published_year')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <!-- Champ Image -->
            <div class="form-group">
                <label for="image">{{ __('Book Image') }}</label>
                <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image">
                @error('image')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <input type="text" name="status" id="status" value="Available" hidden>
            @error('status')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror

            <!-- Bouton de soumission -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const titleInput = document.getElementById('title');
            const authorInput = document.getElementById('author');
            const descriptionInput = document.getElementById('description');
            const isbnInput = document.getElementById('isbn');

            const titleCounter = document.getElementById('title-counter');
            const authorCounter = document.getElementById('author-counter');
            const descriptionCounter = document.getElementById('description-counter');
            const isbnCounter = document.getElementById('isbn-counter');

            titleInput.addEventListener('input', function () {
                titleCounter.textContent = `${titleInput.value.length} / 255`;
            });

            authorInput.addEventListener('input', function () {
                authorCounter.textContent = `${authorInput.value.length} / 255`;
            });

            descriptionInput.addEventListener('input', function () {
                descriptionCounter.textContent = `${descriptionInput.value.length} / 191`;
            });

            isbnInput.addEventListener('input', function () {
                isbnCounter.textContent = `${isbnInput.value.length} / 13`;
            });
        });
    </script>
</x-app-layout>