<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Books;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $books = Books::all();

        //$search = $request->input('search');
        //$books = Books::where('title', 'like', "%$search%")->orWhere('author', 'like', "%$search%")->get();
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
            $livre = new Books();
            $livre->title = $request->input('title');
            $livre->author = $request->input('author');
            $livre->description = $request->input('description');
            $livre->isbn = $request->input('isbn');
            $livre->published_year = $request->input('published_year');
            $livre->status = $request->input('status');

            if ($request->hasFile('image')) {
                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $livre->image = 'images/'.$imageName;

                // $path = $request->file('image')->store('images', 'public');
                // $livre->image = $path;
            }

            $livre->save();

            return redirect()->route('books.index')->with('success', 'Book added successfully.');
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
            $book->title = $request->input('title');
            $book->author = $request->input('author');
            $book->isbn = $request->input('isbn');
            $book->published_year = $request->input('published_year');
            $book->description = $request->input('description');
            $book->status = $request->input('status');

            if ($request->hasFile('image')) {
                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $book->image = 'images/'.$imageName;
            }

            $book->save();

            return redirect()->route('books.index')->with('success', 'Livre édité avec succès.');
        
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du livre.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (Auth::user()->role == 'admin') {
            $book = Books::find($id);
            if ($book) {
                $book->delete();
                return redirect()->route('books.index')->with('success', 'Livre supprimé avec succès');
            } else {
                return redirect()->route('books.index')->with('error', 'Livre non trouvé');
            }
        } else {
            return redirect()->route('books.index')->with('error', 'Vous n\'avez pas les droits nécessaires pour supprimer ce livre');
        }
    }
    
}
