<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
        ]);
    
        $donation = Donation::create([
            'userId' => auth()->id(),
            'amount' => $validated['amount'],
            'donationDate' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        return response()->json($donation, 201);
    }

    public function getAllDonations()
    {
        $donations = Donation::all();
        return response()->json($donations);
    }
    
}
