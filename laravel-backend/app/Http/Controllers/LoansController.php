<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveLoanRequest;
use App\Models\Loans;
use App\Models\Books;
use App\Models\Penalites;
use App\Models\Members;
use Illuminate\Http\Request;

class LoansController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loans::with('book')->paginate(10);
        return view('books.loans.list', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Loans $loans)
    {
        $books = Books::all();
        $members = Members::all();
        return view('books.loans.loan', compact('loans', 'books', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveLoanRequest $request)
    {
        try {
            // Check if the book is already borrowed
            $existingLoan = Loans::where('book_id', $request->input('book_id'))
            ->where('status', 'Borrowed') // Assuming 'en cours' indicates an active loan
            ->first();

        if ($existingLoan) {
            return redirect()->back()->withErrors(['book_id' => 'Ce livre est déjà emprunté.']);
        }else {

            $loan = new Loans();
    
            $loan->book_id = $request->input('book_id');
            $loan->member_id = $request->input('member_id'); // Ensure member_id is part of the request
            $loan->borrowed_at = $request->input('borrowed_at');
            $loan->due_date = $request->input('due_date');
            $loan->status = 'Borrowed';
            $loan->save();
    
            // Create a penalty for the loan
            try {
            $penalty = new Penalites();
            $penalty->member_id = $loan->member_id; // Use the member_id from the loan
            $penalty->loan_id = $loan->id; // Assign the loan_id to the penalty
            $penalty->start_date = $loan->borrowed_at; // Set start_date to loan_date
            $penalty->end_date = $loan->due_date; // Set end_date to return_date
            $penalty->amount = 0.00; // Set a fixed penalty amount or calculate as needed
            $penalty->save();

            } catch (\Exception $e) {
                dd($e);
            }
    
            return redirect()->route('loans.index')->with('success', 'loan ajouté avec succès.');
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
        //
    }
}
