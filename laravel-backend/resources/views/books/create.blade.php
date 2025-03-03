<x-app-layout>
    @if(session('success'))
            <div class="bg-green-300 p-3">{{ session('success') }}</div>
        @endif
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add a Book</title>
        
    </head>
    <body>
        <div class="container mt-4">
            <h1 class="text-center text-primary mb-4">Add a New Book</h1>
            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Champ Titre -->
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required>
                    @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Champ Auteur -->
                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" class="form-control @error('author') is-invalid @enderror" id="author" name="author" value="{{ old('author') }}" required>
                    @error('author')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Champ Description -->
                <div class="mb-3">
                    <label for="description">{{ __('Description') }}</label>
                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>
                    <div id="description-counter" class="text-end text-muted">0 / 191</div>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Champ ISBN -->
                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn"  value="{{ old('isbn') }}" required>
                    @error('isbn')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                     @enderror
                </div>

                <!-- Champ Année de publication -->
                <div class="mb-3">
                    <label for="published_year" class="form-label">Published Year</label>
                    <input type="number" class="form-control @error('published_year') is-invalid @enderror" id="published_year" name="published_year" value="{{ old('published_year') }}" required>
                    @error('published_year')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Champ Image -->
                <div class="mb-3">
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
                    <button type="submit" class="btn btn-primary">Save Book</button>
                </div>
            </form>
        </div>
    </body>
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
</html>
</x-app-layout>