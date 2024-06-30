<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('image')) {
            // Upload the new image
            $imagePath = $request->file('image')->store('productImages', 's3');
            Storage::url($imagePath);
            $imagePath = 'http://localhost:9000/paws/' . $imagePath;
        } else {
            $imagePath = null;
        }

        $product = Product::create([
            'sellerId' => auth()->id(),
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $imagePath,
        ]);

        return response()->json($product, 201);
    }

    /**
     * Update Specific Product
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        Log::info('Update Request Data:', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            Log::error('Validation Errors:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('productImages', 's3');
            Storage::url($imagePath);
            $product->image = 'http://localhost:9000/paws/' . $imagePath;
        }

        // Update fields
        $product->name = $request->name;
        $product->category = $request->category;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;

        $product->save();

        return response()->json(['message' => 'Product updated successfully.', 'product' => $product]);
    }

    //Delete Product
    public function destroy($id)
    {
        $product = Product::where('sellerId', auth()->id())->findOrFail($id);

        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }

        $product->delete();

        return response()->json(null, 204);
    }

    public function getProductSummary()
    {
        $totalProducts = Product::count();
        $totalCustomers = User::where('userRole', 'customer')->count();
        $totalOrders = Order::count();

        $orderStatusCounts = Order::select(DB::raw('count(*) as count, status'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $productCategoryCounts = Product::select(DB::raw('count(*) as count, category'))
            ->groupBy('category')
            ->pluck('count', 'category');

        return response()->json([
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'totalOrders' => $totalOrders,
            'orderStatusCounts' => $orderStatusCounts,
            'productCategoryCounts' => $productCategoryCounts,
        ]);
    }

        /**
     * Retrieve all products.
     */
    public function getAllProduct()
    {
        $products = Product::all();
        return response()->json($products);
    }

}
