<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Members as Member;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use DateTime;

class MemberController extends Controller
{
    // Méthode pour lister tous les membres
    public function index()
    {
        $members = Member::with('user')->get();
        return response()->json($members, 200);
    }

    // Méthode pour créer un nouveau membre
    public function store(Request $request)
    {
        $user = Auth::user();
        $existingMember = Member::where('user_id', $request->user_id)->first();
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
        ]);

        // Créer un nouveau membre avec des valeurs par défaut
        $member = new Member();
        $member->user_id = $request->input('user_id');
        $member->phone = Null; // Valeur par défaut
        $member->address = 'N/R'; // Valeur par défaut
        $member->join_date = now();
        $member->expiry_date = now()->addMonth(); // Valeur par défaut
        $member->status = 'Active'; // Valeur par défaut
        $member->membership_number = $this->generateMembershipNumber();
        $member->save();

        return response()->json($member, 201);
    }

    // Méthode pour supprimer un membre
    public function destroy($member)
    {
        $member = Member::findOrFail($member);
        if (Auth::user()->role == 'admin') {
            $member->delete();
            return response()->json(['message' => 'Member deleted successfully']);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
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