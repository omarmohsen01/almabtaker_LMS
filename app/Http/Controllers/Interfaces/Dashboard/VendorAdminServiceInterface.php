<?php

namespace App\Http\Controllers\Interfaces\Dashboard;

interface VendorAdminServiceInterface
{
    public function vendorAdminIndex($data);

    public function vendorAdminStore($data);

    public function vendorAdminUpdate($data, $id);

    public function vendorAdminDestroy($id);

    public function changeVendorAdminStatus($id);
}