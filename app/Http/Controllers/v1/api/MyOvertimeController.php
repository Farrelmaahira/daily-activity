<?php

namespace App\Http\Controllers\v1\api;


use App\Http\Resources\OvertimeResource;
use App\Models\Overtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            return $this->errorResponse('Validate Error', $validate->errors(), 400);
        }
        // CHECK DATA IF DATA EXIST
        $data = Overtime::where('user_id', $user['id'])->where('date', $request->date)->get();
        $count = count($data);
        if($count > 0)
        {
            return $this->errorResponse('You have been input your overtime on this date');
        }
        // STORE DATA
        $data = Overtime::create([
            'user_id' => $user['id'],
            'overtime' => $request->overtime,
            'date' => $request->date,
            'from' => $request->from,
            'untill' => $request->untill    
            
        ]);

        return $this->sendResponse(new OvertimeResource($data), 'Success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $data = OvertimeResource::collection(Overtime::where('id', $id)->where('user_id', $user['id'])->get());
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
