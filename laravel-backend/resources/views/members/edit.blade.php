@extends('layouts.layout')

@section('content')
<div class="container">
    <h2>Modifier le membre</h2>
    <form action="{{ route('members.update', $member) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Téléphone :</label>
            <input type="text" name="phone" class="form-control" value="{{ $member->phone }}" required>
        </div>
        <div class="mb-3">
            <label>Adresse :</label>
            <input type="text" name="address" class="form-control" value="{{ $member->address }}" required>
        </div>
        <button type="submit" class="btn btn-warning">Modifier</button>
    </form>
</div>
@endsection
