<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class RegisterController extends \App\Http\Controllers\API\BaseController
{
    public function register( Request $request ){

        $validator = Validator::make( $request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password'=> 'required',
            'c_password'=> 'required|same:password'
        ]);

        if( $validator->fails() ) {
            return $this->sendError('Validation Error.'
            , $validator->errors());
        }
        $input = $request->all();
        $input['password'] = bcrypt( $input['password'] );
        $user = User::create( $input );
        $success['token'] = $user->createToken('MySlowApp')
        ->plainTextToken;
        $success['name'] = $user->name;

        return $this->sendResponse( $success,'User Register Successfully.');
    }

    public function login( Request $request ){
        if ( Auth::attempt([
            'email'=> $request->email,
            'password'=> $request->password
        ])) {
            $user = Auth::user(); 
            $success['token'] = $user->createToken('MySlowApp')->plainTextToken;
            $success['name']= $user->name;

            return $this->sendResponse($success, 'User Login Successfully.');
        } else {
            return $this->sendError('Unauthorised', ['error'=> 'Unauthorised']);
        }
    }
}
