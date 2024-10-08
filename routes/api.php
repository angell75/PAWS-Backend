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
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;

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
    Route::get('/pets/user/{userId}', [PetController::class, 'getUserPets']);
    Route::get('/pets-with-owners', [PetController::class, 'getPetListWithOwners']);

    //Route for User
    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::get('/profile', [UserController::class, 'getProfile']);
    Route::post('/updateProfile', [UserController::class, 'updateProfile']);
    Route::post('/changePassword', [UserController::class, 'changePassword']);
    Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
    Route::get('/users/{userId}', [UserController::class, 'getUserById']);

    //Route for Auth
    Route::get('/users/{id}', [AuthController::class, 'getUserById']);

    //Route for Donation
    Route::post('/donations', [DonationController::class, 'store']);
    Route::get('/donations', [DonationController::class, 'getAllDonations']);

    //Route for Enquiries
    Route::post('/enquiries', [EnquiryController::class, 'store']);
    Route::get('/enquiries', [EnquiryController::class, 'index']);
    Route::get('/enquiries/{id}', [EnquiryController::class, 'show']);
    Route::put('enquiries/{id}/update-status', [EnquiryController::class, 'updateStatus']);

    //Route for Application
    Route::post('/applications', [AdoptionApplicationController::class, 'store']);
    Route::get('applications/{userId}', [AdoptionApplicationController::class, 'fetchMyApplications']);
    Route::post('/applications/{applicationId}/approve', [AdoptionApplicationController::class, 'approve']);
    Route::post('/applications/{applicationId}/confirm', [AdoptionApplicationController::class, 'confirm']);
    Route::get('/applications', [AdoptionApplicationController::class, 'getAllApplications']);

        
    //Route for Product
    Route::post('/products', [ProductController::class, 'store']);
    Route::post('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);;
    Route::get('/product-summary', [ProductController::class, 'getProductSummary']);
    Route::get('/products', [ProductController::class, 'getAllProduct']);

    //Route for Cart
    Route::post('/cart', [CartController::class, 'addToCart']);
    Route::get('/cart/{userId}', [CartController::class, 'getCartItems']);
    Route::put('/cart/{cartId}', [CartController::class, 'updateCartItem']);
    Route::delete('/cart/{cartId}', [CartController::class, 'removeFromCart']); 

    //Route for Order
    Route::post('/orders', [OrderController::class, 'createOrder']);
    Route::get('/orders/user/{userId}', [OrderController::class, 'getOrdersByUser']);
    Route::get('/orders', [OrderController::class, 'getAllOrders']); 
    Route::put('/orders/update-status/{orderId}', [OrderController::class, 'updateOrderStatus']);
    Route::get('/summary', [OrderController::class, 'getSummaryData']);

    //Route for Appointment
    Route::get('/appointments/user/{userId}', [AppointmentController::class, 'getUserAppointments']);
    Route::post('/appointments', [AppointmentController::class, 'createAppointment']);
    Route::post('/appointments/{appointmentId}/cancel', [AppointmentController::class, 'cancelAppointment']);
    Route::get('/appointments/vet/{vetId}', [AppointmentController::class, 'getAppointmentsByVet']);
    Route::get('/appointments/pet/{petId}', [AppointmentController::class, 'getAppointmentsByPet']);
    Route::post('/appointments/pets/{petId}/appointments/{appointmentId}', [AppointmentController::class, 'updatePetAndAppointment']);

    //Route for Admin
    Route::get('/admin/dashboard', [AdminController::class, 'getDashboardData']);
    Route::post('/users', [AdminController::class, 'createUser']);
    Route::put('/users/{id}', [AdminController::class, 'updateUser']);
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);

    // Route for Blog
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::get('/blogs/{id}', [BlogController::class, 'show']);
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::post('/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);

});
