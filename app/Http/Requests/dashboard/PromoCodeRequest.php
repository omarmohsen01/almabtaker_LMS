<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromoCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $promo_code_id = $this->route('promo_code');
        return [
            'title' => [
                Rule::unique('promo_codes')->ignore($promo_code_id),
                'sometimes', 'string', 'max:255', 'min:3'
            ],
            'value'=>'sometimes|numeric',
            'from'=>'sometimes|after_or_equal:today',
            'to'=> 'sometimes|after_or_equal:from',
            'maximum_times_of_use'=>'sometimes|integer',
            'dedicated_to'=>'sometimes',
            'type'=>'sometimes',
        ];
    }
}