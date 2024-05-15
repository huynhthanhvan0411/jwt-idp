<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class PasswordResetController extends Controller
{
    //function to send mail and inside of that there are another functionm
    public function sendmail(Request $request){
        if(!$this->validateEmail($request->email)){ //validate to fail send mail or true 
            return response()->json(['message' => 'Email not found'], Response::HTTP_NOT_FOUND);
        }
        $this->send($request->email);// function to send mail 
        return response()->json(['message' => 'We have e-mailed your password reset link!'], Response::HTTP_OK);
    }
    
}
