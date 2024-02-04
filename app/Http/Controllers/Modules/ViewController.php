<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use App\Models\SsoModule;
use App\Models\User;
use Illuminate\Http\Request;

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
        $view_data['client'] = OAuthClient::with([])->find($mod);

        return view('pages.modules.detail', $view_data);
    }

    public function form($mod = null)
    {
        $view_data['module'] = SsoModule::with([])->find($mod);

        return view('pages.modules.form', $view_data);
    }
}
