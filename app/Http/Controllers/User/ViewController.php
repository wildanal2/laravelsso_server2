<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ViewController extends Controller
{

    public function index()
    {
        $view_data['user_count'] = User::all()->count();

        return view('pages.users.index', $view_data);
    }

    public function detail($role_id = null)
    {
        $view_data['user'] = User::with(['hasEntities'])->find($role_id);

        return view('pages.users.detail', $view_data);
    }


    public function form($user_id = null)
    {
        $view_data['user'] = User::with(['hasEntities'])->find($user_id);
        $view_data['role'] = Role::all();
        
        return view('pages.users.form', $view_data);
    }
}
