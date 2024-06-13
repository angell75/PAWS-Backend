<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'userId';

    protected $fillable = [
        'name', 'email', 'password', 'contact', 'address', 'userRole', 'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'sellerId', 'userId');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'userId', 'userId');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'userId', 'userId');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'userId', 'userId');
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class, 'userId', 'userId');
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'shelterId', 'userId');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'vet_id', 'userId');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'userId', 'userId');
    }

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class, 'userId', 'userId');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'vetId', 'userId');
    }
}

