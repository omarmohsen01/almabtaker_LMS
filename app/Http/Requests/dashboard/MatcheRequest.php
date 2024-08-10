<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class MatcheRequest extends FormRequest
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
            'first_team_en'=>'sometimes|string',
            'first_team_ar'=>'sometimes|string',
            'seconed_team_en'=>'sometimes|string',
            'seconed_team_ar'=>'sometimes|string',
            'day'=>'sometimes',
            'time'=>'sometimes',
            'stadium_en'=>'sometimes|string',
            'stadium_ar'=>'sometimes|string',
            'compitation_en'=>'sometimes|string',
            'compitation_ar'=>'sometimes|string',
            'descrption_en' => 'sometimes|string',
            'descrption_ar'=>'sometimes|string',
            'quantity'=>'integer',
            'price'=>'numeric|sometimes',
            'tickt_image'=>'sometimes|string',
        ];
    }
}
