<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\UserController;

// Log in, sign up and logout route
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//Route to get Connected user data and Token
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'getUser']);

Route::middleware('auth:sanctum')->group(function () {
    // Book Management Routes
    Route::get('/books', [BookController::class, 'index']);
    Route::post('/books', [BookController::class, 'store']);

    // Member Management Routes
    Route::get('/members', [MemberController::class, 'index']);
    Route::post('/members', [MemberController::class, 'store']);
    Route::delete('/members/{member}', [MemberController::class, 'destroy']);

    //Route to get connected User Loans
    Route::get('/user/loans', [LoanController::class, 'getUserLoans']);

    //Route to borrow and return bbook
    Route::post('/books/return/{bookId}', [LoanController::class, 'returnBook']);
    Route::post('/books/borrow/{bookId}', [LoanController::class, 'borrowBook']);

    // Route to get non-members
    Route::get('/users/non-members', [UserController::class, 'getNonMembers']);

});

// Route de test
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!'], 200);
});