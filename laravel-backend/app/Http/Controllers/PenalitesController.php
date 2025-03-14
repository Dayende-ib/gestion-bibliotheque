<?php

namespace App\Http\Controllers;

use App\Models\Members;
use App\Models\Penalites;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PenalitesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penalties = Penalites::all();

        return view('penalites.index', compact('penalties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Members::all();
        return view('penalites.create', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'amount' => 'required|numeric',
        ]);

        $penalty = new Penalites();
        $penalty->member_id = $request->input('member_id');
        $penalty->start_date = $request->input('start_date');
        $penalty->end_date = $request->input('end_date');
        // Calculate the number of days late
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::now();
        $daysLate = $endDate->diffInDays($startDate);

        // Calculate the penalty amount
        $penalty->amount = abs($daysLate * 500); // 500 francs CFA per day
        $penalty->save();

        return redirect()->route('penalites.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penalites $penalites)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penalites $penalites)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penalites $penalites)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penalty = Penalites::findOrFail($id);
        $penalty->delete();

        return redirect()->route('penalites.index')->with('success', 'Pénalité supprimée avec succès.');
    }
}
