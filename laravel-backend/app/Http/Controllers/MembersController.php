<?php

namespace App\Http\Controllers;

use App\Models\Members;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BannedEmail;
use App\Models\Loans;
use DateTime;
use Illuminate\Support\Facades\Auth;

class MembersController extends Controller
{
    // Liste des membres
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Members::query();

        if ($user->role != 'admin') {
            return redirect()->route('books.index');
        }

        if ($request->has('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('lastname', 'like', '%' . $request->search . '%')
                  ->orWhere('firstname', 'like', '%' . $request->search . '%');
            });
        }

        $members = $query->paginate(10);
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
        $user = Auth::user();
        // Vérifier si l'utilisateur a déjà un compte de membre
        $existingMember = Members::where('user_id', $request->user_id)->first();
        if ($existingMember) {
            // L'utilisateur a déjà un compte de membre, renvoyer une erreur
            return redirect()->back()->withErrors(['user_id' => 'Vous avez déjà un compte de membre']);
        } else {
            // L'utilisateur n'a pas de compte de membre, continuer avec la création du compte

            // If the user is not an admin, ensure they can only create a membership for themselves
            if ($user->role != 'admin' && $request->user_id != $user->id) {
                return redirect()->back()->withErrors(['user_id' => 'You can only create a membership account for yourself']);
            }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone' => 'required|unique:members,phone',
            'address' => 'required|min:2|max:254',
            'join_date' => 'required|date|after_or_equal:today',
            'expiry_date' => 'required|date|after:join_date',
            'status' => 'required|in:Active,Inactive,Banned',
        ], [
            'user_id.required' => 'The user field is required',
            'user_id.exists' => 'The user does not exist',
            'phone.required' => 'The phone field is required',
            'phone.unique' => 'The phone number is already in use',
            'phone.regex' => 'The phone format is invalid',
            'address.required' => 'The address field is required',
            'address.min' => 'The address must be at least 02 characters',
            'address.max' => 'The address must not exceed 254 characters',
            'join_date.required' => 'The join date field is required',
            'join_date.date' => 'The join date must be a valid date',
            'join_date.before_or_equal' => 'The join date cannot be in the future',
            'expiry_date.required' => 'The expiry date field is required',
            'expiry_date.date' => 'The expiry date must be a valid date',
            'expiry_date.after' => 'The expiry date must be after the join date',
            'status.required' => 'The status field is required',
            'status.in' => 'The status must be either "Active", "Inactive", or "Banned"',
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
        // $request->validate([
        //     'phone' => 'required|exists:members,phone',
        //     'address' => 'required|min:2|max:254',
        //     'join_date' => 'required|date|after_or_equal:today',
        //     'expiry_date' => 'required|date|after:join_date',
        //     'status' => 'required|in:Active,Inactive,Banned',
        // ], [
        //     'phone.required' => 'The phone field is required',
        //     'phone.exists' => 'The phone number is already in use',
        //     'address.required' => 'The address field is required',
        //     'address.min' => 'The address must be at least 02 characters',
        //     'address.max' => 'The address must not exceed 254 characters',
        //     'join_date.required' => 'The join date field is required',
        //     'join_date.date' => 'The join date must be a valid date',
        //     'join_date.before_or_equal' => 'The join date cannot be in the future',
        //     'expiry_date.required' => 'The expiry date field is required',
        //     'expiry_date.date' => 'The expiry date must be a valid date',
        //     'expiry_date.after' => 'The expiry date must be after the join date',
        //     'status.required' => 'The status field is required',
        //     'status.in' => 'The status must be either "Active", "Inactive", or "Banned"',
        // ]);

        // If the member is banned, cancel all their borrowings and save their email
        if ($request->status == 'Banned') {
            // Delete all borrowings
            Loans::where('member_id', $member->id)->delete();
    
            // Save the email to prevent future account creation
            BannedEmail::create(['email' => $member->user->email]);
        }else {
            // Delete the email from the banned emails table
            BannedEmail::where('email', $member->user->email)->delete();
        }
        
        // $member->phone = $request->input('phone');
        // $member->address = $request->input('address');
        // $member->join_date = $request->input('join_date');
        // $member->expiry_date = $request->input('expiry_date');
        $member->status = $request->input('status');
        $member->save();
    
        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    // Supprimer un membre
    public function destroy(Members $member)
    {
        if(Auth::user()->role == 'admin') {
            $member->delete();
        return redirect()->route('members.index')->with('success', 'Membre supprimé.');
        }
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
