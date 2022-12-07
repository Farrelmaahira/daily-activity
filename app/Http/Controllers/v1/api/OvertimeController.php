<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\Controller;
use App\Models\Overtime;
use Illuminate\Http\Request;
use App\Http\Controllers\v1\api\BaseController;
use App\Http\Resources\OvertimeResource;
use Carbon\Carbon;
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
        $this->validate($request, [
            'date' => 'nullable',
            'month' => 'nullable',
            'position' => 'nullable',
            'sort' => 'nullable'
        ]);
        $ovt = Overtime::query();
        $date = Carbon::parse($request->date);
        if($request->has('date'))
        {
            $ovt->where('date', $date);
        }
        if($request->has('position'))
        {
            $ovt->whereHas('user', function ($query) use ($request)
            {
                $query->where('position_id', $request->position);
            });
        }
        if($request->has('month'))
        {
            $ovt->whereMonth('date', $request->month);
        }
        if($request->has('sort'))
        {
           if($request->sort == 'oldest')
           {
                $ovt->oldest();
           } elseif($request->sort == 'latest') {
                $ovt->latest();
           }
        }
        $data = OvertimeResource::collection($ovt->latest()->paginate(10));
        return $data;
    }
}
