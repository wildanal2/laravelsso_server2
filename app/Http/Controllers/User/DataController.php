<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;
use Yajra\DataTables\Facades\DataTables;

class DataController extends Controller
{

    public function get()
    {
        $query = User::all();
        // dd($query);
        return DataTables::of($query) 
            ->addColumn('role', function ($eloquent) {
                return '';
            })
            ->addColumn('entity', function ($eloquent) {
                return '';
            })
            ->addColumn('buttons', function ($eloquent) {
                $array = [];
                $user = auth()->user();
                // if ($user->hasPermissionTo('edit-user')) {
                //     $array[] = [
                //         'url' => url('users/' . $eloquent->id . '/edit'),
                //         'className' => 'btn-success',
                //         'text' => 'Edit',
                //         'fontawesome' => 'fa-user-cog',
                //     ];
                // } 
                $array[] = [
                    'url' => url('modules/' . $eloquent->id . '/detail'),
                    'className' => 'btn-light',
                    'text' => 'Detail',
                    'fontawesome' => 'lni lni-eye',
                ];
                $array[] = [
                    'url' => url('modules/' . $eloquent->id . '/edit'),
                    'className' => 'btn-light',
                    'text' => 'Edit',
                    'fontawesome' => 'lni lni-cogs',
                ];
                return $array;
            })
            ->make(true);
    }

    public function submit($module_id = null)
    {
        try {
            if ($module_id != null) {
                $client = OAuthClient::find($module_id);
            }
            // validate
            request()->validate([
                'nama' => 'required|max:255',
                'redirect' => 'required|max:1000',
            ]);
            // 
            DB::connection('mysql')->beginTransaction();

            if (!isset($client)) {
                $clients = App::make(ClientRepository::class);
                // 1st param is the user_id - none for client credentials
                // 2nd param is the client name
                $client_name = request()->input('nama');
                // 3rd param is the redirect URI - none for client credentials
                $uri = request()->input('redirect');
                $client = $clients->create(null, $client_name, $uri);
            }else{
                $client->name = request()->input('nama');
                $client->redirect = request()->input('redirect');
                $client->save();
            }

            DB::connection('mysql')->commit();
            return $this->responseRedirect('modules', 'Successfully Create Module', 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            return $this->error_handler($e);
        }
    }
}
