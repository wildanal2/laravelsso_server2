<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class DataController extends Controller
{

    public function get()
    {
        $query = Role::with(['permissions']);
        // dd($query);
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
                // if ($user->hasPermissionTo('edit-user')) {
                //     $array[] = [
                //         'url' => url('users/' . $eloquent->id . '/edit'),
                //         'className' => 'btn-success',
                //         'text' => 'Edit',
                //         'fontawesome' => 'fa-user-cog',
                //     ];
                // } 
                $array[] = [
                    'url' => url('roles/' . $eloquent->id . '/detail'),
                    'className' => 'btn-light',
                    'text' => 'Detail',
                    'fontawesome' => 'lni lni-eye',
                ];
                $array[] = [
                    'url' => url('roles/' . $eloquent->id . '/edit'),
                    'className' => 'btn-light',
                    'text' => 'Edit',
                    'fontawesome' => 'lni lni-cogs',
                ];
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
            ]);
            // 
            DB::connection('mysql')->beginTransaction();

            if (!isset($role)) {
                $role_name = request()->input('nama');
                $role = Role::create(['name' => $role_name ]);
                $message = 'Successfully Create Role';
            }else{
                $role->name = request()->input('nama');
                $role->save();
                $message = 'Successfully Update Role';
            }

            DB::connection('mysql')->commit();
            return $this->responseRedirect('roles', $message, 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            return $this->error_handler($e);
        }
    }
}
