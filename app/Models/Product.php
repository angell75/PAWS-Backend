<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'productId';

    protected $fillable = [
        'sellerId', 'name', 'category', 'description', 'price', 'quantity', 'image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'sellerId', 'userId');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'productId', 'productId');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'productId', 'productId');
    }
}


