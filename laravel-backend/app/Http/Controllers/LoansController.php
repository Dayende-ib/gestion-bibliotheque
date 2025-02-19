<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveLoanRequest;
use App\Models\Loans;
use App\Models\Books;
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
        return view('books.loans.loan', compact('loans', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveLoanRequest $request)
    {
        try {
            $loan = new Loans();

            $loan->book_id = $request->input('book_id');
            $loan->member_id = null;
            $loan->loan_date = $request->input('loan_date');
            $loan->return_date = $request->input('return_date');
            $loan->status = 'en cours';

            $loan->save();

            return redirect()->route('loans.index')->with('success', 'loan ajouté avec succès.');
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
