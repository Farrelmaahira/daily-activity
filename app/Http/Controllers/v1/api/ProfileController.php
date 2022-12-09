<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Rules\LowerCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        
       $user = $request->user();
       $data = ProfileResource::make($user);
       return $this->sendResponse('Profile data', $data);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $user = $request->user();   
        $data = User::where('id', $user['id'])->get();
        return $data;

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
        $user = User::where('id', $id);
        $validate = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', Rule::unique('users')->ignore($id), new LowerCase],
            'position' => ['required']
            
        ]); 
        if($validate->fails())
        {
            return $this->errorResponse($validate->errors(), 400);
        }
        if($request->has('image'))
        {
            $image = $request->file('image');
            $image->storeAs('storage/img/', $image->hashName());
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'position_id' => $request->position,
                'image' => $request->image 
            ]);
        }   

        return $this->sendResponse($user, 'data has been updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request)
    {
        $user = $request->user();
        $token = $user->currentAccessToken();
        $token->delete();
        $data = User::where('id', $user['id']);
        $data->delete();
        return $this->sendResponse($data, 'data has been deleted');
        
    }
}
