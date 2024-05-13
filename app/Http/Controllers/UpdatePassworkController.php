<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class UpdatePassworkController extends Controller
{
    // just onlye change password but your account 
    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|current_password:api',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        DB::beginTransaction();

        try {
            $user = $request->user();

            $user->password = Hash::make($request->password);

            $user->save();

            DB::commit();

            return response()->json(['message' => __('Password has been updated successfully.')]);
            } catch (\Exception $e) {
                DB::rollBack();

                Log::error($e->getMessage());

                return response()->json(['error' => __('Unable to update password')], 500);
        }
    }
}
