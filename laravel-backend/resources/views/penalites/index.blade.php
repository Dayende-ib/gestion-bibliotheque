<x-app-layout>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
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
                <td>{{ $penalty->calculateAmountDue() }}</td>
                <td>
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
              </tr>
            @endforelse
          </tbody>
        </table>
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
          {{ $penalties->links() }}
      </div>
      </div>
    </div>
  </div>
</x-app-layout>