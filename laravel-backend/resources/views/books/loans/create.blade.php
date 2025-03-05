<x-app-layout>
    <div class="container mt-4">
        <h1 class="text-center text-primary">Emprunter le Livre : <strong class="h2 text-danger"> {{ $book->title }}</strong> </h1>
        
        <form action="{{ route('loans.store') }}" method="POST">
            @csrf
            
            <input type="hidden" name="book_id" value="{{ $book->id }}">
            <input type="hidden" name="member_id" value="{{ $memberId }}">
            <input type="hidden" name="status" value="Borrowed">
            
            <div class="mb-3">
                <label for="borrowed_at" class="form-label">Date d'emprunt</label>
                <input type="date" class="form-control" id="borrowed_at" name="borrowed_at" value="{{ old('borrowed_at') ?: date('Y-m-d') }}" required>
            </div>
            
            <div class="mb-3">
                <label for="due_date" class="form-label">Date de retour</label>
                <input type="date" class="form-control" id="due_date" name="due_date" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Valider l'emprunt</button>
        </form>
    </div>
</x-app-layout>