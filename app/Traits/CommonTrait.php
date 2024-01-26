<?php
namespace App\Traits;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

trait CommonTrait {


    function sendSuccess($message, $data = '') {
        //return Response::json(array('status' => 200, 'successMessage' => $message, 'successData' => $data), 200, []);
        return response()->json([

            'message' => $message,
            'data' => $data,
        ],200);
    }

    /**
     * Show error message
     *
     * @param [type] $error_message
     * @param string $code
     * @param [type] $data
     * @return \Illuminate\Http\JsonResponse
     */
    function sendError($error_message, $code = '', $data = NULL) {
        //return Response::json(array('status' => 400, 'errorMessage' => $error_message), 400);
        return response()->json([

            'message' => $error_message,
            'data' => $data,
        ],400);
    }


}
