<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\PromoCode;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use ApiTrait;
    public function get_countries(Request $request)
    {
        $lang = app()->getLocale();
        $Countries = Country::active()->select('id', "title_$lang As title", 'shortcut', 'image')->get();

        if (!$Countries) {
            return $this->errorResponse(config('responses.error')[$lang], 404, $request->getLocale());
        }
        return $this->successResponse($Countries, config('responses.success')[$lang], $request->getLocale());
    }

    public function get_categories(Request $request)
    {
        $lang = app()->getLocale();
        $categories = Category::select('id', "title_$lang As title",  'image')->get();

        if (!$categories) {
            return $this->errorResponse(config('responses.error')[$lang], 404, $request->getLocale());
        }
        return $this->successResponse($categories, config('responses.success')[$lang], $request->getLocale());
    }

    public function get_promo_code(Request $request)
    {
        $lang = app()->getLocale();
        $promos = PromoCode::select('id', "code",'discount')->get();

        if (!$promos) {
            return $this->errorResponse(config('responses.error')[$lang], 404, $request->getLocale());
        }
        return $this->successResponse($promos, config('responses.success')[$lang], $request->getLocale());
    }
}
