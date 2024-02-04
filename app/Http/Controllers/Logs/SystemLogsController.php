<?php

namespace App\Http\Controllers\Logs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemLogsController extends Controller
{
    //
    public function index()
    { 
        $view_data[''] = [];
        dd('ee');
        return view('vendor.log-viewer.index', $view_data);
    }
}
