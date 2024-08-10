<?php

namespace App\Http\Controllers\Interfaces\Dashboard;

interface ProductServiceInterface
{
    public function productIndex($data);

    public function productStore($data);

    public function productUpdate($data, $id);

    public function productDestroy($id);

    public function productRestore($id);

    public function productForceDelete($id);

}