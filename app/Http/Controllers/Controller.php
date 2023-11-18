<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendResponse($status, $status_code, $message, $data){
        return response()->json([
            "status" => isset($status) ? $status : false,
            "message" => isset($message) ? $message : null,
            "data" => isset($data) ? $data : [],
        ], isset($status_code) ? $status_code : 400);
    }
}
