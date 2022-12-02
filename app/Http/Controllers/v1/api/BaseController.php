<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($data, $message, $code = 200)
    {
        $response = [
            'success' => true,
            'data' => $data,
            'message' => $message
        ];

        return response()->json($response, $code);
    }

    public function errorResponse($message = [], $code = 404)
    {
        $response = [
            'success' => false,
        ];

        if(!empty($message))
        {
            $response['message'] = $message;
        }
        return response()->json($response, $code);
    }
}
