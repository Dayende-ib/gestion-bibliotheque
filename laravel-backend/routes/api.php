<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\MemberController;

// Ajout de la route pour la création de compte et la connexion
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'getUser']);

Route::middleware('auth:sanctum')->group(function () {
    // Book Management Routes
    Route::get('/books', [BookController::class, 'index']);
    Route::post('/books', [BookController::class, 'store']);
    Route::get('/books/{book}', [BookController::class, 'show']);
    Route::put('/books/{book}', [BookController::class, 'update']);
    Route::delete('/books/{book}', [BookController::class, 'destroy']);

    // Loan Management Routes
    Route::get('/user/loans', [LoanController::class, 'getUserLoans']);
    Route::patch('/loans/{loan}', [LoanController::class, 'update']);
    Route::delete('/loans/{loan}', [LoanController::class, 'destroy']);

    // Member Management Routes
    Route::get('/members', [MemberController::class, 'index']);
    Route::post('/members', [MemberController::class, 'store']);
    Route::get('/members/{member}', [MemberController::class, 'show']);
    Route::put('/members/{member}', [MemberController::class, 'update']);
    Route::delete('/members/{member}', [MemberController::class, 'destroy']);
});

// Ajout de la route de test
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!'], 200);
});