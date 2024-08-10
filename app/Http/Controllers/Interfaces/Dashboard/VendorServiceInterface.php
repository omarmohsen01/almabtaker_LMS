<?php

namespace App\Http\Controllers\Interfaces\Dashboard;

interface VendorServiceInterface
{
    public function vendorIndex($data);

    public function vendorStore($data);

    public function vendorUpdate($data, $id);

    public function vendorDestroy($id);

    public function changeVendorStatus($id);
}
