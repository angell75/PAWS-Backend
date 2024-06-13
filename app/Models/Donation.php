<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $primaryKey = 'donationId';

    protected $fillable = [
        'userId', 'amount', 'donationDate'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }
}

