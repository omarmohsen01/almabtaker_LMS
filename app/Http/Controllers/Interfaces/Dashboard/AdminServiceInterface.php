<?php

namespace App\Http\Controllers\Interfaces\Dashboard;

interface AdminServiceInterface
{
    public function adminIndex($data);

    public function adminStore($data);

    public function adminUpdate($data, $id);

    public function adminDestroy($id);

    public function changeAdminStatus($id);
}