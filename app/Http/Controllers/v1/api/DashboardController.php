<?php

namespace App\Http\Controllers\v1\api;
use App\Http\Controllers\v1\api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResource;
use App\Models\DailyActivity;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $user = $request->user();
        // $result['position_label'] = Position::pluck('name');
        // $result['user_position'] = Position::withCount("user")->pluck('user_count');
        // $result['activity_data'] = DailyActivity::select(DB::raw('COUNT(*) as count'))->groupBy(DB::raw('YEAR(date)'))->pluck('count');
        // $result['activity_label'] = DailyActivity::select(DB::raw('YEAR(date) as label'))->distinct()->pluck('label');
        return response()->json($user);
       
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
        //
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
