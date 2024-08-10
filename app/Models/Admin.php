<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;

class Admin extends Authenticatable
{
    use HasFactory;
    protected $table = 'admins';

    protected $fillable = [
        'name', 'email', 'phone', 'image', 'birth_date',
        'address', 'gender', 'status', 'password', 'facebook',
        'twitter', 'website', 'instagram','vendor_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function scopeFilter(Builder $builder, $filter)
    {
        $defaultOptions = [
            'first_name' => null,
            'last_name' => null,
            'email' => null,
            'phone' => null,
            'type'=> null,
            'status' => null,
        ];

        $options = array_merge($defaultOptions, $filter);

        $builder->when($options['first_name'], function ($query, $first_name) {
            return $query->where('first_name', $first_name);
        });
        $builder->when($options['last_name'], function ($query, $last_name) {
            return $query->where('last_name', $last_name);
        });
        $builder->when($options['email'], function ($query, $email) {
            return $query->where('email', $email);
        });
        $builder->when($options['phone'], function ($query, $phone) {
            return $query->where('phone', $phone);
        });
        $builder->when($options['type'], function ($query, $type) {
            return $query->where('type', $type);
        });
        $builder->when($options['status'], function ($query, $status) {
            return $query->where('status', $status);
        });

        return $builder;
    }
}
