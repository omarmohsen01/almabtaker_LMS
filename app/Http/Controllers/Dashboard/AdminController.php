<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Interfaces\Dashboard\AdminServiceInterface;
use App\Http\Requests\dashboard\AdminRequest;
use App\Models\Admin;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class AdminController extends Controller
{
    protected $adminService;
    public function __construct(AdminServiceInterface $adminService)
    {
        $this->adminService=$adminService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $admins=$this->adminService->adminIndex($request);
        return view('dashboard.admins.index',compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('dashboard.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminRequest $request)
    {
        try {
            $this->adminService->adminStore($request);
            return redirect()->route('dashboard.admins.index')
                ->with('success', 'Admin Created Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.admins.index')
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
        $admin=Admin::findOrFail($id);
        return view('dashboard.admins.edit',compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminRequest $request, string $id)
    {
        // dd($request);
        try {
            $this->adminService->adminUpdate($request,$id);
            return redirect()->back()
                ->with('success', 'Admin Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()
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
            $this->adminService->adminDestroy($id);
            return redirect()->route('dashboard.admins.index')
                ->with('success', 'Admin Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.admins.index')
                ->with('fail', 'Not Deleted,Please Try Again');
            throw $e;
        }
    }
}
