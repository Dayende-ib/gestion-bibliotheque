<x-app-layout>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2>Pénalités</h2>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Membre</th>
              <th>Date de début</th>
              <th>Date de fin</th>
              <th>Montant</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($penalties as $penalty)
              <tr>
                <td>{{ $penalty->id }}</td>
                <td>{{ $penalty->member->user->lastname }} {{ $penalty->member->user->firstname }}</td>
                <td>{{ $penalty->start_date }}</td>
                <td>{{ $penalty->end_date }}</td>
                <td>{{ $penalty->amount }}</td>
                <td>
                  <a href="{{ route('penalites.show', $penalty->id) }}" class="btn btn-primary">Voir</a>
                  <a href="{{ route('penalites.edit', $penalty->id) }}" class="btn btn-secondary">Éditer</a>
                  <form action="{{ route('penalites.destroy', $penalty->id) }}" method="POST" style="display: inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                  </form>
                </td>

              </tr>
              @empty
              <tr>
                <td colspan="6">Aucune pénalité trouvée</td>
            @endforelse
          </tbody>
        </table>
        <button><a href="{{ route('penalites.create') }}" class="btn btn-primary">Ajouter une pénalité</a></button>
      </div>
    </div>
  </div>
</x-app-layout>