<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveLoanRequest;

use App\Models\Loans;
use App\Models\Books;
use App\Models\Penalites;
use App\Models\Members;
use App\Models\LoansHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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

                $startDate = Carbon::parse($request->input('borrowed_at'));
                $endDate = Carbon::now();
                $daysLate = $endDate->diffInDays($startDate);
                
                $penalty->amount = abs($daysLate * 500); // 500 francs CFA per day
                $penalty->save();

            return redirect()->route('loans.index')->with('success', 'Emprunt ajouté avec succès.');
        }
    } catch (\Exception $e) {
        dd($e);
    }
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $loan = Loans::find($id);
        if($loan){
            // Create a new record in the loans_history table
            LoansHistory::create([
                'user_id' => $loan->member->user_id,
                'book_id' => $loan->book_id,
                'borrowed_at' => $loan->borrowed_at,
                'returned_at' => now(),
            ]);
                
                
            // Update the book status to 'Available'
            $book = Books::find($loan->book_id);
            if ($book) {
                $book->status = 'Available';
                $book->save();
            }
            $loan->delete();
            return redirect()->route('loans.index')->with('success', 'Loans deleted.');
        } else {
            return redirect()->route('loans.index')->with('error', 'Loans unavailable.');
        }

            

            
       
    }

    public function history(Request $request)
    {
        $query = LoansHistory::with(['user', 'book'])
            ->orderBy('returned_at', 'desc');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' .$search. '%');
            })->orWhereHas('book', function($q) use ($search) {
                $q->where('title', 'like', '%' .$search. '%');
            });
        }

        if ($request->has('start_date')) {
            $query->whereDate('borrowed_at', '>=', $request->input('start_date'));
        }

        if ($request->has('end_date')) {
            $query->whereDate('borrowed_at', '<=', $request->input('end_date'));
        }

        $loansHistory = $query->paginate(10);

        return view('books.loans.Historique', compact('loansHistory'));
    }


    public function returnBook($id)
    {
        $loan = Loans::find($id);
        if ($loan) {
            // Create a new record in the loans_history table
            LoansHistory::create([
                'user_id' => $loan->member->user_id,
                'book_id' => $loan->book_id,
                'borrowed_at' => $loan->borrowed_at,
                'returned_at' => now(),
            ]);

            

            // Update the book status to 'Available'
            $book = Books::find($loan->book_id);
            if ($book) {
                $book->status = 'Available';
                $book->save();
            }

            // Delete the loan record from the loans table
            $loan->delete();

            return redirect()->route('loans.index')->with('success', 'Livre retourné avec succès.');
        } else {
            return redirect()->route('loans.index')->with('error', 'Emprunt non trouvé.');
        }
    }
}
