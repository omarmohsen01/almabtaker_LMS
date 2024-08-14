<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::paginate(10);
        return view('dashboard.activities.index', compact('activities'));
    }

    public function create()
    {
        $activities=Country::all();
        return view('dashboard.activities.create',compact('activities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ActivityRequest $request)
    {
        try {
            $data = $request->except('primary_image');
            $path = null;
            if ($request->hasFile('primary_image')) {
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs('activity-images', $newName, 'public');
            }
            $data['image'] = $path;
            Activity::create($data);
            return redirect()->route('dashboard.activities.index')
            ->with('success', 'Visa Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.activities.index')
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
        $activity = Activity::findOrFail($id);
        $countries=Country::get();
        return view('dashboard.activities.edit', compact('activity', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActivityRequest $request, string $id)
    {
        try {
            $activity = Activity::findOrFail($id);
            $data = $request->except('primary_image');
            $oldPhotoPath_primary = $activity->primary_image;
            $primary_path = $oldPhotoPath_primary;
            if ($request->hasFile('primary_image')) {
                // Delete the old photo
                if ($oldPhotoPath_primary) {
                    Storage::disk('public')->delete($oldPhotoPath_primary);
                }
                // Store the new photo
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $primary_path = $newImage->storeAs('activity-images', $newName, 'public');
            }
            $data['image'] = $primary_path;
            $activity->update($data);
            return redirect()->route('dashboard.activitys.index')
            ->with('success', 'Macth Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.activitys.index')
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
            $activity = Activity::findOrFail($id);
            if ($activity->image != null) {
                Storage::disk('public')->delete($activity->image);
            }
            $activity->delete();
            return redirect()->route('dashboard.activitys.index')
            ->with('success', 'activity Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.activitys.index')
            ->with('fail', 'Not Deleted,Please Try Again');
            throw $e;
        }
    }
}
