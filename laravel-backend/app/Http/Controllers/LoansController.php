<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveLoanRequest;
use App\Models\Loans;
use App\Models\Books;
use App\Models\Penalites;
use App\Models\Members;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoansController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role == 'admin') {
            // L'administrateur voit tous les emprunts
            $loans = Loans::with('book')->paginate(10);
        } else {
            if (!$user->member) {
                return redirect()->route('members.create')->withErrors(['errors' => 'Vous devez être membre pour voir vos emprunts. INSCRIVEZ VOUS ICI!!']);
            } else {
            // Les utilisateurs normaux voient uniquement leurs propres emprunts
            $loans = Loans::with('book')->where('member_id', $user->member->id)->paginate(10);
            }
        }

        return view('books.loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($book_id = null)
    {
        $user = Auth::user();

        // Vérifiez si l'utilisateur est membre
        if (!$user->member) {
            return redirect()->route('books.index')->withErrors(['errors' => 'Vous devez être membre pour emprunter un livre.']);
        }

        $member = Members::where('user_id', Auth::user()->id)->first();

        if ($member->status == 'Banned') {
            return redirect()->back()->withErrors(['error' => 'Vous ne pouvez pas emprunter de livres car vous êtes banni.']);
        }

        $book = Books::findOrFail($book_id);
        $memberId = Auth::user()->member->id; // Assurez-vous que l'utilisateur a un membre associé

        return view('books.loans.create', compact( 'book', 'memberId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveLoanRequest $request)
{
    try {
        //  Check if the user is authenticated and is a member
        if (!Auth::check() || !Auth::user()->member) {
             return redirect()->back()->withErrors(['errors' => 'Vous devez être membre pour emprunter un livre.']);
         }

         $member = Members::where('user_id', Auth::user()->id)->first();

        if ($member->status == 'Banned') {
            return redirect()->back()->withErrors(['error' => 'Vous ne pouvez pas emprunter de livres car vous êtes banni.']);
        }

        // Check if the book is already borrowed
        $existingLoan = Loans::where('book_id', $request->input('book_id'))
            ->where('status', 'Borrowed') // Assuming 'Borrowed' indicates an active loan
            ->first();

        if ($existingLoan) {
            return redirect()->back()->withErrors(['book_id' => 'Ce livre est déjà emprunté.']);
        } else {
            $loan = new Loans();
            $loan->book_id = $request->input('book_id');
            $loan->member_id = $request->input('member_id'); // Ensure member_id is part of the request
            $loan->borrowed_at = $request->input('borrowed_at');
            $loan->due_date = $request->input('due_date');
            $loan->status = 'Borrowed';
            $loan->save();

            $book = Books::find($request->book_id);
            if ($book) {
                // Mettre à jour le statut du livre
                $book->status = 'Borrowed';
                $book->save();
            } else {
                // Gérer le cas où le livre n'existe pas
            }

            // Create a penalty for the loan
                $penalty = new Penalites();
                $penalty->member_id = $loan->member_id; // Use the member_id from the loan
                $penalty->loan_id = $loan->id; // Assign the loan_id to the penalty
                $penalty->start_date = $loan->borrowed_at; // Set start_date to loan_date
                $penalty->end_date = $loan->due_date; // Set end_date to return_date
                $penalty->amount = 0.00; // Set a fixed penalty amount or calculate as needed
                $penalty->save();

            return redirect()->route('loans.index')->with('success', 'Emprunt ajouté avec succès.');
        }
    } catch (\Exception $e) {
        dd($e);
    }
}


/**
     * Display the specified resource.
     */
    public function show(Loans $loans)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loans $loans)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loans $loans)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loans $loans)
    {
        $loans->delete();
        return redirect()->route('loans.index')->with('success', 'Emprunt supprimé avec succès.');
    }

    public function returnBook($id)
    {
        $loan = Loans::find($id);
        if ($loan) {
            // Update the loan status to 'Returned'
            $loan->status = 'Returned';
            $loan->save();

            // Update the book status to 'Available'
            $book = Books::find($loan->book_id);
            if ($book) {
                $book->status = 'Available';
                $book->save();
            }

            return redirect()->route('loans.index')->with('success', 'Livre retourné avec succès.');
        } else {
            return redirect()->route('loans.index')->with('error', 'Emprunt non trouvé.');
        }
    }
}
