<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SellerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Seller User',
            'email' => 'seller@paws.com',
            'password' => Hash::make('password123'),
            'contact' => '0987654321',
            'address' => 'Seller Address',
            'userRole' => 'seller',
            'status' => true,
        ]);
    }
}
