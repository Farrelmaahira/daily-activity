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
        $data = ActivityResource::collection($dailyAct->with('user')->paginate(5));
        return $this->sendResponse($data, 'success');
    }


    
}
