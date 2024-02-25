<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestSend;

class MailController extends Controller
{
    public function Create(Request $request)
    {
        $response['auth_status'] = 0;
        $user = (new User())->create($request->input('username'));
        if($user != null)
        {
            $response['auth_status'] = (new UserAuth())->register_mail($user->user_id, $request->input('mail'), $request->input('password'));
        }
        return response()->json($response);
    }

    public function Auth(Request $request)
    {
        $response = (new UserAuth())->check_mail_token($request->input('token'), $request->input('otp'));
        return response()->json($response);
    }

    public function TestMail(Request $request)
    {
        Mail::to($request->user())->send(new TestSend());
        $response['status'] = 'success';
        return response()->json($response);
    }
}