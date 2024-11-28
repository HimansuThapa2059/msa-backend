<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Msa extends Model
{
    use HasFactory;

    protected $table = 'msa';

    protected $fillable = [
        'name',
        'type',
        'rating',
        'genre',
        'description'
    ];

    // Cast 'genre' to an array automatically when retrieving from the database
    protected $casts = [
        'genre' => 'array', // Ensures genre is automatically cast to an array
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
