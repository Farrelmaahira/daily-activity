<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use App\Models\DailyActivity;
use App\Rules\CantSame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MyActivityController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $user = $request->user();
         $act = ActivityResource::collection(DailyActivity::where('user_id', $user['id'])->get());
         return $this->sendResponse($act, 'lasdjf');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $user = $request->user();
        $request->validate([
            'activity' => ['required'],
            'date' => 'required'
        ]);

        // // CHECK DATA IF DATA EXIST
        $data = DailyActivity::where('user_id', $user['id'])->where('date', $request->date)->count();

        if($data > 0)  throw ValidationException::withMessages(['date' => 'you have been input data on this date']);
        
        // STORE DATA
        $data = DailyActivity::create([
            'user_id' => $user['id'],
            'activity' => $request->activity,
            'date' => $request->date
        ]);

        return $this->sendResponse(new ActivityResource($data), 'Success');
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
        $data = DailyActivity::findOrFail($id);
        if($data->user_id !== $user['id'])
        {
            return $this->errorResponse('Forbidden', 403);
        }
        return $this->sendResponse($data, 'pp');
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
            'activity' => 'required',
            // 'date' => 'required'
        ]);
        if($validate->fails())
        {
            return $this->errorResponse($validate->errors(), 400);
        }
        $act = DailyActivity::findOrFail($id); 
        $act->update([
            'activity' => $request->activity,
            // 'date' => $request->date
        ]);
        return $this->sendResponse($act, 'Data has been update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $user = $request->user();
        $data = DailyActivity::where('user_id', $user['id'])->where('id', $id)->get();
        $count = count($data);
        if($count == 0)
        {
            return $this->errorResponse('Data Not Found');
        }
        $data = DailyActivity::where('user_id', $user['id'])->where('id', $id)->delete();
        return $this->sendResponse('', 'Data has been deleted', 200);
    }

   
}
