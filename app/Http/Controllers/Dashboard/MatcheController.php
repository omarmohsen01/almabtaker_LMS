<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\MatcheRequest;
use App\Models\Matche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MatcheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matches=Matche::paginate(10);
        return view('dashboard.matches.index',compact('matches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.matches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MatcheRequest $request)
    {
        try {
            $data=$request->except('seat_image','ticket_image');
            $path = null;
            if ($request->hasFile('seat_image')) {
                $newImage = $request->file('seat_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs('matche-images', $newName, 'public');
            }
            $data['seat_image'] = $path;
            $path = null;
            if ($request->hasFile('ticket_image')) {
                $newImage = $request->file('ticket_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs('matche-images', $newName, 'public');
            }
            $data['ticket_image'] = $path;
            Matche::create($data);
            return redirect()->route('dashboard.matches.index')
                ->with('success', 'Macth Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.matches.index')
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
        $matche=Matche::findOrFail($id);
        return view('dashboard.matches.edit',compact('matche'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MatcheRequest $request, string $id)
    {
        try {
            $matche=Matche::findOrFail($id);
            $data=$request->except('seat_image','ticket_image');
            $oldPhotoPath_seat = $matche->seat_image;
            $seat_path = $oldPhotoPath_seat;
            if ($request->hasFile('primary_image')) {
                // Delete the old photo
                if ($oldPhotoPath_seat) {
                    Storage::disk('public')->delete($oldPhotoPath_seat);
                }
                // Store the new photo
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $seat_path = $newImage->storeAs('matche-images', $newName, 'public');
            }
            $data['seat_image'] = $seat_path;
            $oldPhotoPath_ticket = $matche->ticket_image;
            $ticket_path = $oldPhotoPath_ticket;
            if ($request->hasFile('primary_image')) {
                // Delete the old photo
                if ($oldPhotoPath_ticket) {
                    Storage::disk('public')->delete($oldPhotoPath_ticket);
                }
                // Store the new photo
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $ticket_path = $newImage->storeAs('matche-images', $newName, 'public');
            }
            $data['ticket_image'] = $ticket_path;
            $matche->update($data);
            return redirect()->route('dashboard.matches.index')
                ->with('success', 'Macth Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.matches.index')
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
            $matche = Matche::findOrFail($id);
            if($matche->image!=null){
                Storage::disk('public')->delete($matche->seat_image);
                Storage::disk('public')->delete($matche->ticket_image);
            }
            $matche->delete();
            return redirect()->route('dashboard.matches.index')
                ->with('success', 'matche Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.matches.index')
            ->with('fail', 'Not Deleted,Please Try Again');
            throw $e;
        }
    }
}
