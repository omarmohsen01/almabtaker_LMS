<?php

namespace App\Http\Controllers\Interfaces\Dashboard;

interface SizeServiceInterface
{
    public function sizeIndex();

    public function sizeStore($data);

    public function sizeUpdate($data, $id);

    public function sizeDestroy($id);

}