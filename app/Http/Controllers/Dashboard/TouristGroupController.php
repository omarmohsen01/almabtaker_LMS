<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\TouristGroupRequest;
use App\Models\Country;
use App\Models\TouristGroup;
use App\Models\Visa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TouristGroupController extends Controller
{
    public function index()
    {
        $tourist_groups = TouristGroup::paginate(10);
        return view('dashboard.tourist-groups.index', compact('tourist_groups'));
    }

    public function create()
    {
        $countries=Country::all();
        return view('dashboard.tourist-groups.create',compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TouristGroupRequest $request)
    {
        try {
            $data = $request->except('primary_image');
            $path = null;
            if ($request->hasFile('primary_image')) {
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs('visa-images', $newName, 'public');
            }
            $data['image'] = $path;
            TouristGroup::create($data);
            return redirect()->route('dashboard.tourist-groups.index')
            ->with('success', 'Visa Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.tourist-groups.index')
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
        $tourist_grooup = TouristGroup::findOrFail($id);
        $countries=Country::get();
        return view('dashboard.tourist-groups.edit', compact('visa', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TouristGroupRequest $request, string $id)
    {
        try {
            $tourist_grooup = TouristGroup::findOrFail($id);
            $data = $request->except('primary_image');
            $oldPhotoPath_primary = $tourist_grooup->primary_image;
            $primary_path = $oldPhotoPath_primary;
            if ($request->hasFile('primary_image')) {
                // Delete the old photo
                if ($oldPhotoPath_primary) {
                    Storage::disk('public')->delete($oldPhotoPath_primary);
                }
                // Store the new photo
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $primary_path = $newImage->storeAs('tourist_grooup-images', $newName, 'public');
            }
            $data['image'] = $primary_path;
            $tourist_grooup->update($data);
            return redirect()->route('dashboard.tourist_grooups.index')
            ->with('success', 'Macth Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.tourist_grooups.index')
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
            $tourist_grooup = TouristGroup::findOrFail($id);
            if ($tourist_grooup->image != null) {
                Storage::disk('public')->delete($tourist_grooup->image);
            }
            $tourist_grooup->delete();
            return redirect()->route('dashboard.tourist_grooups.index')
            ->with('success', 'tourist_grooup Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.tourist_grooups.index')
            ->with('fail', 'Not Deleted,Please Try Again');
            throw $e;
        }
    }
}
