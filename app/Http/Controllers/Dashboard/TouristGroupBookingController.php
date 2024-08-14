<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TouristGroupBooking;
use Illuminate\Http\Request;

class TouristGroupBookingController extends Controller
{
      /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tourists_booking = TouristGroupBooking::paginate(10);
        return view('dashboard.tourist_booking.index', compact('tourists_booking'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tourist_booking = TouristGroupBooking::findOrFail($id);
        $tourist_booking->delete();
        return redirect()->back()->with('success', 'Deleted Successfully');
    }

    public function accept(Request $request, string $id)
    {
        $tourist_booking = TouristGroupBooking::findOrFail($id);
        $tourist_booking->update(['status' => 'accepted']);
        return redirect()->back()->with('success', 'Accepted Successfully');
    }

    public function reject(Request $request, string $id)
    {
        $tourist_booking = TouristGroupBooking::findOrFail($id);
        $tourist_booking->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Rejected Successfully');
    }
}
