<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\SessionWrapper;

class UserSession
{
    public function __construct()
    {
        return $this;
    }

    public function get_user_id($session_key)
    {
        DB::beginTransaction();
        try
        {
            $session_data = DB::table('tbl_session')
                ->select('user_id')
                ->where('session_key', '=', $session_key)
                ->where('is_deleted', '=', 0)
                ->first();
            DB::commit();
        }
        catch (Exception $e)
        {
            DB::rollback();
            Log::error('exception: ' . $e->getMessage());
            return 0;
        }      
        if(empty($session_data->user_id)) return 0;
        return intval($session_data->user_id);
    }

    public function delete($session_key)
    {
        $result = 0;
        DB::beginTransaction();
        try
        {
            $result = DB::table('tbl_session as a')
                ->where('session_key', '=', $session_key)
                ->update([
                    'is_deleted' => 1
                ]);
            DB::commit();
        }
        catch(\Exception $e)
        {
            Log::error('exception: ' . $e->getMessage());
            DB::rollBack();
            return $result;
        }
        return $result;
    }
}