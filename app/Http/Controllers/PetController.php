<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'petImage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'breed' => 'required|string|max:255',
            'gender' => 'required|string|max:6',
            'age' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'vaccineStatus' => 'required|string',
            'vaccineDate' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pet = new Pet();

        if ($request->hasFile('petImage')) {
            // Delete the old image if it exists
            if ($pet->petImage) {
                Storage::disk('s3')->delete($pet->petImage);
            }

            // Upload the new image
            $imagePath = $request->file('petImage')->store('petImage', 's3');

            Storage::url($imagePath);

            $pet->petImage = 'http://localhost:9000/paws/'.$imagePath;
        }

        $pet->name = $request->name;
        $pet->breed = $request->breed;
        $pet->gender = $request->gender;
        $pet->age = $request->age;
        $pet->description = $request->description;
        $pet->vaccineStatus = $request->vaccineStatus;
        $pet->vaccineDate = $request->vaccineDate;
        $pet->userId = auth()->id();
        $pet->save();

        return response()->json(['message' => 'Pet uploaded successfully', 'pet' => $pet], 201);
    }
    public function getPetList()
    {
        $pets = Pet::all();
        return response()->json($pets);
    }
}
