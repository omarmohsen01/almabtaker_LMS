<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TouristGroupBooking extends Model
{
    use HasFactory;

    protected $table = 'tourist_group_booking';

    protected $fillable = ['tourist_group_id', 'user_id', 'quantity', 'total_price', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tourist_group()
    {
        return $this->belongsTo(TouristGroup::class);
    }
}