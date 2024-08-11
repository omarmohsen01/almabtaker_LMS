<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchBooking extends Model
{
    use HasFactory;

    protected $table='matches_booking';

    protected $fillable=['matche_id','user_id','quantity','total_price','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matche()
    {
        return $this->belongsTo(Matche::class);
    }
}
