<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PetController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'breed' => 'required|string|max:255',
            'gender' => 'required|string|max:6',
            'age' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'vaccineStatus' => 'boolean',
            'vaccineDate' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pet = new Pet();
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $pet->image = $imagePath;
        }
        $pet->name = $request->name;
        $pet->breed = $request->breed;
        $pet->gender = $request->gender;
        $pet->age = $request->age;
        $pet->description = $request->description;
        $pet->vaccine_status = $request->vaccineStatus;
        $pet->vaccine_date = $request->vaccineDate;
        $pet->save();

        return response()->json(['message' => 'Pet uploaded successfully', 'pet' => $pet], 201);
    }
}

