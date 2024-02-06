<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Booking;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class CarHireController extends Controller
{
    public function getAvailableCars(Request $request)
    {

        if($request->has('start_date') && $request->has('end_date')){
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
        } else { // if no data range is provided assume a week from now
            $startDate = Carbon::now()->addDays(7)->format('Y-m-d'); 
            $endDate =   Carbon::now()->addDays(14)->format('Y-m-d');
        }
        

        $availableCars = Car::whereDoesntHave('bookings', function ($query) use ($startDate, $endDate) {
            $query->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
            })
                ->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<', $startDate)
                        ->where('end_date', '>', $endDate);
                });
        })->get();

        return response()->json($availableCars);
    }


    public function modifyBooking(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }
        $booking->update($request->all());
        return response()->json($car);
    }

    public function updateBookingCost(Request $request, $id)
    {
        // Validation - I'd normally put this in a separate Request class
        $validator = Validator::make($request->all(), [
            'customer_age' => 'required|integer|min:18', // Example validation rules
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $booking->total_cost = $this->_calculateAdditionalCosts($booking->car->price, $request->customer_age);
        $booking->save();

        
        return response()->json($booking);
    }


    public function deleteBooking($id){
        $booking = Booking::find($id);
        $booking->delete();
        return response()->json(['message' => 'Booking deleted']);
    }

    private function  _calculateAdditionalCosts($price, $age)
    {
        $additionalCost = 0;
        
        if ($age < 25) {
            $additionalCost += 20; // Young driver surcharge
        } elseif ($age > 65) {
            $additionalCost += 15; // Senior driver surcharge
        }

        return $price + $additionalCost;
    }
}
