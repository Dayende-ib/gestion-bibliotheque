<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Members;

class Penalites extends Model
{
    public function member()
    {
        return $this->belongsTo(Members::class);
    }
}
