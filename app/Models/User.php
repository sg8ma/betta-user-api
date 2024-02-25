<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class User
{
    public int $user_id;
    public string $user_name;
    public string $custom_user_id;
    public Carbon $created_at;
    public Carbon $updated_at;
    public bool $is_deleted;

    public function __construct()
    {
        return $this;
    }

    public function list()
    {
        DB::beginTransaction();
        try
        {
            $users = DB::table('tbl_user')
                ->select(
                    'user_id',
                    'user_name',
                    'custom_user_id',
                    'created_at',
                    'updated_at',
                )
                ->orderBy('user_id', 'asc')
                ->where('is_deleted', '=', 0)
                ->get();
            DB::commit();
        }
        catch(\Exception $e)
        {
            $users = null;
            Log::error('exception: ' . $e->getMessage());
            DB::rollBack();
        }
        return $users;
    }

    public function read($id)
    {
        DB::beginTransaction();
        try
        {
            $user = DB::table('tbl_user')
                ->select(
                    'user_id',
                    'user_name',
                    'custom_user_id',
                    'created_at',
                    'updated_at',
                )
                ->where('user_id', '=', $id)
                ->where('is_deleted', '=', 0)
                ->first();
            DB::commit();
        }
        catch(\Exception $e)
        {
            $users = null;
            Log::error($e->getMessage());
            DB::rollBack();
        }
        return $user;
    }

    public function create($user_name, $custom_user_id = '')
    {
        if(empty($custom_user_id))
        {
            $custom_user_id = 'u' . uniqid(mt_rand());
        }
        DB::beginTransaction();
        try
        {
            $user_id = DB::table('tbl_user')
                ->insertGetId([
                    'user_name' => $user_name,
                    'custom_user_id'=> $custom_user_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            DB::commit();
        }
        catch(\Exception $e)
        {
            $user_id = -1;
            Log::error($e->getMessage());
            DB::rollBack();
        }
        if($user_id > 0)
        {
            $user = $this->read($user_id);
        }
        else
        {
            $user = null;
        }
        return $user;
    }

    public function update($id, $user_name, $custom_user_id)
    {
        DB::beginTransaction();
        try
        {
            $result = DB::table('tbl_user')
                ->where('user_id', '=', $id)
                ->update([
                    'user_name' => $user_name,
                    'custom_user_id'=> $custom_user_id,
                    'updated_at' => Carbon::now(),
                ]);
            DB::commit();
        }
        catch(\Exception $e)
        {
            $result = -1;
            Log::error($e->getMessage());
            DB::rollBack();
        }
        if($result > 0)
        {
            $user = $this->read($id);
        }
        else
        {
            $user = null;
        }
        return $user;
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try
        {
            $result = DB::table('tbl_user')
                ->where('user_id', '=', $id)
                ->update([
                    'is_deleted' => 1,
                    'deleted_at' => Carbon::now(),
                ]);
            DB::commit();
        }
        catch(\Exception $e)
        {
            $result = -1;
            Log::error($e->getMessage());
            DB::rollBack();
        }
        if($result > 0)
        {
            $user = $this->read($id);
        }
        else
        {
            $user = null;
        }
        return $user;
    }
}