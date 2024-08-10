<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matche extends Model
{
    use HasFactory;

    public $table='matches';

    public $fillable=[
                    'first_team_en','first_team_ar','seconed_team_en','seconed_team_ar',
                    'day','time','stadium_en','stadium_ar','compitation_en',
                    'compitation_ar','description_ar','description_en',
                    'quantity','price','seat_image','ticket_image'
                ];
}
