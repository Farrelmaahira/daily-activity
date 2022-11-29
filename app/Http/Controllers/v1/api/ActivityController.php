<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\v1\api\BaseController;
use App\Http\Resources\ActivityResource;
use App\Models\DailyActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;


class ActivityController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'start' => 'date|nullable',
            'end' => 'date|after_or_equal:start',
            'position' => 'nullable'
        ]);

        $dailyAct = DailyActivity::query();

        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        if ($request->has(['start', 'end'])) {

            if($request->has('position'))
            {
                $dailyAct->whereHas('user', function ($query) use ($request) {
                    $query->where('position_id', $request->position);
                });
            } 

            $dailyAct->whereBetween('date', [$start, $end]);

        } elseif($request->has('position')) {

            $dailyAct->whereHas('user', function ($query) use ($request) {
                $query->where('position_id', $request->position);
            });

        }

        $act = ActivityResource::collection($dailyAct->select()->with('user')->get());
        return $this->sendResponse($act, 'success');
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
        //
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
