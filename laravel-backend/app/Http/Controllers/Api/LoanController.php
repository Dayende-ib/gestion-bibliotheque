<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loans;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function getUserLoans()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();
        
        // Vérifier si l'utilisateur a un membre associé
        if (!$user->member) {
            return response()->json(['message' => 'Aucun membre associé à cet utilisateur'], 404);
        }

        // Récupérer les emprunts du membre avec les informations du livre
        $loans = Loans::with('book')
            ->where('member_id', $user->member->id)
            ->get();

        return response()->json($loans, 200);
    }
}