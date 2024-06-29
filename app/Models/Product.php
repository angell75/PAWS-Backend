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

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products', 'productId', 'orderId')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'productId', 'productId');
    }
}
