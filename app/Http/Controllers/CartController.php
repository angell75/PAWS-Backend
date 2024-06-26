<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // Import the DB facade

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
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cartItem = Cart::updateOrCreate(
            ['userId' => $request->userId, 'productId' => $request->productId],
            ['quantity' => DB::raw('quantity + ' . $request->quantity), 'price' => $request->price]
        );

        $latestCartItem = Cart::with('product')->where('userId', $request->userId)->get();

        if ($latestCartItem->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 200);
        }

        $detailedCartItems = $latestCartItem->map(function ($item) {
            return [
                'cartId' => $item->cartId,
                'userId' => $item->userId,
                'productId' => $item->productId,
                'productName' => $item->product->name,
                'productImage' => $item->product->image,
                'productCategory' => $item->product->category,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
            ];
        });

        return response()->json($detailedCartItems, 201);
    }

    /**
     * Get cart items for a specific user.
     */
    public function getCartItems($userId)
    {
        $cartItems = Cart::with('product')->where('userId', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 200);
        }

        $detailedCartItems = $cartItems->map(function ($item) {
            return [
                'cartId' => $item->cartId,
                'userId' => $item->userId,
                'productId' => $item->productId,
                'productName' => $item->product->name,
                'productImage' => $item->product->image,
                'productCategory' => $item->product->category,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
            ];
        });

        return response()->json($detailedCartItems, 200);
    }


        /**
     * Update cart item quantity.
     */
    public function updateCartItem(Request $request, $cartId)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cartItem = Cart::find($cartId);

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json($cartItem, 200);
    }

    /**
     * Remove item from cart.
     */
    public function removeFromCart($cartId)
    {
        $cartItem = Cart::find($cartId);
        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['message' => 'Item removed from cart'], 200);
        } else {
            return response()->json(['message' => 'Item not found'], 404);
        }
    }
}
