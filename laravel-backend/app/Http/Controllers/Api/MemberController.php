<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Members;
use App\Models\User;
use App\Models\BannedEmail;
use App\Models\Loans;
use DateTime;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    // Méthode pour lister tous les membres
    public function index()
    {
        $user = Auth::user();
        $query = Members::query();

        // Vérifier si l'utilisateur est un administrateur
        if ($user->role != 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Filtrer les membres par recherche si le paramètre 'search' est présent
        if (request()->has('search')) {
            $query->whereHas('user', function ($q) {
                $q->where('lastname', 'like', '%' . request('search') . '%')
                  ->orWhere('firstname', 'like', '%' . request('search') . '%');
            });
        }

        // Paginer les résultats
        $members = $query->paginate(10);
        return response()->json($members);
    }

    // Méthode pour créer un nouveau membre
    public function store(Request $request)
    {
        $user = Auth::user();
        $existingMember = Members::where('user_id', $request->user_id)->first();
        if ($existingMember) {
            return response()->json(['error' => 'User already has a membership'], 400);
        }

        // Vérifier si l'utilisateur est un administrateur ou s'il crée un compte pour lui-même
        if ($user->role != 'admin' && $request->user_id != $user->id) {
            return response()->json(['error' => 'You can only create a membership account for yourself'], 403);
        }

        // Valider les données de la requête
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone' => 'required|unique:members,phone',
            'address' => 'required|min:2|max:254',
            'join_date' => 'required|date|after_or_equal:today',
            'expiry_date' => 'required|date|after:join_date',
            'status' => 'required|in:Active,Inactive,Banned',
        ]);

        // Créer un nouveau membre
        $member = new Members();
        $member->user_id = $request->input('user_id');
        $member->phone = $request->input('phone');
        $member->address = $request->input('address');
        $member->join_date = $request->input('join_date');
        $member->expiry_date = $request->input('expiry_date');
        $member->status = $request->input('status');
        $member->membership_number = $this->generateMembershipNumber();
        $member->save();

        return response()->json($member, 201);
    }

    // Méthode pour afficher les détails d'un membre
    public function show($id)
    {
        $member = Members::findOrFail($id);
        return response()->json($member);
    }

    // Méthode pour mettre à jour un membre
    public function update(Request $request, $id)
    {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'phone' => 'required|exists:members,phone',
            'address' => 'required|min:2|max:254',
            'join_date' => 'required|date|after_or_equal:today',
            'expiry_date' => 'required|date|after:join_date',
            'status' => 'required|in:Active,Inactive,Banned',
        ]);

        $member = Members::findOrFail($id);

        // Si le statut est "Banned", supprimer les prêts et ajouter l'email à la liste des bannis
        if ($request->status == 'Banned') {
            Loans::where('member_id', $member->id)->delete();
            BannedEmail::create(['email' => $member->user->email]);
        } else {
            BannedEmail::where('email', $member->user->email)->delete();
        }

        // Mettre à jour les informations du membre
        $member->phone = $request->input('phone');
        $member->address = $request->input('address');
        $member->join_date = $request->input('join_date');
        $member->expiry_date = $request->input('expiry_date');
        $member->status = $request->input('status');
        $member->save();

        return response()->json($member);
    }

    // Méthode pour supprimer un membre
    public function destroy($id)
    {
        $member = Members::findOrFail($id);
        if (Auth::user()->role == 'admin') {
            $member->delete();
            return response()->json(['message' => 'Member deleted successfully']);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Méthode pour générer un numéro de membre unique
    private function generateMembershipNumber()
    {
        $date = new DateTime();
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');
        $hour = $date->format('H');
        $minute = $date->format('i');
        $second = $date->format('s');
        $random = rand(100, 999);

        $membershipNumber = "M" . $year . $month . $day . $hour . $minute . $second . $random;

        return $membershipNumber;
    }
}