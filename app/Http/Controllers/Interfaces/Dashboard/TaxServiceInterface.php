<?php

namespace App\Http\Controllers\Interfaces\Dashboard;

interface TaxServiceInterface
{
    public function taxIndex($data);

    public function taxStore($data);

    public function taxUpdate($data, $id);

    public function taxDestroy($id);

    public function changeTaxStatus($id);
}