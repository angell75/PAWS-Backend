<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Add item to cart.
     */
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|integer',
            'productId' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cartItem = Cart::updateOrCreate(
            ['userId' => $request->userId, 'productId' => $request->productId],
            ['quantity' => \DB::raw('quantity + ' . $request->quantity)]
        );

        return response()->json($cartItem, 201);
    }
}
