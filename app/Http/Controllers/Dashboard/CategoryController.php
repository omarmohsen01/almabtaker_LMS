<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories=Category::paginate(10);
        return view('dashboard.category.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['title_en'=>'required|string',
                    'title_ar'=>'required|string','primary_image'=>'required']);
        try {
            $data=$request->except('primary_image');
            $path = null;
            if ($request->hasFile('primary_image')) {
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs('category-images', $newName, 'public');
            }
            $data['image'] = $path;

            Category::create($data);
            return redirect()->route('dashboard.categories.index')
                ->with('success', 'category Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.categories.index')
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
        $category=Category::findOrFail($id);
        if(!$category){
            return redirect()->route('dashboard.categories.index')
                ->with('fail', 'Something Went Wrong,Please Try Again');
        }
        return view('dashboard.category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['title_en'=>'sometimes|string',
                    'title_ar'=>'sometimes|string','primary_image'=>'sometimes']);
        try {
            $category=Category::findOrFail($id);
            $data=$request->except('primary_image');
            $oldPhotoPath_primary = $category->primary_image;
            $primary_path = $oldPhotoPath_primary;
            if ($request->hasFile('primary_image')) {
                // Delete the old photo
                if ($oldPhotoPath_primary) {
                    Storage::disk('public')->delete($oldPhotoPath_primary);
                }
                // Store the new photo
                $newImage = $request->file('primary_image');
                $newName = rand() . '.' . $newImage->getClientOriginalExtension();
                $primary_path = $newImage->storeAs('category-images', $newName, 'public');
            }
            $data['image'] = $primary_path;
            $category->update($data);
            return redirect()->route('dashboard.categories.index')
                ->with('success', 'Macth Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.categories.index')
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
            $category=Category::findOrFail($id);
            if($category->image!=null){
                Storage::disk('public')->delete($category->image);
            }
            $category->delete();
            return redirect()->route('dashboard.categories.index')
                ->with('success', 'matche Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.categories.index')
            ->with('fail', 'Not Deleted,Please Try Again');
            throw $e;
        }
    }
}
