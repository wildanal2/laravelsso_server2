<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ViewController extends Controller
{

    public function index(){
        $view_data['user_count'] = User::all()->count();

        return view('pages.users.index', $view_data);
    }


    public function form($user_id = null){
        $view_data['user'] = User::with([])->find($user_id);

        return view('pages.users.form', $view_data);
    }


}
