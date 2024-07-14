<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::all();
        return response()->json($blogs);
    }

    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        return response()->json($blog);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|max:2048', // validation for image
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('images', 'public') : null;

        $blog = Blog::create([
            'shelterId' => $request->shelterId,
            'title' => $request->title,
            'subject' => $request->subject,
            'description' => $request->description,
            'date' => $request->date,
            'image' => $imagePath,
        ]);

        return response()->json($blog, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|max:2048', // validation for image
        ]);

        $blog = Blog::findOrFail($id);

        if ($request->hasFile('image')) {
            // delete old image if exists
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $imagePath = $request->file('image')->store('images', 'public');
        } else {
            $imagePath = $blog->image;
        }

        $blog->update([
            'shelterId' => $request->shelterId,
            'title' => $request->title,
            'subject' => $request->subject,
            'description' => $request->description,
            'date' => $request->date,
            'image' => $imagePath,
        ]);

        return response()->json($blog);
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully']);
    }
}
