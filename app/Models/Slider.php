<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    public $table='sliders';

    public $fillable=['title_en','title_ar','description_en','description_ar','image'];
}