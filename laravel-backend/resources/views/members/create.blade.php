<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<x-app-layout>
    <div class="container mt-5">
        <h2 class="mb-4">Ajouter un membre</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('members.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="user_id">Utilisateur</label>
                        <select class="form-control" id="user_id" name="user_id" required>
                            <option value="">Sélectionnez un utilisateur</option>
                            <!-- liste des utilisateurs -->
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->lastname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Téléphone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="address">Adresse</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="membership_number">Numéro de membre</label>
                <input type="text" class="form-control" id="membership_number" name="membership_number" value="{{ $membershipNumber }}" readonly>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="join_date">Date d'adhésion</label>
                        <input type="date" class="form-control" id="join_date" name="join_date" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="expiry_date">Date d'expiration</label>
                        <input type="date" class="form-control" id="expiry_date" name="expiry_date">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="status">Statut</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="">Sélectionnez un statut</option>
                    <option value="Active">Actif</option>
                    <option value="Inactive">Inactif</option>
                    <option value="Banned">Banni</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
        </form>
    </div>
</x-app-layout>