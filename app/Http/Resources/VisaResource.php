<?php

namespace App\Http\Resources;

use App\Models\VisaBooking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class VisaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = App::getLocale();

        $visa_booking = Auth::check()
        ? VisaBooking::where('visa_id', $this->id)
                      ->where('user_id', Auth::user()->id)
                      ->first()
        : null;

        $status = $visa_booking ? $visa_booking->status : 'none';

        return [
            'id'=>$this->id,
            'title'=>$locale === 'ar' ? $this->title_ar : $this->title_en,
            'description'=>$locale === 'ar' ? $this->description_ar : $this->description_en,
            'visa_type'=>$this->visa_type,
            'duration'=>$this->duration,
            'quantity'=>$this->quantity,
            'price'=>number_format($this->price,2),
            'country'=>$locale === 'ar' ? $this->country->title_ar:$this->country->title_en,
            'image'=>url(asset('storage/' . $this->image)),
            'status'=>$status
        ];
    }
}
