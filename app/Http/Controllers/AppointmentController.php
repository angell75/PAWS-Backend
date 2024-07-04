<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    // Fetch all appointments for a user
    public function getUserAppointments($userId)
    {
        try {
            $appointments = Appointment::whereHas('pet', function ($query) use ($userId) {
                $query->where('userId', $userId);
            })
            ->where('status', '!=', 'cancelled') // Exclude cancelled appointments
            ->with(['pet', 'vet'])
            ->get();
    
            return response()->json($appointments);
        } catch (\Exception $e) {
            Log::error('Failed to fetch appointments: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch appointments'], 500);
        }
    }
    

    public function createAppointment(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'userId' => 'required|integer|exists:users,userId',
            'petId' => 'required|integer|exists:pets,petId',
            'appointmentDatetime' => 'required|date_format:Y-m-d\TH:i:s',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        try {
            // Find an available vet
            $availableVet = User::where('userRole', 'vet')
                ->whereNotIn('userId', function ($query) use ($request) {
                    $query->select('vetId')
                        ->from('appointments')
                        ->where('appointmentDatetime', $request->appointmentDatetime);
                })
                ->first();
    
            if (!$availableVet) {
                return response()->json(['error' => 'No available vet for the selected date and time'], 400);
            }
    
            $appointment = new Appointment();
            $appointment->vetId = $availableVet->userId; // Update to use correct column name
            $appointment->petId = $request->petId;
            $appointment->appointmentDatetime = $request->appointmentDatetime;
            $appointment->status = 'pending';
            $appointment->save();
    
            return response()->json($appointment);
        } catch (\Exception $e) {
            Log::error('Failed to create appointment: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create appointment: ' . $e->getMessage()], 500);
        }
    }
    
    public function cancelAppointment($appointmentId)
    {
        $appointment = Appointment::find($appointmentId);
    
        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], 404);
        }
    
        $appointment->status = 'cancelled';
        $appointment->save();
    
        return response()->json(['message' => 'Appointment cancelled successfully']);
    }
    
    
}
