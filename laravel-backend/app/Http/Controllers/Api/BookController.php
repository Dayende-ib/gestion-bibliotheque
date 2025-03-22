<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Books;  
use Illuminate\Support\Facades\Storage;  
use Illuminate\Http\Request;  
use App\Models\Loans;

class BookController extends Controller
{
    public function index()
    {
        $books = Books::all();
        return response()->json($books, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'required|string|max:13',
            'published_year' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        $book = new Books();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->isbn = $request->isbn;
        $book->published_year = $request->published_year;

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public'); // Store image in public/images
            $book->image = $imagePath; // Save the image path in the database
        }

        $book->save();

        return response()->json($book, 201);
    }
}