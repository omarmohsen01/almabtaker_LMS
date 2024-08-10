<?php

namespace App\Http\Controllers\Interfaces\Dashboard;

interface PromoCodeServiceInterface
{
    public function promoCodeIndex($data);

    public function promoCodeStore($data);

    public function promoCodeUpdate($data, $id);

    public function promoCodeDestroy($id);

}