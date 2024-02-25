<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SessionWrapper;
use App\Models\UserSession;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function Login(Request $request)
    {
        $response = (new UserAuth())->login_mail($request->input('mail'), $request->input('password'));
        return response()->json($response);
    }

    public function Status(Request $request)
    {
        $response['login_status'] = ((new SessionWrapper())->ExistsId($request->input('session_key')))? 1 : 0;
        return response()->json($response);
    }

    public function Logout(Request $request)
    {
        $response['is_deleted'] = (new UserSession())->delete($request->input('session_key'));
        return response()->json($response);
    }
}