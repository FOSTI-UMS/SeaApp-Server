<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    public $success = array(200,FALSE);
    public $created = array(201,FALSE);
    public $error = array(401,TRUE);
    public $server_error = array(500,TRUE);

    public function apiResponse($status,$message,$data)
    {
        return array(
            "error" => $status[1],
            "code" => $status[0],
            "message" => $message,
            "data" => $data
        );
    }

    protected function respondWithToken($token,$credentials)
    {
        

        return $this->apiResponse(
            $this->success,
            "Unauthorized, Please Login",
            [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => null,
                'user_data' => $credentials
            ]
        );
    }


}
