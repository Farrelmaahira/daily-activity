<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\v1\api\BaseController;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\UserResource;
use App\Rules\LowerCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auth = $request->user();
        $this->validate($request, [
            'position' => 'integer'
        ]);
        $user = User::query();
        if($auth->hasRole('leader'))
        {
            if($request->has('position'))
            {
                $user->where('position_id', $request->position);
            }
            $data = UserResource::collection($user->role(['co-leader', 'user'])->with(['roles', 'position'])->get());
            return $this->sendResponse($data, 'here ur data');
        } else {

            if($request->has('position'))
            {
                $user->where('position_id', $request->position);
            }
            $data = UserResource::collection($user->role('user')->with(['roles', 'position'])->get());
            return $this->sendResponse($data, 'here ur data');
        }
       
    }  

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $data['role'] = Role::select()->get();
       $data['position'] = Role::select()->get();
        return $this->sendResponse($data, '');
    }
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required','email','unique:users', new LowerCase],
            'password' => ['required', 'min:8'],
            'role' => ['required'],
            'position' => ['required']
        ]);
        if($validate->fails())
        {
            return $this->errorResponse($validate->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'position_id' => $request->position 
        ]);
        $user->assignRole($request->role);
        return $this->sendResponse($user, 'New User has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = UserResource::collection(User::where('id', $id)->get());
        return $this->sendResponse($user, 'ldkjlj');
    }
   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::select()->get();
        $user = UserResource::collection(User::where('id', $id)->get());
        $data = [
            'user' => $user,
            'role' => $role
        ];

        return $this->sendResponse($data, 'sdf');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response    
     */
    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($id), new LowerCase], 
            'password' => 'required|min:8',
            'role' => 'required', 
            'position' => 'required'
        ]);
        if($validate->fails())
        {
            return $this->errorResponse($validate->errors(), 400);
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'position_id' => $request->position
        ]);
        if($request->has('role')){
            $user->syncRoles($request->role);
        }
        return $this->sendResponse($user, 'data has been updated');
    }    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return $this->sendResponse($user, 'This user has been delete');
    }
    
   
   
}


