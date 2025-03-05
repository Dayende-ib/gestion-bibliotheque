<?php

use App\Http\Controllers\BooksController;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenalitesController;
use App\Http\Controllers\MembersController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->middleware(['auth'], 'verified')->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'], 'verified')->name('dashboard');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::middleware('auth')->group(function () {
        
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('books',BooksController::class);
    Route::resource('penalites',PenalitesController::class);
    Route::resource('members', MembersController::class);

    // Route pour afficher la liste des loans
     Route::get('/loans', [LoansController::class, 'index'])->name('loans.index');

    // Route pour les emprunts
     if (Auth::check() && Auth::user()->role == 'admin') {
        Route::get('/loans/create', [LoansController::class, 'create'])->name('loans.create');
     } else {
        Route::get('/loans/create/{book_id}', [LoansController::class, 'create'])->name('loans.create');
     }
     Route::post('/loans', [LoansController::class, 'store'])->name('loans.store');
     Route::get('/loans/{id}', [LoansController::class, 'show'])->name('loans.show');
     Route::get('/loans/{id}/edit', [LoansController::class, 'edit'])->name('loans.edit');
     Route::patch('/loans/{id}', [LoansController::class, 'update'])->name('loans.update');
     Route::delete('/loans/{id}', [LoansController::class, 'destroy'])->name('loans.destroy');

     // Route for returning a book
     Route::post('/loans/{id}/return', [LoansController::class, 'returnBook'])->name('loans.return');

});

require __DIR__.'/auth.php';
