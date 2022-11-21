<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use App\Models\DailyActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $validate = Validator::make($request->all(), [
            'activity' => 'required',
            'date' => 'required'
        ]);

        if($validate->fails())
        {
            return $this->errorResponse('Validate Error', $validate->errors(), 400);
        }
        // CHECK DATA IF DATA EXIST
        $data = DailyActivity::where('user_id', $user['id'])->where('date', $request->date)->get();
        $count = count($data);
        if($count > 0)
        {
            return $this->errorResponse('You have been input your activity on this date');
        }
        // STORE DATA
        $data = DailyActivity::create([
            'user_id' => $user['id'],
            'activity' => $request->activity,
            'date' => $request->date
        ]);

        return $this->sendResponse(new ActivityResource($data), 'Success');

        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
       
        $user = $request->user();
        $data = DailyActivity::where('user_id', $user['id'])->where('id', $id)->get();
        $count = count($data);
        $response = ActivityResource::collection($data);
       
        if($count === 0  )
        {
            return $this->errorResponse('Data not Found');
        }
        return $this->sendResponse($response, 'pp');
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
        $data = DailyActivity::where('user_id', $user['id'])->where('id', $id)->get();
        $count = count($data);
        $response = ActivityResource::collection($data);
       
        if($count === 0  )
        {
            return $this->errorResponse('Data not Found');
        }
        return $this->sendResponse($response, 'pp');
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
        $user = $request->user();
        $validate = Validator::make($request->all(), [
            'activity' => 'required',
            'date' => 'required'
        ]);
        if($validate->fails())
        {
            return $this->errorResponse('Validate Error', $validate->errors(), 400);
        }

        $act = DailyActivity::where('user_id', $user['id'])->where('id', $id);
        $act->update([
            'user_id' => $user['id'],
            'activity' => $request->activity,
            'date' => $request->date
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
        return $this->sendResponse($data, 'Data has been deleted', 204);
    }

    public function general()
    {
        $data = ActivityResource::collection(DailyActivity::select()->get());
        return $this->sendResponse($data, 'success');
    }
}
// $user = $request->user();
// $validate = Validator::make($request->all(), [
//     'activity' => 'required',
//     'date' => 'required'
// ]);
// if($validate->fails())
// {
//     return $this->errorResponse('Validation Failed', $validate->errors(), 400);
// }
// $act = DailyActivity::create([
//     'user_id' => $user['id'],
//     'activity' => $request->activity,
//     'date' => $request->date
// ]);


// return $this->sendResponse(new ActivityResource($act), 'Add Activities success');