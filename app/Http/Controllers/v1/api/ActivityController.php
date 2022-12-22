<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\v1\api\BaseController;
use App\Http\Resources\ActivityResource;
use App\Models\DailyActivity;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Http\Request;


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
            'date' => 'nullable',
            'month' => 'nullable',
            'position' => 'nullable',
            'sort' => 'nullable',
            'search' => 'nullable'
        ]);

        $dailyAct = new DailyActivity();

        $date = Carbon::parse($request->date);
        // SEARCH DATA
        if($request->has('search'))
        {
            $dailyAct = DailyActivity::search($request->search);
        }
        // FILTER BY DATE
        if($request->has('date'))
        {
            $dailyAct->where('date', $date);
        }
        // FILTER BY POSITION
        if($request->has('position'))
        {
            $dailyAct->whereHas('user', function($query) use ($request)
            {
                $query->where('position_id', $request->position);
            });
        }
        //FILTER BY MONTH
        if($request->has('month'))
        {
            $dailyAct->whereMonth('date', $request->month);
        }
        //SORT BY DATE
        if($request->has('sort'))
        {
            if($request->sort == 'latest')
            {
                $dailyAct->latest();
            } elseif($request->sort == 'oldest')
            {
                $dailyAct->oldest();
            }
        }
        
        $activity = ActivityResource::collection($dailyAct->get());
        $data = $activity->map(function($d, $key){
            $d['no'] = $key+1;  
            return $d;
        });
        return $this->sendResponse($data, 'blabla');
    }

    public function show()
    {
        $data = Position::select()->get();
        return response()->json($data);
    }
    
}