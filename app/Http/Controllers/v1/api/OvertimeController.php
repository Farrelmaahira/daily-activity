<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\Controller;
use App\Models\Overtime;
use Illuminate\Http\Request;
use App\Http\Controllers\v1\api\BaseController;
use App\Http\Resources\OvertimeResource;
use Illuminate\Support\Facades\Validator;

class OvertimeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {       
        $user = $request->user();
        $data = OvertimeResource::collection(Overtime::where('user_id', $user['id'])->get());
        return $this->sendResponse($data, 'Here ur data');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            'overtime' => 'required',
            'date' => 'required',
            'from' => 'required',
            'untill' => 'required'
        ]);
        if($validate->fails())
        {
            return $this->errorResponse('Validation error', $validate->errors(), 400);
        }

        $data = Overtime::create([
            'user_id' => $user['id'],
            'overtime' => $request->overtime,
            'date' => $request->date,
            'from' => $request->from,
            'untill' => $request->untill,
        ]);

        return $this->sendResponse(new OvertimeResource($data), 'Overtime has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
        $data = Overtime::where('id', $id)->where('user_id', $user['id'])->get();
        $count = count($data);
        if($count == 0)
        {
            return $this->errorResponse($data ,'You dont have this data', 404);
        }
        return $this->sendResponse($data, 'Here ur data');
        
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
            'overtime' => 'required',
            'date' => 'required',
            'from' => 'required',
            'untill' => 'required'
        ]); 
        if($validate->fails())
        {
            return $this->errorResponse('Validation error', $validate->errors(), 400);
        }
        
        $data = Overtime::where('id', $id)->where('user_id', $user['id']);
        $data->update([
            'user_id' => $user['id'],
            'overtime' => $request->overtime,
            'date' => $request->date,
            'from' => $request->from,
            'untill' => $request->untill
        ]);

        return $this->sendResponse($data, 'Data has been updated');
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
        $data = Overtime::where('id', $id)->where('user_id', $user['id'])->get();
        $count = count($data);
        if($count == 0)
        {
            return $this->errorResponse($data, 'You dont have this data', 404);
        }
        $data = Overtime::where('id', $id)->where('user_id', $user['id'])->delete();
        return $this->sendResponse($data, 'data has been deleted');  
    }

    public function general()
    {
        $data = OvertimeResource::collection(Overtime::select()->with('user')->get());
        return $this->sendResponse($data, 'Here ur data');
    }
}
