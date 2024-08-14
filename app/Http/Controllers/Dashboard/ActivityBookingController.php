<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityBooking;
use Illuminate\Http\Request;

class ActivityBookingController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities_booking = ActivityBooking::paginate(10);
        return view('dashboard.activity_booking.index', compact('activities_booking'));
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
        $activity_booking = ActivityBooking::findOrFail($id);
        $activity_booking->delete();
        return redirect()->back()->with('success', 'Deleted Successfully');
    }

    public function accept(Request $request, string $id)
    {
        $activity_booking = ActivityBooking::findOrFail($id);
        $activity_booking->update(['status' => 'accepted']);
        return redirect()->back()->with('success', 'Accepted Successfully');
    }

    public function reject(Request $request, string $id)
    {
        $activity_booking = ActivityBooking::findOrFail($id);
        $activity_booking->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Rejected Successfully');
    }
}
