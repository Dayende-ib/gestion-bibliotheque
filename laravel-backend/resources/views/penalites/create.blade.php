
<x-app-layout>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2>Créer une pénalité</h2>
        <form action="{{ route('penalites.store') }}" method="POST">
          @csrf
          <div class="form-group">
            <label for="member_id">Membre</label>
            <select class="form-control" id="member_id" name="member_id">
              @foreach($members as $member)
                <option value="{{ $member->id }}">{{ $member->user->lastname }} {{ $member->user->firstname }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="start_date">Date de début</label>
            <input type="date" class="form-control" id="start_date" name="start_date">
          </div>
          <div class="form-group">
            <label for="end_date">Date de fin</label>
            <input type="date" class="form-control" id="end_date" name="end_date">
          </div>
          <div class="form-group">
            <label for="amount">Montant</label>
            <input type="number" class="form-control" id="amount" name="amount">
          </div>
          <button type="submit" class="btn btn-primary">Créer</button>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>