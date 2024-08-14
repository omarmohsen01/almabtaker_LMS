<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    public $table='events';
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