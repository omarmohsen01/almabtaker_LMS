<?php

namespace App\Http\Controllers\Services\Dashboard;

use App\Http\Controllers\Interfaces\Dashboard\PromoCodeServiceInterface;
use App\Models\PromoCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PromoCodeService implements PromoCodeServiceInterface
{
    public function promoCodeIndex($data)
    {
        return PromoCode::filter($data->query())->paginate(10);
    }

    public function promoCodeStore($data)
    {
        $data['admin_id']=Auth::guard('admin')->user()->id;
        $promo_code = PromoCode::create($data->all());
        return $promo_code;
    }
    public function promoCodeUpdate($data, $id)
    {
        $promo_code = PromoCode::findOrFail($id);
        $promo_code->update($data->all());
    }
    public function promoCodeDestroy($id)
    {
        $promo_code = PromoCode::findOrFail($id);
        $promo_code->delete();
    }

}