<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class ApiController extends Controller
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

}