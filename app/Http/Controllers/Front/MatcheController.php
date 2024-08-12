<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\MatcheResource;
use App\Models\MatchBooking;
use App\Models\Matche;
use App\Models\PromoCode;
use App\Models\PromoUser;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MatcheController extends Controller
{
    use ApiTrait;
    public function index(Request $request)
    {
        $matches=Matche::where('quantity','>','0')->get();
        if (!$matches) {
            return $this->errorResponse(
                null,
                null,
                $request->getLocale()
            );
        }
        return $this->successResponse(
            MatcheResource::collection($matches)->response()->getData(),
            null,
            $request->getLocale()
        );
    }

    public function show(Request $request , $id)
    {
        $matche=Matche::findOrFail($id);
        if (!$matche) {
            return $this->errorResponse(
                null,
                null,
                $request->getLocale()
            );
        }
        return $this->successResponse(
            new MatcheResource($matche),
            null,
            $request->getLocale()
        );
    }

    public function store(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'quantity' => 'integer|min:1',
                'matche_id' => 'required|integer|exists:matches,id',
                'promo_code'=>'string'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse([], $validator->errors()->first(), $request->getLocale());
            }
            $matche=Matche::findOrFail($request->matche_id);
            if($request->quantity>$matche->quantity){
                return $this->errorResponse(
                    'quantity larger than limitation',
                    null,
                    $request->getLocale()
                );
            }

            if($matche){
                $matche_booking=MatchBooking::where('matche_id',$matche->id)->where('user_id',auth()->user()->id)->first();
                if($matche_booking){
                    return $this->errorResponse(
                        'you have Booked this matche before',
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
                    $totalPrice = ($request->quantity*$matche->price) * (1 - ($promoCode->discount / 100));
                    PromoUser::create(['promo_code_id'=>$promoCode->id,'user_id'=>auth()->user()->id]);
                }else{
                    $totalPrice=($request->quantity*$matche->price);
                }
                MatchBooking::create([
                    'user_id'=>auth()->user()->id,
                    'matche_id'=>$matche->id,
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
