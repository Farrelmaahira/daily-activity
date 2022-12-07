<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\v1\api\BaseController;
use App\Http\Resources\ActivityResource;
use App\Models\DailyActivity;
use App\Models\Position;
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
            'date' => 'nullable',
            'month' => 'nullable',
            'position' => 'nullable',
            'sort' => 'nullable'
        ]);

        $dailyAct = DailyActivity::query();

        $date = Carbon::parse($request->date);
        
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
        $data = ActivityResource::collection($dailyAct->get());
        return $this->sendResponse($data, 'blabla');
    }

    public function show()
    {
        $data = Position::select()->get();
        return response()->json($data);
    }
    
}