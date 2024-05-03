<?php

namespace App\Traits;

use Carbon\Carbon;

trait ApiResponser{

    protected function token($personalAccessToken, $message = null, $code = 200)
    {
        $tokenData = [
            'access_token' => $personalAccessToken->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($personalAccessToken->token->expires_at)->toDateTimeString()
        ];

        return $this->success($tokenData, $message, $code);
    }
    
    protected function success($data, $message = null, $code = 200)
    {
        return response()->json([
            'status'=> 'Success', 
            'message' => $message, 
            'data' => $data
        ], $code);
    }

    protected function error($message = null, $code)
    {
        return response()->json([
            'status'=>'Error',
            'message' => $message,
            'data' => null
        ], $code);
    }

}