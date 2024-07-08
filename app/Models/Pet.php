<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $primaryKey = 'petId';

    protected $fillable = [
        'petImage', 'name', 'breed', 'gender', 'age', 'description', 'diagnosis', 'vaccineStatus','vaccine_date', 'adoptionStatus'
    ];

    public function applications()
    {
        return $this->hasMany(Application::class, 'petId', 'petId');
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class, 'petId', 'petId');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'petId', 'petId');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }
}

