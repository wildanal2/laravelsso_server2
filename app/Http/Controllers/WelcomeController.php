<?php

namespace App\Http\Controllers;

use App\Models\OAuthClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WelcomeController extends Controller
{ 
    public function index()
    {
        $viewdata['app'] = OAuthClient::all();
        
        return view('welcome', $viewdata);
    }

}
