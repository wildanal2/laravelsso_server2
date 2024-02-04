<?php

namespace App\Http\Controllers\OAuthClient;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use App\Models\User;
use Illuminate\Http\Request;

class ViewController extends Controller
{

    public function index()
    {
        $modules = OAuthClient::all();
        $view_data['oauthclient_total'] = $modules->count();

        return view('pages.oauthclient.index', $view_data);
    }


    public function detail($mod = null)
    {
        $view_data['client'] = OAuthClient::with([])->find($mod);

        return view('pages.oauthclient.detail', $view_data);
    }

    public function form($mod = null)
    {
        $view_data['client'] = OAuthClient::with([])->find($mod);

        return view('pages.oauthclient.form', $view_data);
    }
}
