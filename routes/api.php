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
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;


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
    Route::get('applications/{userId}', [AdoptionApplicationController::class, 'fetchMyApplications']);
    Route::post('/applications/{applicationId}/approve', [AdoptionApplicationController::class, 'approve']);
    Route::post('/applications/{applicationId}/confirm', [AdoptionApplicationController::class, 'confirm']);
    
    //Route for Product
    Route::get('/products', [ProductController::class, 'getAllProduct']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::post('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);;

    //Route for Cart
    Route::post('/cart', [CartController::class, 'addToCart']);
    Route::get('/cart/{userId}', [CartController::class, 'getCartItems']);
    Route::put('/cart/{cartId}', [CartController::class, 'updateCartItem']);
    Route::delete('/cart/{cartId}', [CartController::class, 'removeFromCart']);

    //Route for Order
    Route::post('/orders', [OrderController::class, 'createOrder']);
});
