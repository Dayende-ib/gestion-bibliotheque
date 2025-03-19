<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book Details') }}
        </h2>
    </x-slot>

    <div class="container mt-2">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-3 h2 text-bold text-capitalize">{{ $book->title }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <p class="mb-1 text-capitalize"><strong>Author:</strong> {{ $book->author }}</p>
                        <p class="mb-1"><strong>ISBN:</strong> {{ $book->isbn }}</p>
                        <p class="mb-1"><strong>Published Year:</strong> {{ $book->published_year }}</p>
                        <p class="mb-1"><strong>Description:</strong> {{ $book->description }}</p>
                        <p class="mb-1"><strong>Status:</strong> {{ $book->status }}</p>
                    </div>
                    <div class="col-md-4 text-center">
                        @if($book->image)
                            <img src="{{ asset($book->image) }}" alt="{{ $book->title }}" class="img-fluid mb-2" style="width: 150px; height: auto; margin-top: 0;">
                        @else
                            <img src="{{ asset('build/images/default-book-cover.png') }}" alt="Default Image" class="img-fluid mb-2" style="width: 150px; height: auto; margin-top: 0;">
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('books.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this book?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>