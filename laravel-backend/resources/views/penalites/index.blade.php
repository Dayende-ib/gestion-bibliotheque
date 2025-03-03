<x-app-layout>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2>Pénalités</h2>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Fullname</th>
              <th>Start date</th>
              <th>End date</th>
              <th>Amount due</th>
              <th></th>
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
                  <a href="{{ route('penalites.show', $penalty->id) }}" class="btn btn-primary">Show</a>
                  <a href="{{ route('penalites.edit', $penalty->id) }}" class="btn btn-secondary">Edit</a>
                  <form action="{{ route('penalites.destroy', $penalty->id) }}" method="POST" style="display: inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                  </form>
                </td>

              </tr>
              @empty
              <tr>
                <td colspan="6">Nothing to show</td>
            @endforelse
          </tbody>
        </table>
        @if (Auth::user()->role = 'admin')
          <button><a href="{{ route('penalites.create') }}" class="btn btn-primary">Add penalty</a></button>
        @endif
      </div>
    </div>
  </div>
</x-app-layout>