<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AuthRequest;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh', 'register']]);
    }
    // public function register(ProfileRequest $request){
    //     DB::beginTransaction();
    //     try {
    //         $users = new User();
    //         $users->name = $request->name;
    //         $users->email = $request->email;
    //         $users->password = Hash::make($request->password);
    //         DB::commit();
    //         $users->save();
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User created successfully',
    //             'user' => $users
    //         ], 201);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error($e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
    public function register(AuthRequest $request){

        DB::beginTransaction();
        try{
            $users = new User();
            $users->name = $request->name;
            $users->email = $request->email;
            $users->password = Hash::make($request->password);
            dd($users);
            $users->save();
            // event(new Registered($users));
            // $token = auth('api')->login($users);
            DB::commit();
            return response()->json(
            [
                    'success' => true,
                    'message' => 'User created successfully',
                    'user' => $users
                ],201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
