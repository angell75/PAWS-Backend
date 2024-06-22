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

    public function fetchMyApplications($userId)
    {
        $applications = Application::whereHas('pet', function ($query) use ($userId) {
            $query->where('userId', $userId);
        })->with('pet')->get();
    
        return response()->json($applications);
    }

    public function approve($applicationId)
    {
        $application = Application::findOrFail($applicationId);
        $application->status = 'approved';
        $application->save();
    
        return response()->json($application, 200);
    }
    
    public function confirm($applicationId)
    {
        $application = Application::findOrFail($applicationId);
        $application->status = 'confirmed';
        $application->save();
    
        // Also update the pet status to 'not available'
        $pet = $application->pet;
        $pet->adoptionStatus = 'adopted';
        $pet->save();
    
        return response()->json($application, 200);
    }
}
