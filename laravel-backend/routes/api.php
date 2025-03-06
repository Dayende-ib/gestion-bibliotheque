<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;


// Ajout de la route pour la création de compte et la connexion
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/books', [BookController::class, 'index']);
    Route::post('/books', [BookController::class, 'store']);
    Route::get('/books/{book}', [BookController::class, 'show']);
    Route::put('/books/{book}', [BookController::class, 'update']);
    Route::delete('/books/{book}', [BookController::class, 'destroy']);

    Route::post('/loans', [LoanController::class, 'store']);
    Route::patch('/loans/{loan}', [LoanController::class, 'update']);
    Route::delete('/loans/{loan}', [LoanController::class, 'destroy']);
});

// Ajout de la route de test
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!'], 200);
});