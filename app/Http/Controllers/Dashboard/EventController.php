<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Country;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::paginate(10);
        return view('dashboard.events.index', compact('events'));
    }

    public function create()
    {
        $events=Country::all();
        return view('dashboard.events.create',compact('events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        try {
            $data = $request->except('primary_image');
            $path = null;
            if ($request->hasFile('primary_image')) {
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs('event-images', $newName, 'public');
            }
            $data['image'] = $path;
            Event::create($data);
            return redirect()->route('dashboard.events.index')
            ->with('success', 'Visa Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.events.index')
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
        $event = Event::findOrFail($id);
        $countries=Country::get();
        return view('dashboard.events.edit', compact('event', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, string $id)
    {
        try {
            $event = Event::findOrFail($id);
            $data = $request->except('primary_image');
            $oldPhotoPath_primary = $event->primary_image;
            $primary_path = $oldPhotoPath_primary;
            if ($request->hasFile('primary_image')) {
                // Delete the old photo
                if ($oldPhotoPath_primary) {
                    Storage::disk('public')->delete($oldPhotoPath_primary);
                }
                // Store the new photo
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $primary_path = $newImage->storeAs('event-images', $newName, 'public');
            }
            $data['image'] = $primary_path;
            $event->update($data);
            return redirect()->route('dashboard.events.index')
            ->with('success', 'Macth Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.events.index')
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
            $event = Event::findOrFail($id);
            if ($event->image != null) {
                Storage::disk('public')->delete($event->image);
            }
            $event->delete();
            return redirect()->route('dashboard.events.index')
            ->with('success', 'event Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.events.index')
            ->with('fail', 'Not Deleted,Please Try Again');
            throw $e;
        }
    }
}
