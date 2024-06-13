<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;

    protected $primaryKey = 'favouriteId';

    protected $fillable = [
        'userId', 'petId'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'petId', 'petId');
    }
}
