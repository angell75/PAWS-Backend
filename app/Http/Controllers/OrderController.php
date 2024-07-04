<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

        DB::beginTransaction();

        try {
            $orderData = $request->except('products');
            $order = Order::create($orderData);

            foreach ($request->products as $product) {
                $order->products()->attach($product['productId'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                ]);

                $productModel = Product::find($product['productId']);
                if ($productModel) {
                    $productModel->quantity -= $product['quantity'];
                    if ($productModel->quantity < 0) {
                        DB::rollBack();
                        return response()->json(['message' => 'Not enough product quantity available'], 400);
                    }
                    $productModel->save();
                }
            }

            DB::commit();
            return response()->json($order->load('products'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error creating order', 'error' => $e->getMessage()], 500);
        }
    }

    public function getOrdersByUser()
    {
        $userId = Auth::id(); 
        $orders = Order::where('userId', $userId)->with('products')->get();
        return response()->json($orders);
    }

    public function getSummaryData()
    {
        $totalProducts = Product::count();
        $totalCustomers = User::where('userRole', 'customer')->count();
        $totalOrders = Order::count();
    
        $orderStatusCounts = [
            'Order Received' => 0,
            'Pending Payment' => 0,
            'Shipped' => 0,
            'Completed' => 0,
        ];
    
        $orderStatusCountsFromDB = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    
        foreach ($orderStatusCountsFromDB as $status => $count) {
            $orderStatusCounts[$status] = $count;
        }
    
        $productCategoryCounts = [
            'food' => 0,
            'treat' => 0,
            'training-needs' => 0,
            'clothes-accessories' => 0,
            'supplies-others' => 0,
        ];
    
        $productCategoryCountsFromDB = Product::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();
    
        foreach ($productCategoryCountsFromDB as $category => $count) {
            $productCategoryCounts[$category] = $count;
        }
    
        return response()->json([
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'totalOrders' => $totalOrders,
            'orderStatusCounts' => $orderStatusCounts,
            'productCategoryCounts' => $productCategoryCounts,
        ]);
    }

    public function getAllOrders()
    {
        $orders = Order::with('products')->get();
        return response()->json($orders);
    }  

    public function updateOrderStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $validator = Validator::make($request->all(), [
            'status' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $order->status = $request->status;
        $order->save();
    
        return response()->json(['message' => 'Order status updated successfully!', 'order' => $order], 200);
    }
    
}

