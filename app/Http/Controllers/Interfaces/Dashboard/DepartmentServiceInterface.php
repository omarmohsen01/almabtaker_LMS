<?php

namespace App\Http\Controllers\Interfaces\Dashboard;

interface DepartmentServiceInterface
{
    public function departmentIndex($data);

    public function departmentStore($data);

    public function departmentUpdate($data, $id);

    public function departmentDestroy($id);

    public function departmentRestore($id);

    public function departmentForceDelete($id);

}