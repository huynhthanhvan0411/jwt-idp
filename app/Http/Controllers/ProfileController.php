<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $users = User::all();
            return response()->json([
                'success' => true,
                'users' => $users],Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // dd($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ProfileRequest $request)
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',Response::HTTP_CREATED]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        try {
            return response()->json([
                'success' => true,
                'user' => $user],Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileRequest $request, $id)
    {
        DB::beginTransaction();
     try {
        $user = User::find($id);
        $user->update([
            'user' => $request->user,
            'name' => $request->name,
            'email' => $request->email
        ]);
        DB::commit();
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',Response::HTTP_OK]);
     } catch (\Exception $e) {
         DB::rollBack();
         Log::error($e->getMessage());
         return response()->json([
             'success' => false,
             'message' => $e->getMessage()
         ],Response::HTTP_INTERNAL_SERVER_ERROR);
     }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',Response::HTTP_OK]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
