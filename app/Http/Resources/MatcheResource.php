<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

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
        return [
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
        ];
    }
}
