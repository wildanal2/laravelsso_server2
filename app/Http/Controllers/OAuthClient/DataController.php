<?php

namespace App\Http\Controllers\OAuthClient;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\ClientRepository;
use Yajra\DataTables\Facades\DataTables;

class DataController extends Controller
{

    public function get()
    {
        $query = OAuthClient::all();
        
        return DataTables::of($query)
            ->addColumn('secret', function ($eloquent) {
                return '**********';
            })
            ->addColumn('buttons', function ($eloquent) {
                $array = [];
                $user = auth()->user();
                $array[] = [
                    'url' => url('oclient/' . $eloquent->id . '/detail'),
                    'className' => 'btn-outline-secondary',
                    'text' => 'Detail',
                    'fontawesome' => 'lni lni-eye',
                ];
                $array[] = [
                    'url' => url('oclient/' . $eloquent->id . '/edit'),
                    'className' => 'btn-outline-secondary',
                    'text' => 'Edit',
                    'fontawesome' => 'lni lni-cogs',
                ];
                if ($user->hasPermissionTo('sso.manage-oauth-client')) {
                    $array[] = [
                        'url' => 'javascript:void(0)',
                        'className' => 'btn-outline-secondary btn-oauth-destroy',
                        'text' => 'Delete',
                        'fontawesome' => 'lni lni-trash',
                        'attribute' => [
                            [
                                'name' => 'data-id',
                                'text' => $eloquent->id,
                            ],
                            [
                                'name' => 'data-name',
                                'text' => $eloquent->name,
                            ],
                        ],
                    ];
                }
                return $array;
            })
            ->make(true);
    }

    public function submit($id = null)
    {
        try {
            if ($id != null) {
                $client = OAuthClient::find($id);
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
                $message = 'Successfully Create Module';
            } else {
                $client->name = request()->input('nama');
                $client->redirect = request()->input('redirect');
                $client->save();
                $message = 'Successfully Update Module';
            }

            DB::connection('mysql')->commit();
            Log::info(json_encode(["message" => $message, "auth_user" => Auth()->user()->toArray(), "data" => request()->all()]));
            return $this->responseRedirect('oclient', $message, 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            Log::info(json_encode(["message" => $e, "auth_user" => Auth()->user()->toArray()]));
            return $this->error_handler($e);
        }
    }

    public function destroy($id = null)
    {
        try {
            // validate  
            $oauth = OAuthClient::findOrFail($id);
            // 
            DB::connection('mysql')->beginTransaction();

            $oauth->delete();
            $message = 'Successfully Delete Oauth Client';

            DB::connection('mysql')->commit();
            Log::info(json_encode(["message" => $message, "auth_user" => Auth()->user()->toArray(), "data" => $oauth]));
            return $this->responseRedirect('oclient', $message, 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            Log::info(json_encode(["message" => $e, "auth_user" => Auth()->user()->toArray()]));
            return $this->error_handler($e);
        }
    }
}
