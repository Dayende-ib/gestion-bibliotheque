<?php

namespace App\Http\Controllers;

use App\Models\Members;
use Illuminate\Http\Request;
use App\Models\User;
use DateTime;

class MembersController extends Controller
{
    // Liste des membres
    public function index()
    {
        $members = Members::all();
        return view('members.index', compact('members'));
    }

    // Formulaire d'ajout
    public function create()
    {  
        $membershipNumber = $this->generateMembershipNumber();
        // Récupère tous les utilisateurs
        $users = User::all(); 
        return view('members.create', compact('users', 'membershipNumber'));
    }
    

    // Enregistrer un membre
    public function store(Request $request)
    { 
        // Vérifier si l'utilisateur a déjà un compte de membre
        $existingMember = Members::where('user_id', $request->user_id)->first();
        if ($existingMember) {
            // L'utilisateur a déjà un compte de membre, renvoyer une erreur
            return redirect()->back()->withErrors(['user_id' => 'Vous avez déjà un compte de membre']);
        } else {
            // L'utilisateur n'a pas de compte de membre, continuer avec la création du compte

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone' => 'required|unique:members,phone',
            'address' => 'required',
            'join_date' => 'required|date',
            'expiry_date' => 'required|date',
            'status' => 'required',
        ], [
            'user_id.required' => 'Le champ utilisateur est obligatoire',
            'user_id.exists' => 'L\'utilisateur n\'existe pas',
            'phone.required' => 'Le champ téléphone est obligatoire',
            'phone.unique' => 'Le téléphone est déjà utilisé',
            'address.required' => 'Le champ adresse est obligatoire',
            'join_date.required' => 'Le champ date d\'adhésion est obligatoire',
            'join_date.date' => 'Le champ date d\'adhésion doit être une date',
            'expiry_date.required' => 'Le champ date d\'expiration est obligatoire',
            'expiry_date.date' => 'Le champ date d\'expiration doit être une date',
            'status.required' => 'Le champ statut est obligatoire',
        ]);

        

        $member = new Members();
        $member->user_id = $request->input('user_id');
        $member->phone = $request->input('phone');
        $member->address = $request->input('address');
        $member->join_date = $request->input('join_date');
        $member->expiry_date = $request->input('expiry_date');
        $member->status = $request->input('status');
        $member->membership_number = $this->generateMembershipNumber();
        $member->save();
    

        //Members::create($request->all());

        return redirect()->route('members.index')->with('success', 'Membre ajouté avec succès.');
        }
    }

    // Formulaire d'édition
    public function edit(Members $member)
    {
        return view('members.edit', compact('member'));
    }

    // Mettre à jour un membre
    public function update(Request $request, Members $member)
    {
        $request->validate([
            'phone' => 'required',
            'address' => 'required',
        ]);

        $member->update($request->all());

        return redirect()->route('members.index')->with('success', 'Membre mis à jour.');
    }

    // Supprimer un membre
    public function destroy(Members $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Membre supprimé.');
    }

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
