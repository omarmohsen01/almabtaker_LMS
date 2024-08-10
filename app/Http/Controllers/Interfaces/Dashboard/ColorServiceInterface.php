<?php

namespace App\Http\Controllers\Interfaces\Dashboard;

interface ColorServiceInterface
{
    public function colorIndex();

    public function colorStore($data);

    public function colorUpdate($data, $id);

    public function colorDestroy($id);

}