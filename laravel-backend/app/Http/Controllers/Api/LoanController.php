<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loans;
use App\Models\User;
use App\Models\Books;
use App\Models\Members;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DateTime;

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

    public function returnBook(Request $request, $bookId)
    {
        $loan = Loans::where('book_id', $bookId)->where('status', 'Borrowed')->first();
        
        if ($loan) {
            // Update the loan status to 'Returned'
            $loan->status = 'Returned';
            $loan->save();

            // Update the book status to 'Available'
            $book = Books::find($bookId);
            if ($book) {
                $book->status = 'Available';
                $book->save();
            }

            return response()->json(['message' => 'Livre retourné avec succès'], 200);
        } else {
            return response()->json(['message' => 'Emprunt non trouvé'], 404);
        }
    }

    public function borrowBook(Request $request, $bookId)
    {
        try {
            $user = Auth::user();
            
            // Validate the bookId route parameter
            $validator = Validator::make(['bookId' => $bookId], [
                'bookId' => 'required|exists:books,id',
            ]);

            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }

            // If user doesn't have a member account, create one
            if (!$user->member) {
                $member = new Members();
                $member->user_id = $user->id;
                $member->phone = null;
                $member->join_date = date('Y-m-d');
                $member->expiry_date = date('Y-m-d', strtotime('+1 month'));
                $member->address = null;
                $member->status = 'Active';
                $member->membership_number = $this->generateMembershipNumber();
                
                if (!$member->save()) {
                    throw new \Exception('Failed to create member account');
                }
                
                // Refresh user to get the new member relationship
                $user = Auth::user();
            }

            $book = Books::find($bookId);
            
            if (!$book) {
                return response()->json(['message' => 'Livre non trouvé'], 404);
            }

            if ($book->status !== 'Available') {
                return response()->json(['message' => 'Livre non disponible'], 400);
            }

            // Create new loan
            $loan = new Loans();
            $loan->member_id = $user->member->id;
            $loan->book_id = $bookId;
            $loan->status = 'Borrowed';
            $loan->due_date = date('Y-m-d', strtotime('+14 days'));
            
            if (!$loan->save()) {
                throw new \Exception('Failed to create loan record');
            }

            // Update book status
            $book->status = 'Borrowed';
            if (!$book->save()) {
                throw new \Exception('Failed to update book status');
            }

            return response()->json(['message' => 'Livre emprunté avec succès'], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Server error: ' . $e->getMessage()], 500);
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