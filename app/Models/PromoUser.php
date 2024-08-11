<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoUser extends Model
{
    use HasFactory;

    protected $table='promo_user';

    protected $fillable=['promo_code_id',
    'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promo_code()
    {
        return $this->belongsTo(PromoCode::class);
    }
}
