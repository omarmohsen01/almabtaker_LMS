<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
    use HasFactory;
    public $table = 'visa';

    public $fillable = [
        'title_en',
        'title_ar',
        'duration',
        'description_ar',
        'description_en',
        'quantity',
        'price',
        'image',
        'visa_type'
        ,'country_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}