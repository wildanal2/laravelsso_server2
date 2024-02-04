<?php

namespace App\Http\Controllers\Entity;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use App\Models\SsoEntity;
use App\Models\User;
use Illuminate\Http\Request;

class ViewController extends Controller
{

    public function index()
    {
        $entity = SsoEntity::all();
        $view_data['entity_total'] = $entity->count();

        return view('pages.entity.index', $view_data);
    }


    public function detail($entity_id = null)
    {
        $view_data['entity'] = SsoEntity::with([])->find($entity_id);

        return view('pages.entity.detail', $view_data);
    }

    public function form($mod = null)
    {
        $view_data['entity'] = SsoEntity::with([])->find($mod);

        return view('pages.entity.form', $view_data);
    }
}
