<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\Controller;
use App\Models\Overtime;
use Illuminate\Http\Request;
use App\Http\Controllers\v1\api\BaseController;
use App\Http\Resources\OvertimeResource;
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
        $data = OvertimeResource::collection(Overtime::select()->get());
        return $this->sendResponse($data, 'Here ur data');
    }

}
