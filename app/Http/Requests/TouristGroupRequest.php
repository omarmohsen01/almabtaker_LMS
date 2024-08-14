<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TouristGroupRequest extends FormRequest
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
        return [
            'title_en'=>'sometimes|string|min:3|max:255',
            'title_ar'=>'sometimes|string|min:3|max:255',
            'description_en'=>'sometimes|string',
            'description_ar' => 'sometimes|string',
            'price'=>'sometimes|numeric',
            'from'=>'string|sometimes',
            'to'=>'string|sometimes',
            'quantity' => 'string|sometimes',
            'country_id'=>'sometimes|integer|exists:countries,id',
            'image'=>'sometimes'
        ];
    }
}
