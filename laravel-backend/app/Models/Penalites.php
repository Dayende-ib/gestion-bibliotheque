<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Penalites extends Model
{
    use HasFactory;

    public function loan()
    {
        return $this->belongsTo(Loans::class);
    }

    public function member()
    {
        return $this->belongsTo(Members::class);
    }

    public function calculateAmountDue()
    {
        $endDate = Carbon::parse($this->end_date);
        $currentDate = Carbon::now();

        if ($currentDate->greaterThan($endDate)) {
            $daysLate = $currentDate->diffInDays($endDate);
            return abs($daysLate * 500); // 500 francs CFA par jour
        }

        return 0;
    }
}