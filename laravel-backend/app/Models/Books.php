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
        'description',
        'isbn',
        'published_year',
        'status',
        'image',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->title = $model->title ?? 'Sans titre';
            $model->author = $model->author ?? 'Unknown author';
            $model->isbn = $model->isbn ?? 'Unknown ISBN';
            $model->description = $model->description ?? 'Pas de description disponible';
        });
    }

    public function loans()
    {
        return $this->hasOne(Loans::class, 'book_id');
    }
}
