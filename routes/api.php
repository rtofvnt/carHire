<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CarHireController;


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

Route::get('/cars/available',               [CarHireController::class, 'getAvailableCars']);
Route::post('/car_hire/{id}/modify',        [CarHireController::class, 'modifyBooking']);
Route::delete('/car_hire/{id}',             [CarHireController::class, 'deleteBooking']);

Route::post('car_hire/{id}/update_booking_cost',    [CarHireController::class, 'updateBookingCost']);




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
