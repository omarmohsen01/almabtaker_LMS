<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table='countries';

    protected $fillable=[
        'title_en', 'title_ar', 'image', 'code',
        'status', 'shortcut'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }
}