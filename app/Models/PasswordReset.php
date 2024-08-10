<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    public static $endAfterMinutes = 20; // OTP it will end after 20 minutes
    public static $seconds = 10; // Can Resend email after 60 Seconds from last resend
    public $table= 'password_resets';
    protected $fillable = ['email', 'otp', 'user_id', 'status', 'type', 'end_at'];

    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    public function scopeForget($query)
    {
        return $query->where('type', 'forget');
    }

    public function scopeRegister($query)
    {
        return $query->where('type', 'register');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 1);
    }

}