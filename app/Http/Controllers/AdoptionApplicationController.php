<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class AdoptionApplicationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'userId' => 'required|exists:users,userId',
            'petId' => 'required|exists:pets,petId',
            'applicationDate' => 'required|date',
            'scheduleDate' => 'nullable|date',
            'scheduleTime' => 'nullable|date_format:H:i',
            'scheduleLocation' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $application = Application::create($validated);

        return response()->json($application, 201);
    }
}
