<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\VisaResource;
use App\Models\PromoCode;
use App\Models\PromoUser;
use App\Models\Visa;
use App\Models\VisaBooking;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VisaController extends Controller
{
    use ApiTrait;
    public function index(Request $request)
    {
        if(isset($request->country_id)){

            $visas=Visa::where('quantity','>','0')->where('country_id',$request->country_id)->get();
        }else{
            $visas=Visa::where('quantity','>','0')->get();
        }
        if (!$visas) {
            return $this->errorResponse(
                null,
                null,
                $request->getLocale()
            );
        }
        return $this->successResponse(
            VisaResource::collection($visas)->response()->getData(),
            null,
            $request->getLocale()
        );
    }

    public function show(Request $request , $id)
    {
        $visas=Visa::findOrFail($id);
        if (!$visas) {
            return $this->errorResponse(
                null,
                null,
                $request->getLocale()
            );
        }
        return $this->successResponse(
            new VisaResource($visas),
            null,
            $request->getLocale()
        );
    }

    public function store(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'quantity' => 'integer|min:1',
                'visa_id' => 'required|integer|exists:visas,id',
                'promo_code'=>'string'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse([], $validator->errors()->first(), $request->getLocale());
            }
            $visa=Visa::findOrFail($request->visa_id);
            if($request->quantity > $visa->quantity){
                return $this->errorResponse(
                    'quantity larger than limitation',
                    null,
                    $request->getLocale()
                );
            }

            if($visa){
                $visa_booking=VisaBooking::where('visa_id',$visa->id)->where('user_id',auth()->user()->id)->first();
                if($visa_booking){
                    return $this->errorResponse(
                        'you have Booked this visa before',
                        null,
                        $request->getLocale()
                    );
                }

                if(isset($request->promo_code)){
                    $promoCode = PromoCode::where('code', $request->promo_code)->first();
                    $promo_user=PromoUser::where('promo_code_id',$promoCode->id)->where('user_id',auth()->user()->id)->first();
                    if($promo_user){
                        return $this->errorResponse(
                            'you have use this promo before',
                            null,
                            $request->getLocale()
                        );
                    }
                    $totalPrice = ($request->quantity*$visa->price) * (1 - ($promoCode->discount / 100));
                    PromoUser::create(['promo_code_id'=>$promoCode->id,'user_id'=>auth()->user()->id]);
                }else{
                    $totalPrice=($request->quantity*$visa->price);
                }
                VisaBooking::create([
                    'user_id'=>auth()->user()->id,
                    'visa_id'=>$visa->id,
                    'quantity'=>$request->quantity,
                    'total_price'=>$totalPrice
                ]);
                return $this->successResponse(
                    [],
                    null,
                    $request->getLocale()
                );
            }else{
                return $this->errorResponse(
                    'Product not found,or quantity larger than limitation',
                    null,
                    $request->getLocale()
                );
            }
        } catch (\Exception $e) {
            Log::error('Error adding to cart: ' . $e->getMessage());

            return $this->errorResponse(
                null,
                null,
                $request->getLocale()
            );
            throw $e;
        }
    }
}
