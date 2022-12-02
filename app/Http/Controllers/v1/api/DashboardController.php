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
        if($user->hasRole('leader'))
        {
           $user->unreadNotifications;
        }
        $data = Position::orderBy('id')->withCount("user");
        $result['position_label'] = $data->pluck('name');
        $result['user_position'] = $data->pluck('user_count');
        $result['activity_data'] = DailyActivity::select(DB::raw('COUNT(*) as count'))->groupBy(DB::raw('YEAR(date)'))->pluck('count');
        $result['activity_label'] = DailyActivity::select(DB::raw('YEAR(date) as label'))->distinct()->pluck('label');
        return response()->json(['user' => $user, 'chart' => $result]);
       
    }

  
}
