<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'orderId';

    protected $fillable = [
        'userId', 'productId', 'quantity', 'orderDate', 'price', 'status',
        'name', 'contact', 'address', 'card_name', 'card_number', 'card_expiry', 'card_cvc'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'productId', 'productId');
    }
}


