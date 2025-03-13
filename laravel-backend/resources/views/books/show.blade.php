<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book Details') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>{{ $book->title }}</h3>
            </div>
            <div class="card-body">
                @if($book->image)
                    <img src="{{ asset($book->image) }}" alt="{{ $book->title }}" class="img-fluid mb-3">
                @else
                    <img src="{{ asset('build/images/default-book-cover.png') }}" alt="Default Image" class="img-fluid mb-3">
                @endif
                <p><strong>Author:</strong> {{ $book->author }}</p>
                <p><strong>ISBN:</strong> {{ $book->isbn }}</p>
                <p><strong>Published Year:</strong> {{ $book->published_year }}</p>
                <p><strong>Description:</strong> {{ $book->description }}</p>
                <p><strong>Status:</strong> {{ $book->status }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('books.index') }}" class="btn btn-secondary">Back to List</a>
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