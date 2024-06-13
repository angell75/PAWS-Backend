<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $primaryKey = 'scheduleId';

    protected $fillable = [
        'vet_id', 'schedule_datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'vet_id', 'userId');
    }
}
