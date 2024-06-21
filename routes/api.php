<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\AdoptionApplicationController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/top-pets', [PetController::class, 'topPets']);

Route::middleware(['auth:sanctum', 'auth.user'])->group(function () {
    //Route for Pet
    Route::get('/pets', [PetController::class, 'getPetList']);
    Route::post('/pets', [PetController::class, 'store']);
    Route::get('/pets/{id}', [PetController::class, 'getPetById']);
    Route::post('/pets/{id}', [PetController::class, 'update']);
    Route::delete('/pets/{id}', [PetController::class, 'deletePet']);

    //Route for User
    Route::get('/profile', [UserController::class, 'getProfile']);
    Route::post('/updateProfile', [UserController::class, 'updateProfile']);
    Route::post('/changePassword', [UserController::class, 'changePassword']);

    //Route for Auth
    Route::get('/users/{id}', [AuthController::class, 'getUserById']);

    //Route for Donation
    Route::post('/donations', [DonationController::class, 'store']);

    //Route for Enquiries
    Route::post('/enquiries', [EnquiryController::class, 'store']);

    //Route for Application
    Route::post('/applications', [AdoptionApplicationController::class, 'store']);

});
