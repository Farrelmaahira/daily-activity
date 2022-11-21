<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\v1\api\BaseController;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Support\Facades\Validator;

class PositionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $position = PositionResource::collection(Position::select()->get());
        return $this->sendResponse($position, 'success');
        
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
        $validate = Validator::make($request->all(), [
            'name' => 'required|unique:positions',
        ]);

        if($validate->fails())
        {
            return $this->errorResponse('Validation error', $validate->errors(), 400);
        }

      $data = Position::create([
        'name' => $request->name 
      ]);   
        return $this->sendResponse(new PositionResource($data), 'success');
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
    public function edit($id)
    {
        $position = PositionResource::collection(Position::where('id', $id)->get());
        return $this->sendResponse($position, 'Success');
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
            'name' => 'required|unique:positions'
        ]);
        if($validate->fails())
        {
            return $this->errorResponse('Validate Error', $validate->errors(), 400);
        }

        $data = Position::find($id);
        $data->update([
            'name' => $request->name 
        ]);
        return $this->sendResponse($data, 'Data has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Position::find($id);
        $data->delete();
        return $this->sendResponse($data, 'Data has been deleted');
    }
}
