<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enquiry;
use Illuminate\Support\Facades\Response;

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

    public function index()
    {
        $enquiries = Enquiry::with('user')->get();
        return response()->json($enquiries, 200);
    }

    public function show($id)
    {
        $enquiry = Enquiry::with('user')->find($id);

        if (is_null($enquiry)) {
            return response()->json(['message' => 'Enquiry not found'], 404);
        }

        return response()->json($enquiry, 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $enquiry = Enquiry::find($id);
        if (!$enquiry) {
            return Response::json(['message' => 'Enquiry not found'], 404)
                            ->header('Access-Control-Allow-Origin', '*')
                            ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
                            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization');
        }
    
        $enquiry->status = $request->status;
        $enquiry->save();
    
        return Response::json($enquiry, 200)
                        ->header('Access-Control-Allow-Origin', '*')
                        ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
                        ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization');
    }

}
