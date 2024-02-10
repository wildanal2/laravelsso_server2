<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Models\SsoModuleFeature;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ViewController extends Controller
{

    public function index(){ 
        $view_data['permission_count'] = Permission::all()->count();

        return view('pages.permission.index', $view_data);
    }

    public function detail($role_id = null)
    {
        $view_data['permission'] = Permission::with([])->findOrFail($role_id);
        $view_data['feature'] = SsoModuleFeature::where('id', $view_data['permission']->feature_id)->first();;
        
        return view('pages.permission.detail', $view_data);
    }

    public function form($role_id = null){
        $view_data['permission'] = Permission::with([])->find($role_id);
        $view_data['role'] = Role::all();
        
        return view('pages.permission.form', $view_data);
    }


}
