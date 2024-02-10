<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use App\Models\SsoModule;
use App\Models\SsoModuleFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\ClientRepository;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class DataController extends Controller
{

    public function get()
    {
        $query = SsoModule::all();

        return DataTables::of($query)
            ->addColumn('revoked', function ($eloquent) {
                return $eloquent->oClient->revoked ?? false;
            })
            ->addColumn('buttons', function ($eloquent) {
                $array = [];
                $user = auth()->user();
                $array[] = [
                    'url' => url('modules/' . $eloquent->id . '/detail'),
                    'className' => 'btn-outline-secondary',
                    'text' => 'Detail',
                    'fontawesome' => 'lni lni-eye',
                ];
                $array[] = [
                    'url' => url('modules/' . $eloquent->id . '/edit'),
                    'className' => 'btn-outline-secondary',
                    'text' => 'Edit',
                    'fontawesome' => 'lni lni-cogs',
                ];
                if ($user->hasPermissionTo('sso.manage-modules')) {
                    $array[] = [
                        'url' => 'javascript:void(0)',
                        'className' => 'btn-outline-secondary btn-module-destroy',
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

    public function submit($module_id = null)
    {
        $except = '';
        try {
            if ($module_id != null) {
                $module = SsoModule::find($module_id);
                $except = $module_id ? ',' . $module_id : '';
            }
            // validate
            request()->validate([
                'name' => 'required|string|max:255',
                'codename' => 'required|max:255|unique:sso_modules,codename' . $except,
                'url' => 'nullable|string|max:1000',
                'callback' => 'required|string|max:1000',
                'description' => 'nullable|string|max:1000',
                'last_version' => 'nullable|string|max:50',
                'revoked' => 'nullable',
            ]);
            //
            $data = request()->all();

            $data['last_version'] = request()->input('last_version') ?? '1.0';
            DB::connection('mysql')->beginTransaction();

            if (!isset($module)) {
                // Simpan data module
                $module = SsoModule::create($data);
                // make Oauth Client
                // 1st param is the user_id - none for client credentials
                // 2nd param is the client name
                // 3rd param is the redirect URI - none for client credentials
                $clients = App::make(ClientRepository::class);
                $client_name = request()->input('name');
                $uri = request()->input('callback');
                $client = $clients->create(null, $client_name, $uri);

                $module->oclient_id = $client->id;
                $module->save();

                $message = 'Successfully Create Module';
            } else {
                $module->update($data);
                // update Oclient
                $is_revoked = request()->input('revoked');
                $client = OAuthClient::find($module->oclient_id);
                $client->revoked = isset($is_revoked) && $is_revoked == 'on' ? true : false;
                $client->save();

                $message = 'Successfully Update Module';
            }

            DB::connection('mysql')->commit();
            Log::info(json_encode(["message" => $message, "auth_user" => Auth()->user()->toArray(), "data" => $data]));
            return $this->responseRedirect('modules', $message, 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            Log::info(json_encode(["message" => $e, "auth_user" => Auth()->user()->toArray()]));
            return $this->error_handler($e);
        }
    }

    public function submitFeature($module_id = null)
    {
        try {
            // validate
            request()->validate([
                'id' => 'required|exists:sso_modules,id',
                'data' => 'required|max:1000',
            ]);

            // 
            DB::connection('mysql')->beginTransaction();
            $module_id = request()->input('id');

            // remove old module_feature and forgn permission 
            SsoModuleFeature::where('module_id', $module_id)->delete();
            // refresh chache
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            // creating
            $listFeat = request()->input('data') ?? [];
            foreach ($listFeat as $key => $value) {
                $listPermission = $value['permission'] ?? [];
                foreach ($listPermission as $key => $data) {
                    $permission = Permission::where(['name' => $data])->get()->first();
                    if (!$permission && $data != '') {
                        $permission = Permission::create(['name' => $data]);
                    }

                    SsoModuleFeature::create(['module_id' => $module_id, 'permission_id' => $permission?->id ?? null, 'name' => $value['name']]);
                }
            }
            $message = 'Successfully Update Feature Module';

            DB::connection('mysql')->commit();
            Log::info(json_encode(["message" => $message, "auth_user" => Auth()->user()->toArray(), "data" => $data]));
            return $this->responseRedirect('modules', $message, 200);
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
            DB::connection('mysql')->beginTransaction();
            $module = SsoModule::findOrFail($id);
            $oauth = OAuthClient::findOrFail($module->oclient_id);

            $oauth->delete();
            $module->delete();

            $message = 'Successfully Delete Module';

            DB::connection('mysql')->commit();
            Log::info(json_encode(["message" => $message, "auth_user" => Auth()->user()->toArray(), "data" => $module]));
            return $this->responseRedirect('entity', $message, 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            Log::info(json_encode(["message" => $e, "auth_user" => Auth()->user()->toArray()]));
            return $this->error_handler($e);
        }
    }

    public function select2()
    {
        $term = request()->term;
        $limit = request()->limit ?? 50;
        $page = request()->page ?? 1;

        $query = SsoModule::with([])
            ->where('name', 'like', '%' . $term . '%')
            ->orderByRaw("INSTR('%" . $term . "%', name) DESC, LENGTH(name) ASC")
            ->offset(($page - 1) * $limit)
            ->limit($limit);

        $data = $query->get();

        $results = $data->map(function ($item) {
            return (object) [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });
        return json_encode([
            'results' => $results,
            'pagination' => (object) [
                'more' => $results->count() == $limit,
            ]
        ]);
    }
}
