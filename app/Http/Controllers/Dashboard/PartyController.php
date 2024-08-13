<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartyRequest;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartyController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parties = Party::paginate(10);
        return view('dashboard.parties.index', compact('parties'));
    }

    public function create()
    {
        return view('dashboard.parties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PartyRequest $request)
    {
        try {
            $data=$request->except('seat_image','ticket_image');
            $path = null;
            if ($request->hasFile('seat_image')) {
                $newImage = $request->file('seat_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs('party-images', $newName, 'public');
            }
            $data['seat_image'] = $path;
            $path = null;
            if ($request->hasFile('ticket_image')) {
                $newImage = $request->file('ticket_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs('party-images', $newName, 'public');
            }
            $data['ticket_image'] = $path;
            Party::create($data);
            return redirect()->route('dashboard.parties.index')
            ->with('success', 'party Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.parties.index')
            ->with('fail', 'Something Went Wrong,Please Try Again');
            throw $e;
        }
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
        $party = Party::findOrFail($id);
        return view('dashboard.parties.edit', compact('party'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PartyRequest $request, string $id)
    {
        try {
            $party = Party::findOrFail($id);
            $data=$request->except('seat_image','ticket_image');
            $oldPhotoPath_seat = $party->seat_image;
            $seat_path = $oldPhotoPath_seat;
            if ($request->hasFile('primary_image')) {
                // Delete the old photo
                if ($oldPhotoPath_seat) {
                    Storage::disk('public')->delete($oldPhotoPath_seat);
                }
                // Store the new photo
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $seat_path = $newImage->storeAs('party-images', $newName, 'public');
            }
            $data['seat_image'] = $seat_path;
            $oldPhotoPath_ticket = $party->ticket_image;
            $ticket_path = $oldPhotoPath_ticket;
            if ($request->hasFile('primary_image')) {
                // Delete the old photo
                if ($oldPhotoPath_ticket) {
                    Storage::disk('public')->delete($oldPhotoPath_ticket);
                }
                // Store the new photo
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $ticket_path = $newImage->storeAs('party-images', $newName, 'public');
            }
            $data['ticket_image'] = $ticket_path;
            $party->update($data);
            return redirect()->route('dashboard.parties.index')
            ->with('success', 'Macth Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.parties.index')
            ->with('fail', 'Something Went Wrong,Please Try Again');
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $party = Party::findOrFail($id);
            if ($party->image != null) {
                Storage::disk('public')->delete($party->image);
            }
            $party->delete();
            return redirect()->route('dashboard.parties.index')
            ->with('success', 'party Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.parties.index')
            ->with('fail', 'Not Deleted,Please Try Again');
            throw $e;
        }
    }
}
