<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subject' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imagePath = $request->file('image') ? $request->file('image')->store('images', 's3') : null;

        $blog = Blog::create([
            'shelterId' => $request->shelterId,
            'title' => $request->title,
            'subject' => $request->subject,
            'description' => $request->description,
            'date' => $request->date,
            'image' => $imagePath ? 'http://localhost:9000/paws/' . $imagePath : null,
        ]);

        return response()->json($blog, 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subject' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $blog = Blog::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('s3')->delete($blog->image);
            }
            $imagePath = $request->file('image')->store('images', 's3');
            $blog->image = 'http://localhost:9000/paws/' . $imagePath;
        }

        $blog->update([
            'shelterId' => $request->shelterId,
            'title' => $request->title,
            'subject' => $request->subject,
            'description' => $request->description,
            'date' => $request->date,
            'image' => $blog->image,
        ]);

        return response()->json($blog);
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->image) {
            Storage::disk('s3')->delete($blog->image);
        }

        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully']);
    }
}
