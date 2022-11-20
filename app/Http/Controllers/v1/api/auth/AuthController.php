<?php

namespace App\Http\Controllers\v1\api\auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\v1\api\BaseController;
use App\Models\Position;
use App\Models\User;
use App\Rules\LowerCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{

    public function index()
    {
        $position = Position::select()->get();
        return response()->json($position);
    }

    public function register(Request $request)
    {
       
        $validate = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required','email','unique:users', new LowerCase],
            'password' => ['required','min:8'],
            'position_id' => ['required']
        ]);

        if ($validate->fails()) {
            return $this->errorResponse('Validation fails', $validate->errors(), 400);
        }
       
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'position_id' => $request->position_id,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('user');

        $token = $user->createToken('APITOKEN')->plainTextToken;
        $data['data'] = $user;
        $data['token'] = $token;

        return $this->sendResponse($data, 'User has been registered' );
    }

    public function login(Request $request)
    {
        $validate = $request->validate([
            'email' => ['required',  new LowerCase],
            'password' => ['required']
        ]);
        // if($validate->fails())
        // {
        //     return $this->errorResponse('validation error', $validate->errors(), 400 );
        // }

        $user = User::where('email', $request->email)->first();
        if(Auth::attempt($validate))
        {  
            $data['data'] = $user;
            $data['token'] = $user->createToken('APITOKEN')->plainTextToken;
            if($user->hasRole('leader')){
                return $this->sendResponse($data, 'Login successfuly, Welcome leader');
            } elseif($user->hasRole('co-leader')){
                return $this->sendResponse($data, 'Login successfuly, Welcome co-leader');
            }else{
                return $this->sendResponse($data, 'Login successfuly, Welcome user');
            }
        }

        return $this->errorResponse('', 'The credentials is not match with our record', 400);
    }

   public function logout(Request $request)
   {
        $user = $request->user()->tokens()->delete();
        return $this->sendResponse($user, 'Logout Succesfully');
   }
}
