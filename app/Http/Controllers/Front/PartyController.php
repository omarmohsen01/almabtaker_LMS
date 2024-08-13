<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartyResource;
use App\Models\Party;
use App\Models\PartyBooking;
use App\Models\PromoCode;
use App\Models\PromoUser;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PartyController extends Controller
{
    use ApiTrait;
    public function index(Request $request)
    {
        $parties=Party::where('quantity','>','0')->get();
        if (!$parties) {
            return $this->errorResponse(
                null,
                null,
                $request->getLocale()
            );
        }
        return $this->successResponse(
            PartyResource::collection($parties)->response()->getData(),
            null,
            $request->getLocale()
        );
    }

    public function show(Request $request , $id)
    {
        $parties=Party::findOrFail($id);
        if (!$parties) {
            return $this->errorResponse(
                null,
                null,
                $request->getLocale()
            );
        }
        return $this->successResponse(
            new PartyResource($parties),
            null,
            $request->getLocale()
        );
    }

    public function store(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'quantity' => 'integer|min:1',
                'party_id' => 'required|integer|exists:parties,id',
                'promo_code'=>'string'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse([], $validator->errors()->first(), $request->getLocale());
            }
            $party=Party::findOrFail($request->party_id);
            if($request->quantity > $party->quantity){
                return $this->errorResponse(
                    'quantity larger than limitation',
                    null,
                    $request->getLocale()
                );
            }

            if($party){
                $party_booking=PartyBooking::where('party_id',$party->id)->where('user_id',auth()->user()->id)->first();
                if($party_booking){
                    return $this->errorResponse(
                        'you have Booked this party before',
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
                    $totalPrice = ($request->quantity*$party->price) * (1 - ($promoCode->discount / 100));
                    PromoUser::create(['promo_code_id'=>$promoCode->id,'user_id'=>auth()->user()->id]);
                }else{
                    $totalPrice=($request->quantity*$party->price);
                }
                PartyBooking::create([
                    'user_id'=>auth()->user()->id,
                    'party_id'=>$party->id,
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
