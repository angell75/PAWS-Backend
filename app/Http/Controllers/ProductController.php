<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->userRole !== 'seller') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $products = Product::where('sellerId', $user->userId)->get();
        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product-images', 'public');
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
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::where('sellerId', auth()->id())->findOrFail($id);
    
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);
    
        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product-images', 'public');
            $product->update(['image' => $imagePath]);
        }
    
        // Update product with other details
        $product->update($request->except('image'));
    
        return response()->json($product);
    }
    

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $product = Product::where('sellerId', auth()->id())->findOrFail($id);
        $product->delete();

        return response()->json(null, 204);
    }
}

