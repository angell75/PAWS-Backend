<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enquiry;

class EnquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $enquiry = Enquiry::create([
            'userId' => auth()->id(),
            'message' => $validated['message'],
            'date' => now(),
            'status' => 'pending', 
        ]);

        return response()->json($enquiry, 201);
    }
}
