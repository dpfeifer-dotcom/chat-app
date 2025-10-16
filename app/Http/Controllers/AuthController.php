<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Responses\BaseResponse;
use App\Models\User;
use App\Notifications\ApiVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class AuthController
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        if (config('mail.mailer')) {
            $user->notify(new ApiVerifyEmail($verifyUrl));
        }

        return BaseResponse::success(['url' => $verifyUrl]);
    }

    public function login(LoginRequest $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return BaseResponse::error('Invalid credentials', 401);
        }

        $user = Auth::user();

        /** @var \App\Models\User $user */
        $token = $user->createToken('api')->plainTextToken;

        return BaseResponse::success(['token' => $token]);
    }

    /**
     * @authenticated
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return BaseResponse::success();
    }

    public function verify(Request $request, $id, $hash)
    {
        if (! $request->hasValidSignature()) {
            return BaseResponse::error('Invalid or expired link', 403);
        }

        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->email))) {
            return BaseResponse::error('Invalid hash', 403);

        }

        if ($user->email_verified_at) {
            return BaseResponse::error('Email already verified', 403);
        }

        $user->email_verified_at = now();
        $user->save();

        return BaseResponse::success();
    }
}
