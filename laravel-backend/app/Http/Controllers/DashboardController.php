<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books;
use App\Models\Loans;
use App\Models\User;
use App\Models\Members;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Books::count();
        $totalLoans = Loans::count();
        $totalMembers = Members::count();
        $totalUsers = User::count();

        if(Auth::user()->role == 'admin') {
            return view('dashboard', compact('totalBooks', 'totalLoans', 'totalMembers', 'totalUsers'));
        } else {
            return redirect()->route('books.index');
        }
    }
}
