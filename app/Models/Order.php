<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'orderId';

    protected $fillable = [
        'userId', 'orderDate', 'status', 'name', 'contact', 'address', 'card_name', 'card_number', 'card_expiry', 'card_cvc'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products', 'orderId', 'productId')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
}
