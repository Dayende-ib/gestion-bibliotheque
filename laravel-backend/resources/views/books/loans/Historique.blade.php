<x-app-layout>
    <div class="container">
        <h1>Historique des Emprunts</h1>

        <form method="GET" action="{{ route('loans.history') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher par utilisateur ou livre" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="start_date" class="form-control" placeholder="Date de début" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="end_date" class="form-control" placeholder="Date de fin" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="{{ route('loans.history') }}" class="btn btn-secondary">Réinitialiser</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Livre</th>
                    <th>Date d'emprunt</th>
                    <th>Date de retour</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loansHistory as $history)
                    <tr>
                        <td>{{ $history->user->lastname }} {{ $history->user->firstname }}</td>
                        <td>{{ $history->book->title }}</td>
                        <td>{{ $history->borrowed_at }}</td>
                        <td>{{ $history->returned_at ? $history->returned_at : 'Non retourné' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>