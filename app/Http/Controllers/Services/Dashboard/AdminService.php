<?php

namespace App\Http\Controllers\Services\Dashboard;

use App\Http\Controllers\Interfaces\Dashboard\AdminServiceInterface;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminService implements AdminServiceInterface
{
    public function adminIndex($data)
    {
        return Admin::filter($data->query())->paginate(10);
    }

    public function adminStore($data)
    {
        $data['password']= Hash::make($data->password);
        $data['image'] = upload_image($data, 'admin');
        $admin = Admin::create($data->except(['confirm_password','primary_image']));
        return $admin;
    }
    public function adminUpdate($data, $id)
    {
        $admin = Admin::findOrFail($id);
        if ($data->hasFile('primary_image')) {
            $path=update_image($admin,$data, 'admin');
            $data['image'] = $path;
        }
        $admin->update($data->all());
    }
    public function adminDestroy($id)
    {
        $admin = Admin::findOrFail($id);
        if ($admin->image != null) {
            Storage::disk('public')->delete($admin->image);
        }
        $admin->delete();
    }
    public function changeAdminStatus($id)
    {
        // $admin = Admin::findOrFail($id);
        // if ($admin->status == 'ACTIVE') {
        //     $admin->status = 'INACTIVE';
        //     $admin->save();
        //     return 'INACTIVATED';
        // } elseif ($admin->status == 'INACTIVE') {
        //     $admin->status = 'ACTIVE';
        //     $admin->save();
        //     return 'ACTIVEATED';
        // }
    }
}
