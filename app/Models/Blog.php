<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $primaryKey = 'blogId';

    protected $fillable = [
        'shelterId', 'title', 'subject', 'description', 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'shelterId', 'userId');
    }
}

