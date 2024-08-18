<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders=Slider::paginate(10);
        return view('dashboard.sliders.index',compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required|string',
            'title_ar' => 'required|string',
            'primary_image' => 'required',
            'description_en' => 'required|string',
            'description_ar' => 'required|string'
        ]);
        try {
            $data = $request->except('primary_image');
            $path = null;
            if ($request->hasFile('primary_image')) {
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs('slider-images', $newName, 'public');
            }
            $data['image'] = $path;

            Slider::create($data);
            return redirect()->route('dashboard.sliders.index')
                ->with('success', 'slider Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.sliders.index')
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
        $slider = Slider::findOrFail($id);
        if (!$slider) {
            return redirect()->route('dashboard.sliders.index')
                ->with('fail', 'Something Went Wrong,Please Try Again');
        }
        return view('dashboard.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title_en' => 'sometimes|string',
            'title_ar' => 'sometimes|string',
            'primary_image' => 'sometimes',
            'description_en' => 'sometimes|string',
            'description_ar' => 'sometimes|string',

        ]);
        try {
            $slider = Slider::findOrFail($id);
            $data = $request->except('primary_image');
            $oldPhotoPath_primary = $slider->primary_image;
            $primary_path = $oldPhotoPath_primary;
            if ($request->hasFile('primary_image')) {
                // Delete the old photo
                if ($oldPhotoPath_primary) {
                    Storage::disk('public')->delete($oldPhotoPath_primary);
                }
                // Store the new photo
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $primary_path = $newImage->storeAs('slider-images', $newName, 'public');
            }
            $data['image'] = $primary_path;
            $slider->update($data);
            return redirect()->route('dashboard.sliders.index')
            ->with('success', 'Macth Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.sliders.index')
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
            $slider = Slider::findOrFail($id);
            if ($slider->image != null) {
                Storage::disk('public')->delete($slider->image);
            }
            $slider->delete();
            return redirect()->route('dashboard.sliders.index')
            ->with('success', 'matche Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.sliders.index')
            ->with('fail', 'Not Deleted,Please Try Again');
            throw $e;
        }
    }
}