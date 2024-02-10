<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use App\Models\SsoModuleFeature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\ClientRepository;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class DataController extends Controller
{

    public function get()
    {
        $query = Permission::all();

        return DataTables::of($query)
            ->addColumn('modul_feature', function ($eloquent) {
                $dat = SsoModuleFeature::where('id', $eloquent->feature_id)->first();
                return $dat ? $dat->name : '';
            })
            ->addColumn('buttons', function ($eloquent) {
                $array = [];
                $user = auth()->user();
                $array[] = [
                    'url' => url('permission/' . $eloquent->id . '/detail'),
                    'className' => 'btn-outline-secondary',
                    'text' => 'Detail',
                    'fontawesome' => 'lni lni-eye',
                ];
                $array[] = [
                    'url' => url('permission/' . $eloquent->id . '/edit'),
                    'className' => 'btn-outline-secondary',
                    'text' => 'Edit',
                    'fontawesome' => 'lni lni-cogs',
                ];
                if ($user->hasPermissionTo('sso.manage-permissions')) {
                    $array[] = [
                        'url' => 'javascript:void(0)',
                        'className' => 'btn-outline-secondary btn-permission-destroy',
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

    public function submit($prmiss_id = null)
    {
        try {
            if ($prmiss_id != null) {
                $permission = Permission::find($prmiss_id);
            }
            // validate
            request()->validate([
                'nama' => 'required|max:255',
                'assign_to' => 'nullable',
            ]);

            // 
            DB::connection('mysql')->beginTransaction();

            if (!isset($permission)) {
                $permission_name = request()->input('nama');
                $assign_to = request()->input('assign_to') ?? [];

                // create permission
                $permission = Permission::create(['name' => $permission_name]);
                foreach ($assign_to as $value) {
                    $role = Role::find($value);
                    $permission->assignRole($role);
                }

                $message = 'Successfully Create Permission';
            } else {
                $permission->name = request()->input('nama');
                $permission->save();

                $message = 'Successfully Update Permission';
            }

            DB::connection('mysql')->commit();
            Log::info(json_encode(["message" => $message, "auth_user" => Auth()->user()->toArray(), "data" => request()->all()]));
            return $this->responseRedirect('permission', $message, 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            Log::info(json_encode(["message" => $e, "auth_user" => Auth()->user()->toArray()]));
            return $this->error_handler($e);
        }
    }

    public function destroy($permission_id = null)
    {
        try {
            // validate  
            $permission = Permission::findOrFail($permission_id);
            // 
            DB::connection('mysql')->beginTransaction();

            $permission->delete();
            $message = 'Successfully Delete Permission';

            DB::connection('mysql')->commit();
            Log::info(json_encode(["message" => $message, "auth_user" => Auth()->user()->toArray(), "data" => $permission]));
            return $this->responseRedirect('permission', $message, 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            Log::info(json_encode(["message" => $e, "auth_user" => Auth()->user()->toArray()]));
            return $this->error_handler($e);
        }
    }
}
