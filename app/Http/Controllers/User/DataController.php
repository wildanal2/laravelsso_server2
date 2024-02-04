<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use App\Models\SsoUserHasEntity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\ClientRepository;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class DataController extends Controller
{

    public function get()
    {
        $query = User::with(['hasEntities'])->get();

        return DataTables::of($query)
            ->addColumn('role', function ($eloquent) {
                return $eloquent->roles->pluck('name')->implode(', ');
            })
            ->addColumn('entity', function ($eloquent) {
                $entity = $eloquent->hasEntities->pluck('name');

                return $entity->implode(', ');
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
                    'url' => url('users/' . $eloquent->id . '/detail'),
                    'className' => 'btn-outline-secondary',
                    'text' => 'Detail',
                    'fontawesome' => 'lni lni-eye',
                ];
                $array[] = [
                    'url' => url('users/' . $eloquent->id . '/edit'),
                    'className' => 'btn-outline-secondary',
                    'text' => 'Edit',
                    'fontawesome' => 'lni lni-cogs',
                ];
                return $array;
            })
            ->make(true);
    }

    public function submit($user_id = null)
    {
        $except = '';
        try {
            if ($user_id != null) {
                $user = User::find($user_id);
                $except = $user_id ? ',' . $user_id : '';
            }

            // validate
            request()->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email' . $except,
                'new-password' => 'nullable|string|max:255',
                're-password' => 'nullable|string|max:255',
                'entity' => 'nullable',
                'role' => 'required',
            ]);
            // 
            $data = request()->except(['new-password', 're-password', 'entity']);

            // IF Update Password
            $new_pass = request()->input('new-password');
            $re_pass = request()->input('re-password');
            if (isset($new_pass) && $new_pass != $re_pass) {
                throw ValidationException::withMessages(['re-password' => 'Re-password is not Match']);
            }
            // IF Set Active || Reset Login Attempt
            $is_active = request()->input('is_active');
            if(isset($is_active)){
                $data['failed_try'] = 0;
            }

            // 
            DB::connection('mysql')->beginTransaction();

            if (!isset($user)) {
                $user = User::create($data);

                $message = 'Successfully Create User';
            } else {
                if (isset($new_pass)) $data['password'] = $new_pass;
                $user->update($data);

                $message = 'Successfully Update User';
            } 

            // Update Entities
            SsoUserHasEntity::where('user_id', $user_id)->delete();
            $new_entt = request()->input('entity') ?? [];
            foreach ($new_entt as $value) {
                SsoUserHasEntity::create(['user_id' => $user_id, 'entity_id' => $value]);
            }
            // update role
            $role = Role::whereIn('id', $data['role'])->get();
            $user->syncRoles($role->pluck('name'));

            DB::connection('mysql')->commit();
            Log::info(json_encode(["message" => $message, "auth_user" => Auth()->user()->toArray(), "data" => $data]));
            return $this->responseRedirect('users', $message, 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            Log::info(json_encode(["message" => $e, "auth_user" => Auth()->user()->toArray()]));
            return $this->error_handler($e);
        }
    }
}
