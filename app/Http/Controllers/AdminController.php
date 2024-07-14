<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\User;
use App\Models\Pet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function getDashboardData()
    {
        // Fetch the total counts
        $totalUsers = User::where('userRole', '!=', 'admin')->count();
        $totalVets = User::where('userRole', 'vet')->count();
        $totalPets = Pet::count();
        $totalAdoptedPets = Pet::where('adoptionStatus', 'adopted')->count();
        $totalEnquiries = 0; // Adjust if you have an enquiries model
        $totalDonations = Donation::sum('amount');
    
        Log::info('Total Donations: ', ['totalDonations' => $totalDonations]);
    
        // Fetch the user role counts
        $userRoleCounts = [
            'customer' => User::where('userRole', 'customer')->count(),
            'vet' => User::where('userRole', 'vet')->count(),
        ];
    
        Log::info('User Role Counts: ', ['userRoleCounts' => $userRoleCounts]);
    
        // Fetch the monthly donations data
        $monthlyDonations = Donation::select(
            DB::raw('YEAR(donationDate) as year'),
            DB::raw('MONTH(donationDate) as month'),
            DB::raw('SUM(amount) as total')
        )
        ->groupBy('year', 'month')
        ->get();
    
        Log::info('Monthly Donations: ', ['monthlyDonations' => $monthlyDonations]);
    
        // Fetch pets data
        $pets = Pet::all();
    
        // Count adoption status
        $adoptionStatusCounts = [
            'available' => Pet::where('adoptionStatus', 'available')->count(),
            'adopted' => Pet::where('adoptionStatus', 'adopted')->count(),
            'pending' => Pet::where('adoptionStatus', 'pending')->count(),
        ];

        Log::info('Adoption Status Counts: ', ['adoptionStatusCounts' => $adoptionStatusCounts]);
    
        return response()->json([
            'totalUsers' => $totalUsers,
            'totalVets' => $totalVets,
            'totalPets' => $totalPets,
            'totalAdoptedPets' => $totalAdoptedPets,
            'totalEnquiries' => $totalEnquiries,
            'totalDonations' => $totalDonations,
            'userRoleCounts' => $userRoleCounts,
            'monthlyDonations' => $monthlyDonations,
            'pets' => $pets,
            'adoptionStatusCounts' => $adoptionStatusCounts,
        ]);
    }

    public function createUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|regex:/^[a-zA-Z0-9._%+-]+@vetcare\.com$/',
            'contact' => 'required|string|regex:/^012\s\d{3}\s\d{4}$/',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['userRole'] = 'vet';

        $user = User::create($data);

        return response()->json($user, 201);
    }

    public function updateUser(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->userId . ',userId|regex:/^[a-zA-Z0-9._%+-]+@vetcare\.com$/',
            'contact' => 'required|string|regex:/^012\s\d{3}\s\d{4}$/',
            'address' => 'required|string|max:255',
            'status' => 'required|in:1,2',
        ]);

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json($user, 200);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}
