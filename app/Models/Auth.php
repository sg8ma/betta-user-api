<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\SessionWrapper;
use Illuminate\Support\Facades\Mail;
use App\Mail\AuthMailAddress;

class UserAuth
{
    public function __construct()
    {
        return $this;
    }
    
    public function register_mail(string $user_id, string $mail, string $password)
    {
        DB::beginTransaction();
        try
        {
            $auth_mail_result = DB::table('tbl_user_auth')
                ->insert([
                    'user_id' => $user_id,
                    'identity_type'=> 'mail',
                    'identifier'=> $mail,
                    'credential'=> $password,
                    'updated_at' => Carbon::now(),
                ]);
            DB::commit();
        }
        catch(\Exception $e)
        {
            Log::error('exception: ' . $e->getMessage());
            DB::rollBack();
        }
        if($auth_mail_result)
        {
            $length = 80;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            $token = 't';
            for($i = 0; $i < $length; $i++) 
            {
                $token .= $characters[random_int(0, strlen($characters) - 1)];
            }
            $otp = mt_rand(100000, 999999);
            DB::beginTransaction();
            try
            {
                $auth_token_result = DB::table('tbl_user_auth')
                    ->upsert([
                        'user_id' => $user_id,
                        'identity_type'=> 'mail-token',
                        'identifier'=> $token, 
                        'credential'=> $otp, 
                        'updated_at' => Carbon::now(),
                        'expired_at' => Carbon::now()->addHour(2),
                    ]);
                DB::commit();
            }
            catch(\Exception $e)
            {
                Log::error('exception: ' . $e->getMessage());
                DB::rollBack();
            }
            if($auth_token_result > 0)
            {
                Mail::to(auth()->user())->send(new AuthMailAddress($mail, $token, $otp));
                return 1;
            }
        }
        return 0;
    }

    public function check_mail_token($token, $otp)
    {
        $response = null;
        $response['auth_status'] = 0;
        DB::beginTransaction();
        try
        {
            $auth_data = DB::table('tbl_user_auth')
                ->select('user_id')
                ->where('identity_type', '=', 'mail-token')
                ->where('identifier', '=', $token)
                ->where('credential', '=', $otp)
                ->where('expired_at', '>', Carbon::now())
                ->first();
            DB::commit();
        }
        catch(\Exception $e)
        {
            Log::error('exception: ' . $e->getMessage());
            DB::rollBack();
        }
        if(!empty($auth_data->user_id))
        {
            $user_auth_result = DB::table('tbl_user_auth')
                ->where('user_id', '=', $auth_data->user_id)
                ->where('identity_type', '=', 'mail')
                ->update([
                    'is_authenticated' => 1
                ]);
            if($user_auth_result > 0)
            {
                $response['auth_status'] = 1;
            }
        }
        return $response;
    }

    public function login_mail($mail, $password)
    {
        $response['session_key'] =  '';
        $response['login_status'] = 0;
        DB::beginTransaction();
        try
        {
            $auth_data = DB::table('tbl_user as a')
                ->leftJoin('tbl_user_auth as b', 'a.user_id', '=', 'b.user_id')
                ->select('a.user_id')
                ->where('a.is_deleted', '=', '0')
                ->where('b.identity_type', '=', 'mail')
                ->where('b.identifier', '=', $mail)
                ->where('b.credential', '=', $password)
                ->first();
            DB::commit();
        }
        catch(\Exception $e)
        {
            Log::error('exception: ' . $e->getMessage());
            DB::rollBack();
            return $response;
        }
        if(!empty($auth_data->user_id))
        {
            $response['session_key'] =  (new SessionWrapper())->RegenerateId();
            $response['login_status'] = 1;
        }
        return $response;
    }
}