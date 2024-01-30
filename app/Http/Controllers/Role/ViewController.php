<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ViewController extends Controller
{

    public function index(){ 
        $view_data['role_count'] = Role::all()->count();

        return view('pages.roles.index', $view_data);
    }

    public function detail($role_id = null)
    {
        $view_data['role'] = Role::with(['permissions'])->find($role_id);
        
        return view('pages.roles.detail', $view_data);
    }

    public function form($role_id = null){
        $view_data['role'] = Role::with([])->find($role_id);
        
        return view('pages.roles.form', $view_data);
    }


}
