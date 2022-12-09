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

        $dailyAct = DailyActivity::query();

        $date = Carbon::parse($request->date);
        
        if($request->has('search'))
        {
            $dailyAct->where(function($query) use ($request){
                $query->where('activity', 'LIKE', '%' . $request->search . '%')->orWhereHas('user', function($q) use ($request) {
                    $q->where('user', 'LIKE', '%'. $request->search. '%');
                });
            });
        }

        if($request->has('date'))
        {
            $dailyAct->where('date', $date);
        }
        if($request->has('position'))
        {
            $dailyAct->whereHas('user', function($query) use ($request)
            {
                $query->where('position_id', $request->position);
            });
        }
        if($request->has('month'))
        {
            $dailyAct->whereMonth('date', $request->month);
        }
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