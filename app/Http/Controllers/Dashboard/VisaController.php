<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\VisaRequest;
use App\Models\Country;
use App\Models\Visa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VisaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $visas = Visa::paginate(10);
        return view('dashboard.visas.index', compact('visas'));
    }

    public function create()
    {
        $countries=Country::all();
        return view('dashboard.visas.create',compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VisaRequest $request)
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
            Visa::create($data);
            return redirect()->route('dashboard.visas.index')
            ->with('success', 'Visa Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.visas.index')
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
        $visa = Visa::findOrFail($id);
        $countries=Country::get();
        return view('dashboard.visas.edit', compact('visa', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VisaRequest $request, string $id)
    {
        try {
            $visa = Visa::findOrFail($id);
            $data = $request->except('primary_image');
            $oldPhotoPath_primary = $visa->primary_image;
            $primary_path = $oldPhotoPath_primary;
            if ($request->hasFile('primary_image')) {
                // Delete the old photo
                if ($oldPhotoPath_primary) {
                    Storage::disk('public')->delete($oldPhotoPath_primary);
                }
                // Store the new photo
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $primary_path = $newImage->storeAs('visa-images', $newName, 'public');
            }
            $data['image'] = $primary_path;
            $visa->update($data);
            return redirect()->route('dashboard.visas.index')
            ->with('success', 'Macth Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.visas.index')
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
            $visa = Visa::findOrFail($id);
            if ($visa->image != null) {
                Storage::disk('public')->delete($visa->image);
            }
            $visa->delete();
            return redirect()->route('dashboard.visas.index')
            ->with('success', 'visa Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.visas.index')
            ->with('fail', 'Not Deleted,Please Try Again');
            throw $e;
        }
    }
}
