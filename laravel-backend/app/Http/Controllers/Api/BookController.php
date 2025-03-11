<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Books;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Books::all();
        return response()->json($books, 200);
    }
}