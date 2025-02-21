<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
