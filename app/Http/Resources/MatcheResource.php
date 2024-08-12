<?php

namespace App\Http\Resources;

use App\Models\MatchBooking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class MatcheResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = App::getLocale();

        $matche_booking = Auth::check()
        ? MatchBooking::where('matche_id', $this->id)
                      ->where('user_id', Auth::user()->id)
                      ->first()
        : null;

        $status = $matche_booking ? $matche_booking->status : 'none';

        return [
            'id'=>$this->id,
            'first_team'=>$locale === 'ar' ? $this->first_team_ar : $this->first_team_en,
            'seconed_team'=>$locale === 'ar' ? $this->seconed_team_ar : $this->seconed_team_en,
            'day'=>$this->day,
            'time'=>$this->time,
            'stadium'=>$locale === 'ar' ? $this->stadium_ar : $this->stadium_en,
            'compitation'=>$locale === 'ar' ? $this->compitation_ar : $this->compitation_en,
            'description'=>$locale === 'ar' ? $this->description_ar : $this->description_en,
            'quantity'=>$this->quantity,
            'price'=>number_format($this->price,2),
            'seat_image'=>url(asset('storage/' . $this->seat_image)),
            'ticket_image'=>url(asset('storage/' . $this->ticket_image)),
            'status'=>$status
        ];
    }
}
