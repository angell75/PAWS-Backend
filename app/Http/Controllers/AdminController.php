<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\User;
use App\Models\Pet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        return response()->json([
            'totalUsers' => $totalUsers,
            'totalVets' => $totalVets,
            'totalPets' => $totalPets,
            'totalAdoptedPets' => $totalAdoptedPets,
            'totalEnquiries' => $totalEnquiries,
            'totalDonations' => $totalDonations,
            'userRoleCounts' => $userRoleCounts,
            'monthlyDonations' => $monthlyDonations,
        ]);
    }
}
