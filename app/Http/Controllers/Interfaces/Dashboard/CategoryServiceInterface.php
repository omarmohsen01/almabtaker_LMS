<?php

namespace App\Http\Controllers\Interfaces\Dashboard;

interface CategoryServiceInterface
{
    public function categoryIndex($data);

    public function categoryStore($data);

    public function categoryUpdate($data, $id);

    public function categoryDestroy($id);

    public function categoryRestore($id);

    public function categoryForceDelete($id);
}