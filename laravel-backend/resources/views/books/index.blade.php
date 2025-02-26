<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Books') }}
        </h2>
    </x-slot>
    
    <div class="container mt-4">
        <h1 class="text-center text-primary">Liste des Livres</h1>
        @if (Auth::user()->role == 'admin')
            <a href="{{ route('books.create') }}" class="btn btn-success mb-3">Add book</a>
        @endif
        
        @if(Session::get('success'))
            <div class="alert alert-success" style="background-color: green; color: white;">
                {{ Session::get('success') }}
            </div>
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
                @forelse($books as $book)
                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->isbn }}</td>
                        <td>{{ $book->published_year }}</td>
                        <td>{{ $book->status }}</td>
                        @if (Auth::user()->role == 'admin')
                            <td>
                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                            </td>
                        @else
                            <td>
                                <a href="#" onclick="checkMembership({{ Auth::user()->member ? 'true' : 'false' }}, {{ $book->id }})" class="btn btn-primary btn-sm">Emprunter</a>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-bg-info text-white" colspan="6">Aucun livre Ajouté</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Add this modal dialog in the body of index.blade.php -->
<div id="membershipModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Devenir Membre</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Vous devez être membre pour emprunter un livre. Souhaitez-vous devenir membre ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="{{ route('members.create') }}" class="btn btn-primary">Devenir Membre</a>
            </div>
        </div>
    </div>
</div>

<script>
    function checkMembership(isMember, bookId) {
        if (isMember) {
            window.location.href = '/loans/create/' + bookId;
        } else {
            $('#membershipModal').modal('show');
        }
    }
</script>
</x-app-layout>