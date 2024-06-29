<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|integer',
            'products' => 'required|array',
            'products.*.productId' => 'required|integer|exists:products,productId',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric',
            'orderDate' => 'required|date',
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

        $orderData = $request->except('products');
        $order = Order::create($orderData);

        foreach ($request->products as $product) {
            $order->products()->attach($product['productId'], [
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ]);
        }

        return response()->json($order->load('products'), 201);
    }

    public function getOrdersByUser($userId)
    {
        $orders = Order::where('userId', $userId)->with('products')->get();
        return response()->json($orders);
    }
}
