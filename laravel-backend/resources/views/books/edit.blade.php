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

            <div class="form-grid">
                <div class="form-inputs">
                    <!-- Champ Titre -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $book->title) }}" required>
                        @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Champ Auteur -->
                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control @error('author') is-invalid @enderror" id="author" name="author" value="{{ old('author', $book->author) }}" required>
                        @error('author')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Champ Description -->
                    <div class="mb-3">
                        <label for="description">{{ __('Description') }}</label>
                        <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $book->description) }}" required></textarea>
                        <div id="description-counter" class="text-end text-muted">{{ strlen(old('description', $book->description)) }} / 191</div>
                        @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Champ ISBN -->
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}" required>
                        <div id="isbn-counter" class="text-end text-muted">{{ strlen(old('isbn', $book->isbn)) }} / 13</div>
                        @error('isbn')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Champ Année de publication -->
                    <div class="mb-3">
                        <label for="published_year" class="form-label">Published Year</label>
                        <input type="number" class="form-control @error('published_year') is-invalid @enderror" id="published_year" name="published_year" value="{{ old('published_year', $book->published_year) }}" required>
                        @error('published_year')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-image">
                    <!-- Champ Image -->
                    <div class="mb-3">
                        <label for="image">{{ __('Book Image') }}</label>
                        <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image" onchange="previewImage(event)">
                        @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <img id="image-preview" src="{{ $book->image ? asset('storage/' . $book->image) : asset('build/images/default-book-cover.png') }}" alt="Book Image" style="max-width: 200px; margin-top: 10px;">
                    </div>
                </div>
            </div>

            <input type="text" name="status" id="status" value="Available" hidden>
            @error('status')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror

            <!-- Bouton de soumission -->
            <div class="text-center mt-4">
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

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('image-preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    <style>
        .form-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group img {
            align-self: center;
        }

        .char-counter {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .form-image {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-image img {
            margin-top: 10px;
        }
    </style>
</x-app-layout>