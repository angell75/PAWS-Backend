<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $primaryKey = 'appointmentId';

    protected $fillable = [
        'vetId', 'petId', 'appointmentDatetime', 'status', 'prognosis'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'vetId', 'userId');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'petId', 'petId');
    }
}
