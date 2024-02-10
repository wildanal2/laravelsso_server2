<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use App\Models\SsoModule;
use App\Models\SsoModuleFeature;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class ViewController extends Controller
{

    public function index()
    {
        $modules = SsoModule::all();
        $view_data['module_total'] = $modules->count();

        return view('pages.modules.index', $view_data);
    }

    public function detail($mod = null)
    {
        $view_data['modul'] = SsoModule::with([])->findOrFail($mod);
        $modulFeatGroup = SsoModuleFeature::where('module_id', $mod)->get()->groupBy('name');
        $view_data['modulFeature'] = $modulFeatGroup->map(function ($item, $key) use ($mod) {
            $listModPerm = SsoModuleFeature::with(['permission'])->where(['name' => $key, 'module_id' => $mod])->get();
            return [
                'name' => strtoupper($key),
                'permission' => $listModPerm->pluck('permission.name'),
            ];
        })->values();

        $view_data['oauth'] = OAuthClient::find($view_data['modul']?->oclient_id ?? '');

        return view('pages.modules.detail', $view_data);
    }

    public function form($mod = null)
    {
        $view_data['module'] = SsoModule::with([])->find($mod);
        $view_data['oauth'] = OAuthClient::find($view_data['module']?->oclient_id ?? '');

        return view('pages.modules.form', $view_data);
    }
}
