<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationLinkMail;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use App\Models\User;
use App\Traits\ApiTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AccessTokenController extends Controller
{
    use ApiTrait;
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|max:255',
                'password' => 'required|string|max:255',
                'device_name' => 'string|max:255'
            ]
        );
        if ($validator->fails()) {
            return $this->validationResponse([], $validator->errors()->first(), $request->getLocale());
        }
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password) && $user->email_verified_at != null) {
            $device_name = $request->post('device_name', $request->userAgent());
            $token = $user->createToken($device_name);
            $user['token'] = $token->plainTextToken;
            return $this->successResponse($user, null, $request->getLocale());
        }
        if(!$user){
            return $this->errorResponse('Invalid Credentials Or Not Verified', 404);
        }elseif($user->email_verified_at != null){
            return $this->errorResponse('Not Verified', 401);
        }
    }

    public function signup(Request $request)
    {
        $lang = app()->getLocale();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            "phone" => 'required|min:10|numeric|unique:users',
            "gender" => "sometimes",
            'password' => 'required',
            'confirm_password' => 'sometimes|same:password',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse([], $validator->errors()->first(), $request->getLocale());
        }

        $input = $request->all();

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        // $verificationLink = route('verify-email', ['user' => $user->id, 'token' => sha1($user->email)]);
        // Mail::to($user->email)->send(new VerificationLinkMail($user->name, $verificationLink));
        // return $this->successResponse([], config('responses.verification')[$lang], $request->getLocale());
        return $this->successResponse($user, config('responses.verification success')[$lang], $request->getLocale());

    }

    public function verifyEmail($userId, $token, Request $request)
    {
        $lang = app()->getLocale();
        $user = User::find($userId);

        if (!$user || sha1($user->email) !== $token) {
            return $this->errorResponse(config('responses.invalid verification')[$lang], 404, $request->getLocale());
        }

        if ($user->email_verified_at) {
            return $this->errorResponse(config('responses.already verification')[$lang], 404, $request->getLocale());
        }

        $user->email_verified_at = Carbon::now();
        $user->save();
        return $this->successResponse($user, config('responses.verification success')[$lang], $request->getLocale());
    }

    public function my_account(Request $request)
    {
        $lang = app()->getLocale();
        $user = Auth::user();
        // $user->addresses;
        // $user->orders;
        // $user->wishlist;
        // return response($user);
        return $this->successResponse($user, config('responses.success')[$lang], $request->getLocale());
    }

    public function logout(Request $request,$token = null)
    {
        $lang = app()->getLocale();
        $user = Auth::guard('sanctum')->user();
        if (null === $token) {
            $user->currentAccessToken()->delete();
            // return;
            return $this->successResponse([], config('responses.logout')[$lang], $request->getLocale());
        }
        $personalAccessToken = PersonalAccessToken::findToken($token);
        if ($user->id == $personalAccessToken->tokenable_id && get_class($user) == $personalAccessToken->tokenable_type) {
            $personalAccessToken->delete();
        }
        return $this->successResponse([], config('responses.logout')[$lang], $request->getLocale());
    }
}
