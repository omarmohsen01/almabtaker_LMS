<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    public $table = 'activities';
    public $fillable = [
        'title_en',
        'title_ar',
        'description_ar',
        'description_en',
        'quantity',
        'price',
        'from',
        'to',
        'image',
        'country_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}