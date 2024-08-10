<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ForgetChangePasswordRequest;
use App\Http\Requests\Api\OtpForgetPasswordRequest;
use App\Mail\VerificationCodeMail;
use App\Models\PasswordReset;
use App\Models\User;
use App\Traits\ApiTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgetPasswordController extends Controller
{
    use ApiTrait;

    public function store(Request $request){
        $lang = ($request->hasHeader('lang')) ? $request->header('lang') : 'en';
        App::setLocale($lang);
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|max:255|exists:users,email',
            ]
        );
        if ($validator->fails()) {
            return $this->validationResponse($validator->errors(), null, $request->getLocale());
        }
        $email = $request->email;
        $user = User::active()->firstWhere('email',$email);

        $userId = $user->id;
        $otp = User::generatePasswordResetToken();
        $end_at = Carbon::now()->addMinutes(PasswordReset::$endAfterMinutes);

        try {

            DB::beginTransaction();

            $userToken = PasswordReset::forget()->where('user_id',$userId)->first();
            if ($userToken){

                $userToken->update([
                    'otp'=> $otp,
                    'email'=>$email,
                    'status' => 0,
                    'end_at' => $end_at,
                ]);

            }else{
                PasswordReset::create([
                    'email'      => $email,
                    'otp'        => $otp,
                    'user_id'    => $userId,
                    'type' =>    'forget',
                    'end_at' => $end_at,
                ]);
            }

            Mail::to($email)->send(new VerificationCodeMail($user->name, $otp));

            DB::commit();
            return $this->successResponse(null, config('responses.success OTP')[$lang], $request->getLocale());

        }catch (\Exception $exception){
            DB::rollback();
            return $this->errorResponse(config('responses.error OTP')[$lang], 404, $request->getLocale());
        }

    }

    public function checkOtp(Request $request){
        $lang = ($request->hasHeader('lang')) ? $request->header('lang') : 'en';
        App::setLocale($lang);
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|exists:users,email',
                'otp' => 'required|integer',
            ]
        );
        if ($validator->fails()) {
            return $this->validationResponse($validator->errors(), null, $request->getLocale());
        }
        $email = $request->email;
        $otp = $request->otp;
        $user = User::active()->firstWhere('email',$email);
        if (!$user){
            return $this->errorResponse(config('responses.error OTP')[$lang], 404, $request->getLocale());
        }
        $userId = $user->id;
        $userToken = PasswordReset::forget()->pending()->where('user_id',$userId)->latest()->first();
        if (!$userToken or $userToken->otp != $otp){
            return $this->errorResponse(config('responses.invalied otp')[$lang], 404, $request->getLocale());
        }
        if (Carbon::now()->gt($userToken->end_at)){
            return $this->errorResponse(config('responses.expired otp')[$lang], 404, $request->getLocale());
        }
        $userToken->update(['status'=>1]);
        return $this->successResponse(null, config('responses.activated otp')[$lang], $request->getLocale());

    }

    public function changePassword(Request $request){
        $lang = ($request->hasHeader('lang')) ? $request->header('lang') : 'en';
        App::setLocale($lang);
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'otp' => 'required|integer',
                'password' => 'required|min:6|confirmed',
            ]
        );
        if ($validator->fails()) {
            return $this->validationResponse($validator->errors(), null, $request->getLocale());
        }
        $email = $request->email;
        $otp = $request->otp;
        $password = bcrypt($request->password);
        $user = User::active()->firstWhere('email',$email);

        $userId = $user->id;
        $userToken = PasswordReset::forget()->verified()->where('user_id',$userId)->latest()->first();
        if (!$userToken or $userToken->otp != $otp){
            return $this->errorResponse(config('responses.invalied otp')[$lang], 404, $request->getLocale());
        }

        if (Carbon::now()->gt($userToken->end_at)){
            return $this->errorResponse(config('responses.expired otp')[$lang], 404, $request->getLocale());
        }

        $user->update(['password'=>$password]);
        $userToken->update(['status'=>2]);
        return $this->successResponse(null, config('responses.changed password')[$lang], $request->getLocale());
    }
}