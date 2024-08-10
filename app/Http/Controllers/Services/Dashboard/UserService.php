<?php

namespace App\Http\Controllers\Services\Dashboard;

use App\Http\Controllers\Interfaces\Dashboard\UserServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService implements UserServiceInterface
{
    public function userIndex($data)
    {
        return User::filter($data->query())->paginate(10);
    }

    public function userStore($data)
    {
        $data['image'] = upload_image($data, 'user');
        $data['password'] = Hash::make($data->password);
        $user = User::create($data->except(['confirm_password','primary_image']));
        return $user;
    }

    public function userUpdate($data, $id)
    {
        $user = User::findOrFail($id);
        if ($data->hasFile('primary_image')) {
            $path = update_image($user, $data,'user');
            $data['image'] = $path;
        }
        $user->update($data->all());
    }
    public function userDestroy($id)
    {
        $user = User::findOrFail($id);
        if($user->image!=null){
            Storage::disk('public')->delete($user->image);
        }
        $user->delete();
    }
    public function changeUserStatus($id)
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