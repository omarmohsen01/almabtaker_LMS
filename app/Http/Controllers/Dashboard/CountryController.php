<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries=Country::paginate(10);
        return view('dashboard.country.index',compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.country.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['title_en'=>'required|string',
                    'title_ar'=>'required|string','primary_image'=>'required','shortcut'=>'required|string']);
        try {
            $data=$request->except('primary_image');
            $path = null;
            if ($request->hasFile('primary_image')) {
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs('country-images', $newName, 'public');
            }
            $data['image'] = $path;

            Country::create($data);
            return redirect()->route('dashboard.countries.index')
                ->with('success', 'country Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.countries.index')
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
        $country=Country::findOrFail($id);
        if(!$country){
            return redirect()->route('dashboard.countries.index')
                ->with('fail', 'Something Went Wrong,Please Try Again');
        }
        return view('dashboard.country.edit',compact('country'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['title_en'=>'sometimes|string',
                    'title_ar'=>'sometimes|string','primary_image'=>'sometimes','shortcut'=>'sometimes|string']);
        try {
            $country=Country::findOrFail($id);
            $data=$request->except('primary_image');
            $oldPhotoPath_primary = $country->primary_image;
            $primary_path = $oldPhotoPath_primary;
            if ($request->hasFile('primary_image')) {
                // Delete the old photo
                if ($oldPhotoPath_primary) {
                    Storage::disk('public')->delete($oldPhotoPath_primary);
                }
                // Store the new photo
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $primary_path = $newImage->storeAs('country-images', $newName, 'public');
            }
            $data['image'] = $primary_path;
            $country->update($data);
            return redirect()->route('dashboard.countries.index')
                ->with('success', 'Macth Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.countries.index')
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
            $country=Country::findOrFail($id);
            if($country->image!=null){
                Storage::disk('public')->delete($country->image);
            }
            $country->delete();
            return redirect()->route('dashboard.countries.index')
                ->with('success', 'matche Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.countries.index')
            ->with('fail', 'Not Deleted,Please Try Again');
            throw $e;
        }
    }
}
