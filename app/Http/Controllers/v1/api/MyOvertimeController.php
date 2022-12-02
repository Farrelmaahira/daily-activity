<?php

namespace App\Http\Controllers\v1\api;


use App\Http\Resources\OvertimeResource;
use App\Models\Overtime;
use App\Models\User;
use App\Notifications\NewOvertime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MyOvertimeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    
    public function index(Request $request)
    {
        $user = $request->user();
        $act = OvertimeResource::collection(Overtime::where('user_id', $user['id'])->get());
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
            'overtime' => 'required',
            'date' => 'required',
            'from' => 'required',
            'untill' => 'required'
        ]);
        // CHECK DATA IF DATA EXIST
        $data = Overtime::where('user_id', $user['id'])->where('date', $request->date)->count();
        if($data > 0) throw ValidationException::withMessages(['date' => 'You have been input data in this date']);
        // STORE DATA
        $data = Overtime::create([
            'user_id' => $user['id'],
            'overtime' => $request->overtime,
            'date' => $request->date,
            'from' => $request->from,
            'untill' => $request->untill    
            
        ]);
        $leader = User::role('leader')->get();
        Notification::send($leader, new NewOvertime($user, 'added new overtime activity'));

        return $this->sendResponse(new OvertimeResource($data), 'Success');
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
        $data = Overtime::findOrFail($id);
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

        $user = $request->user();

        $data = Overtime::findOrFail($id);
        if($data->user_id !== $user['id'])
        {
            return $this->errorResponse('Forbidden', 403);
        }

        $validate = Validator::make($request->all(), [
            'from' => 'required',
            'untill' => 'required',
            'overtime' => 'required'
        ]);

        if($validate->fails())
        {
            return $this->errorResponse($validate->errors(),  400);
        }

        $data->update([
            'from' => $request->from,
            'untill' => $request->untill,
            'overtime' => $request->overtime
        ]);

        return $this->sendResponse($data, 'Data has been updated', 202);

        
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
        $data = Overtime::findOrFail($id);
        if($data->user_id !== $user['id'])
        {
            return $this->errorResponse('Forbidden', 403);
        }

        $data->delete();
        return $this->sendResponse($data, 'Data has been deleted');

    }
}
