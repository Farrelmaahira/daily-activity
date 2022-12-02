<?php

use App\Http\Controllers\v1\api\ActivityController;
use App\Http\Controllers\v1\api\auth\AuthController;
use App\Http\Controllers\v1\api\DashboardController as ApiDashboardController;
use App\Http\Controllers\v1\api\MyActivityController;
use App\Http\Controllers\v1\api\MyOvertimeController;
use App\Http\Controllers\v1\api\OvertimeController;
use App\Http\Controllers\v1\api\PositionController;
use App\Http\Controllers\v1\api\ProfileController;
use App\Http\Controllers\v1\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function (){
    Route::get('/v1/auth/register', 'index');
    Route::post('/v1/auth/register', 'register');
    Route::post('/v1/auth/login', 'login');
  
})->middleware('guest');

Route::middleware(['auth:sanctum', 'role:leader|co-leader|user'])->group(function (){
    Route::resource('/v1/dashboard/activity', ActivityController::class); 
    Route::get('/v1/dashboard', [ApiDashboardController::class, 'index']);
    Route::resource('/v1/dashboard/myactivity', MyActivityController::class);
    Route::post('/v1/auth/logout', [AuthController::class, 'logout']);
    Route::get('/v1/dashboard/overtime', [OvertimeController::class, 'index']);
    Route::resource('/v1/dashboard/profile', ProfileController::class);
    Route::resource('/v1/dashboard/myovertime', MyOvertimeController::class);
});

Route::middleware(['auth:sanctum', 'role:leader|co-leader'])->group(function () {
    Route::resource('/v1/dashboard/user', UserController::class);
});

Route::middleware(['auth:sanctum', 'role:leader'])->group(function () {
    Route::resource('/v1/dashboard/position', PositionController::class);
    Route::get('/v1/dashboard/user/profile/{id}', [UserController::class, 'show']);
});

/*
http://192.168.101.144/dashboard

ACTIVITY
http://192.168.101.144/dashboard/activity (activity general)
http://192.168.101.144/dashboard/myactivity (myactivity)
http://192.168.101.144/dashboard/myactivity/id (get & post myactivity by id)
http://192.168.101.144/dashboard/myactivity/id (update & delete myactivity by id)
OVERTIME
http://192.168.101.144/dashboard/overtime (overtime general)
http://192.168.101.144/dashboard/myovertime ( get & post myovertime )
http://192.168.101.144/dashboard/myovertime/id ( get overtime by id)
http://192.168.101.144/dashboard/myovertime/id (update & delete overtime by id )
USER (ROLE LEADER & CO LEADER)
http://192.168.101.144/dashboard/user (get & post all user)
http://192.168.101.144/dashboard/user/create (get data role & position)
http://192.168.101.144/dashboard/user/id (get user by id)
http://192.168.101.144/dashboard/user/id (update & delete user by id)
PROFILE
http://192.168.101.144/dashboard/profile (get profile)






*/
