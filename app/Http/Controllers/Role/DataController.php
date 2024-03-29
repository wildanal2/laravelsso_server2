<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
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
        $query = Role::with(['permissions']);
        
        return DataTables::of($query)
            ->addColumn('permission', function ($eloquent) {
                $permission_list = $eloquent->permissions->pluck('name');

                return $permission_list->implode(', ');
            })
            ->addColumn('entity', function ($eloquent) {
                return '';
            })
            ->addColumn('buttons', function ($eloquent) {
                $array = [];
                $user = auth()->user();
                $array[] = [
                    'url' => url('roles/' . $eloquent->id . '/detail'),
                    'className' => 'btn-outline-secondary',
                    'text' => 'Detail',
                    'fontawesome' => 'lni lni-eye',
                ];
                $array[] = [
                    'url' => url('roles/' . $eloquent->id . '/edit'),
                    'className' => 'btn-outline-secondary',
                    'text' => 'Edit',
                    'fontawesome' => 'lni lni-cogs',
                ];
                if ($user->hasPermissionTo('sso.manage-roles')) {
                    $array[] = [
                        'url' => 'javascript:void(0)',
                        'className' => 'btn-outline-secondary btn-roles-destroy',
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

    public function submit($role_id = null)
    {
        try {
            if ($role_id != null) {
                $role = Role::find($role_id);
            }
            // validate
            request()->validate([
                'nama' => 'required|max:255',
                'permissions' => 'nullable',
            ]);
            // 
            DB::connection('mysql')->beginTransaction();

            if (!isset($role)) {
                $role_name = request()->input('nama');
                $role = Role::create(['name' => $role_name]);
                $message = 'Successfully Create Role';
            } else {
                $role->name = request()->input('nama');
                $role->save();
                $message = 'Successfully Update Role';
            }

            // syncPermissions
            $permissions = Permission::whereIn('id', request()->input('permissions') ?? [])->get();
            $role->syncPermissions($permissions->pluck('name'));

            DB::connection('mysql')->commit();
            Log::info(json_encode(["message" => $message, "auth_user" => Auth()->user()->toArray(), "data" => request()->all()]));
            return $this->responseRedirect('roles', $message, 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            Log::info(json_encode(["message" => $e, "auth_user" => Auth()->user()->toArray()]));
            return $this->error_handler($e);
        }
    }

    public function destroy($role_id = null)
    {
        try {
            // validate  
            $role = Role::findOrFail($role_id);
            // 
            DB::connection('mysql')->beginTransaction();

            $role->delete();
            $message = 'Successfully Delete Role';

            DB::connection('mysql')->commit();
            Log::info(json_encode(["message" => $message, "auth_user" => Auth()->user()->toArray(), "data" => $role]));
            return $this->responseRedirect('roles', $message, 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            Log::info(json_encode(["message" => $e, "auth_user" => Auth()->user()->toArray()]));
            return $this->error_handler($e);
        }
    }
}
