<?php

namespace App\Http\Controllers\Interfaces\Dashboard;

interface InputServiceInterface
{
    public function inputIndex();

    public function inputStore($data);

    public function inputUpdate($data, $id);

    public function inputDestroy($id);

}