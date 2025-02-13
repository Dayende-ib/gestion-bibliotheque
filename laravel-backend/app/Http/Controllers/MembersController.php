<?php

namespace App\Http\Controllers;

use App\Models\Members;
use Illuminate\Http\Request;
use App\Models\User;

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
        // Récupère tous les utilisateurs
        $users = User::all(); 
        return view('members.create', compact('users'));
    }
    

    // Enregistrer un membre
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone' => 'required',
            'address' => 'required',
        ]);

        Members::create($request->all());

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
}
