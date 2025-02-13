<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liste des Livres') }}
        </h2>
    </x-slot>

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .container {
                max-width: 90%;
                margin: auto;
            }
            .table th, .table td {
                text-align: center;
            }
            .btn {
                margin: 2px;
            }
        </style>
    </head>

    <div class="container mt-4">
        <h1 class="text-center text-primary">Liste des Livres</h1>
        <a href="{{ route('books.create') }}" class="btn btn-success mb-3">Add book</a>
        
        @if(Session::get('success'))
            <div class="alert alert-success" style="background-color: green; color: white;">
                {{ Session::get('success') }}
            </div>
        @endif
        
        <table class="table table-striped table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>ISBN</th>
                    <th>Année de publication</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->isbn }}</td>
                        <td>{{ $book->published_year }}</td>
                        <td>{{ $book->status }}</td>
                        <td>
                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
