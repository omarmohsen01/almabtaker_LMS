<?php

namespace App\Http\Controllers\Interfaces\Dashboard;

interface UserServiceInterface
{
    public function userIndex($data);

    public function userStore($data);

    public function userUpdate($data, $id);

    public function userDestroy($id);

    public function changeUserStatus($id);
}