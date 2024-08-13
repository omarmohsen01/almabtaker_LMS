<?php

namespace App\Http\Resources;

use App\Models\PartyBooking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class PartyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = App::getLocale();

        $party_booking = Auth::check()
        ? PartyBooking::where('party_id', $this->id)
                      ->where('user_id', Auth::user()->id)
                      ->first()
        : null;

        $status = $party_booking ? $party_booking->status : 'none';

        return [
            'id'=>$this->id,
            'title'=>$locale === 'ar' ? $this->title_ar : $this->title_en,
            'description'=>$locale === 'ar' ? $this->description_ar : $this->description_en,
            'day'=>$this->day,
            'time'=>$this->time,
            'quantity'=>$this->quantity,
            'price'=>number_format($this->price,2),
            'address'=>$this->address,
            'seat_image'=>url(asset('storage/' . $this->seat_image)),
            'ticket_image'=>url(asset('storage/' . $this->ticket_image)),
            'status'=>$status
        ];
    }
}
