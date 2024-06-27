<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Create a new order.
     */
    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|integer',
            'productId' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'orderDate' => 'required|date',
            'price' => 'required|numeric',
            'status' => 'required|string',
            'name' => 'required|string',
            'contact' => 'required|string',
            'address' => 'required|string',
            'card_name' => 'required|string',
            'card_number' => 'required|string',
            'card_expiry' => 'required|string',
            'card_cvc' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::create($request->all());

        return response()->json($order, 201);
    }

    /**
     * Get orders by user ID.
     */
    public function getOrdersByUser($userId)
    {
        $orders = Order::where('userId', $userId)->with('product')->get();
        return response()->json($orders);
    }
}
