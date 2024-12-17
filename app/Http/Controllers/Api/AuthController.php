<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Api\Post;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Password;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

        return $this->success(
            [
                'user' => new UserResource($user),
                'token' => $user->createToken('Registered User')->plainTextToken
            ],
            'User created successfully!',
            201
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        if (!Auth::attempt([
            'email' => $validatedData['email'],
            'password' => $validatedData['password']
        ])) {
            return $this->error(null, 'Invalid email or password', 401);
        }

        $user = Auth::user();

        return $this->success(
            [
                'user' => new UserResource($user),
                'token' => $user->createToken('Login User')->plainTextToken
            ],
            'Login successful!'
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return $this->success('', 'Logged out successfully!');
    }

    public function users(): JsonResponse
    {
        $users = User::all();

        if (!$users) {
            return $this->success(
                [
                    'users' => []
                ],
                "Users Not Found!"
            );
        }

        return $this->success(
            [
                'users' => UserResource::collection($users),
            ],
            "Users Found!"
        );
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            return $this->error(null, "Failed to send reset link.", 500);
        }

        return $this->success('', "Password reset link sent to your email.");
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            return $this->error(null, "Failed to reset password.", 500);
        }
        return $this->success('', 'Password has been reset successfully.');
    }

    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validatedData['current_password'], Auth::user()->password)) {
            return $this->error(null, 'Current password is incorrect.');
        }

        Auth::user()->update([
            'password' => Hash::make($validatedData['new_password']),
        ]);

        return $this->success('', 'Password changed successfully.');
    }
}
