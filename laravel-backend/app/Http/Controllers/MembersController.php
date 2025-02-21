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

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone' => 'required',
            'address' => 'required',
            'join_date' => 'required|date',
            'expiry_date' => 'required|date',
            'status' => 'required',
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
