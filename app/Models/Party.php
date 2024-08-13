<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;
    protected $table='parties';

    protected $fillable=['title_en','title_ar','description_en',
        'description_ar','day','time','address','quantity','price',
        'seat_image','ticket_image'];

    }
