<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyBooking extends Model
{
    use HasFactory;

    protected $table='party_bookings';

    protected $fillable=['party_id','user_id','quantity','total_price','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }
}
