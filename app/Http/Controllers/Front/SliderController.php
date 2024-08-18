<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $sliders=Slider::get();
        return $this->successResponse(
            SliderResource::collection($sliders)->response()->getData(),
            null,
            $request->getLocale()
        );
    }
}