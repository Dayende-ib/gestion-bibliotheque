<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Members;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getNonMembers()
    {
        // Assuming you have a 'members' table with a 'user_id' column
        $nonMembers = User::whereDoesntHave('member')->get();
        return response()->json($nonMembers, 200);
    }
}