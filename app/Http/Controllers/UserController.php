<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function Create(Request $request)
    {
        $user = (new User())->create($request->input('user_name'), $request->input('custom_user_id'));
        return response()->json($user);
    }

    public function Read(Request $request, $id)
    {
        $user = (new User())->read($id);
        return response()->json($user);
    }

    public function Update(Request $request, $id)
    {
        $user = (new User())->list($id, $request->input('user_name'), $request->input('custom_user_id'));
        return response()->json($user);
    }

    public function Delete(Request $request, $id)
    {
        $user = (new User())->list($id);
        return response()->json($user);
    }
}