<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Books;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Books::all();
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBookRequest $request)
    {
        try {
            $livre = new Books();
            $livre->title = $request->input('title');
            $livre->author = $request->input('author');
            $livre->isbn = $request->input('isbn');
            $livre->published_year = $request->input('published_year');

            $livre->save();

            return redirect()->route('books.index')->with('success', 'Livre ajouté avec succès.');
        } catch (\Exception $e) {
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Books $books)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Books $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Books $book)
    {
        try {
            // $request->validate([
            //     'title' => 'required|string|max:255',
            //     'author' => 'required|string|max:255',
            //     'isbn' => 'required|string|max:20|unique:books,isbn,' . $book->id,
            //     'published_year' => 'required|integer|min:1900|max:' . date('Y'),
            // ]);
    
            // $book->update($request->all());

            $book->title = $request->input('title');
            $book->author = $request->input('author');
            $book->isbn = $request->input('isbn') ;
            $book->published_year = $request->input('published_year');

            $book->update();

            return redirect()->route('books.index')->with('success', 'Livre edité avec succès.');
        
        } catch (\Exception $e) {
           dd($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Books $books)
    {
        //
    }
}
