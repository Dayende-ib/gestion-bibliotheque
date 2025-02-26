<?php

use App\Http\Controllers\BooksController;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenalitesController;
use App\Http\Controllers\MembersController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', action: function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::resource('books',BooksController::class);
Route::resource('penalites',PenalitesController::class);


// Route::resource('loans',LoansController::class);
// Route pour afficher la liste des loans
Route::get('/loans', [LoansController::class, 'index'])->name('loans.index');

// Route pour afficher le formulaire d'emprunt
if (Auth::check() && Auth::user()->role == 'admin') {
    Route::get('/loans/create', [LoansController::class, 'create'])->name('loans.create');
} else {
    Route::get('/loans/create/{book_id}', [LoansController::class, 'create'])->name('loans.create');
}

// Route pour enregistrer un nouvel emprunt
Route::post('/loans', [LoansController::class, 'store'])->name('loans.store');

// Route pour afficher les détails d'un emprunt
Route::get('/loans/{id}', [LoansController::class, 'show'])->name('loans.show');

// Route pour afficher le formulaire de modification d'un emprunt
Route::get('/loans/{id}/edit', [LoansController::class, 'edit'])->name('loans.edit');

// Route pour mettre à jour un emprunt
Route::patch('/loans/{id}', [LoansController::class, 'update'])->name('loans.update');

// Route pour supprimer un emprunt
Route::delete('/loans/{id}', [LoansController::class, 'destroy'])->name('loans.destroy');

//Route pour le CRUD des membres
Route::resource('members', MembersController::class);

});



require __DIR__.'/auth.php';
