<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Permission as ModelsPermission;
use App\Models\SsoModuleFeature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ViewController extends Controller
{

    public function index()
    {
        $view_data['role_count'] = Role::all()->count();

        return view('pages.roles.index', $view_data);
    }

    public function detail($role_id = null)
    {
        $view_data['role'] = Role::with(['permissions'])->findOrFail($role_id);

        return view('pages.roles.detail', $view_data);
    }

    public function form($role_id = null)
    {
        $view_data['role'] = Role::with([])->find($role_id);
        $view_data['other'] = ModelsPermission::doesntHave('feature')->get();
        $modulFeatGroup = SsoModuleFeature::with(['module', 'permission'])->get()->groupBy('module.name');
        $view_data['modulFeature'] = $modulFeatGroup->map(function ($item, $key) {
            $listFiture = $item->groupBy('name');
            $_ListFiture = $listFiture->map(function ($obj, $keyy) {
                return [
                    'feature_name' => strtoupper($keyy),
                    'permissions' => $obj->values()->toArray()
                ];
            })->values();
            return [
                'module_name' => strtoupper($key),
                'data' => $_ListFiture,
            ];
        })->values();

        // dd($view_data);
        return view('pages.roles.form', $view_data);
    }
}
