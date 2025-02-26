<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Emprunts') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <h1 class="text-center text-primary">Liste des Emprunts</h1>
        
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
                    <th>Livre</th>
                    <th>Date d'emprunt</th>
                    <th>Date de retour</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                    <tr>
                        <td>{{ $loan->book->title }}</td>
                        <td>{{ $loan->borrowed_at }}</td>
                        <td>{{ $loan->due_date }}</td>
                        <td>{{ $loan->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-bg-info text-white" colspan="4">Aucun emprunt</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $loans->links() }}
    </div>
</x-app-layout>