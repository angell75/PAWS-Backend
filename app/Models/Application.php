<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $primaryKey = 'applicationId';

    protected $fillable = [
        'userId', 'petId', 'applicationDate', 'scheduleDate', 'scheduleTime', 'scheduleLocation', 'status'
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
