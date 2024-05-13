<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{ 
    //forgot and change password 
    public function createResetPasswordToken(ResetPasswordRequest $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if ($user) {
            $token = Str::random(64);
            DB::table('password_resets')->insert([
                'email' => $user->email,
                'token' => $token
            ]);
            return response()->json([
                'success' => true,
                'token' => $token
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Email not found'
        ]);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        DB::beginTransaction(); 
        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->save();

                    event(new PasswordReset($user));
                }
            );
            DB::commit();
            return $status == Password::PASSWORD_RESET
                ? response()->json(['message' => 'Password reset successfully'])
                : response()->json(['message' => 'Password reset failed'], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
