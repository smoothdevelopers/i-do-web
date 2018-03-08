<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function dashboard()
    {
        return view('admin.dashboard', [
            'page' => config('const.admin_route_codes.dashboard'),
        ]);
    }

    public function users()
    {
        return view('admin.users', [
            'page' => config('const.admin_route_codes.users'),
        ]);
    }

    public function singleUser(User $user)
    {
        return view('admin.single-user', [
            'page' => config('const.admin_route_codes.users'),
            'user' => $user,
        ]);
    }
}
