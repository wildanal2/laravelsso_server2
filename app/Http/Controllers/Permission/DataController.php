<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class DataController extends Controller
{

    public function get()
    {
        $query = Permission::all();
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
                    'url' => url('permission/' . $eloquent->id . '/detail'),
                    'className' => 'btn-light',
                    'text' => 'Detail',
                    'fontawesome' => 'lni lni-eye',
                ];
                $array[] = [
                    'url' => url('permission/' . $eloquent->id . '/edit'),
                    'className' => 'btn-light',
                    'text' => 'Edit',
                    'fontawesome' => 'lni lni-cogs',
                ];
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
            }else{
                $permission->name = request()->input('nama');
                $permission->save();

                $message = 'Successfully Update Permission';
            }

            DB::connection('mysql')->commit();
            return $this->responseRedirect('permission', $message, 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            return $this->error_handler($e);
        }
    }
}
