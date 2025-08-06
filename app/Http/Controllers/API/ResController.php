<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResController extends Controller
{
   /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($status, $message, $body = null)
    {
        $response = [
            'status'  => $status,
            'message' => $message,
        ];
        if(!empty($body)){
            $response['body'] = $body;
        }

        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sensResNoBody($error, $errorMessages = [], $code = 401)
    {
        $response = [
            'status' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['body'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}
