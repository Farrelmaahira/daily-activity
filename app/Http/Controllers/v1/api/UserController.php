<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\v1\api\BaseController;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
        $role = Role::select()->get();
        return $this->sendResponse($role, '');
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
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:8',
            'role' => 'required'
        ]);
        if($validate->fails())
        {
            return $this->errorResponse('Validation error', $validate->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $user->assignRole($request->role);
        return $this->sendResponse($user, 'New User has been updated');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::select()->get();
        $user = User::find($id);
        $user->roles;
        $user->get();
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
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id, 
            'password' => 'required|min:8',
            'role' => 'required'    
        ]);
        if($validate->fails())
        {
            return $this->errorResponse('Validate Error', $validate->errors());
        }
       
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
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
    
    public function profile($id)
    {
        $user = UserResource::collection(User::where('id', $id)->get());
        return $this->sendResponse($user, 'ldkjlj');
    }
   
}



        // $user = $request->user();
        // if($user->hasRole('leader'))
        // {
        //    $user = UserResource::collection(User::role(['co-leader', 'user'])->with(['roles', 'position ' ])->get());
        //    return $this->sendResponse($user, 'Here ur data');
        // }

        // $user = UserResource::collection(User::role('user')->with('roles')->get());
        //    return $this->sendResponse($user, 'Here ur data');