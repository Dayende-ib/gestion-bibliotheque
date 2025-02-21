<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Books extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'published_year',
        'status'
    ];

    public function loans()
    {
        return $this->hasMany(Loans::class);
    }
}
