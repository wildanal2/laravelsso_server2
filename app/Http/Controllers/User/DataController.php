<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use App\Models\SsoUserHasEntity;
use App\Models\SsoUserHasModule;
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
        $user = auth()->user();
        if ($user->hasRole('sso.super-admin')) {
            $query = User::with(['hasEntities'])->withTrashed()->get();
        } else {
            $query = User::with(['hasEntities'])->get();
        }

        return DataTables::of($query)
            ->addColumn('role', function ($eloquent) {
                return $eloquent->roles->pluck('name')->implode(', ');
            })
            ->addColumn('entity', function ($eloquent) {
                $entity = $eloquent->hasEntities->pluck('name');

                return $entity->implode(', ');
            })
            ->addColumn('module', function ($eloquent) {
                $entity = $eloquent->hasModule->pluck('name');
                return $entity->implode(', ');
            })
            ->addColumn('status', function ($eloquent) {
                $array[] = $eloquent->status;
                return $array;
            })
            ->addColumn('buttons', function ($eloquent) {
                $array = [];
                $user = auth()->user();
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
                if ($user->hasPermissionTo('sso.manage-users')) {
                    if ($eloquent->deleted_at) {
                        $array[] = [
                            'url' => 'javascript:void(0)',
                            'className' => 'btn-outline-success btn-user-restore',
                            'text' => 'Restore',
                            'fontawesome' => 'bx bx-revision',
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
                    } else {
                        $array[] = [
                            'url' => 'javascript:void(0)',
                            'className' => 'btn-outline-secondary btn-user-destroy',
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
                }
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
                'new-password' => [
                    $user_id ? 'nullable' : 'required',
                    'string',
                    'max:255'
                ],
                're-password' => 'nullable|string|max:255',
                'entity' => 'nullable',
                'role' => 'required',
                'module' => 'nullable',
            ]);

            // IF Update Password
            $new_pass = request()->input('new-password');
            $re_pass = request()->input('re-password');
            if (isset($new_pass) && $new_pass != $re_pass) {
                throw ValidationException::withMessages(['re-password' => 'Re-password is not Match']);
            }
            // IF Set Active || Reset Login Attempt
            $is_active = request()->input('is_active');
            if (isset($is_active)) {
                $data['failed_try'] = 0;
            }
            // 
            $data = request()->except(['new-password', 're-password', 'entity']);
            // 
            DB::connection('mysql')->beginTransaction();

            if (!isset($user)) {
                $data['password'] = request()->input('password') ?? '';
                $user = User::create($data);

                $message = 'Successfully Create User';
            } else {
                if (isset($new_pass)) $data['password'] = $new_pass;
                $user->update($data);

                $message = 'Successfully Update User';
            }

            // Update User Has Entities
            SsoUserHasEntity::where('user_id', $user->id)->delete();
            $new_entt = request()->input('entity') ?? [];
            foreach ($new_entt as $value) {
                SsoUserHasEntity::create(['user_id' => $user->id, 'entity_id' => $value]);
            }
            // Update User Has Module
            SsoUserHasModule::where('user_id', $user->id)->delete();
            $new_module = request()->input('module') ?? [];
            foreach ($new_module as $value) {
                SsoUserHasModule::create(['user_id' => $user->id, 'module_id' => $value]);
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

    public function resetAttempt()
    {
        try {
            // validate
            request()->validate([
                'id' => 'required|exists:users,id',
                'reset' => 'required',
                'active' => 'nullable',
            ]);
            //
            DB::connection('mysql')->beginTransaction();
            $user = User::find(request()->input('id'));
            $active = request()->input('active');
            if (isset($active)) {
                $user->update([
                    'failed_try' => 0,
                    'is_active' => 1
                ]);
            } else {
                $user->update([
                    'failed_try' => 0,
                ]);
            }
            $message = 'Successfully reset login attempt User';

            DB::connection('mysql')->commit();
            Log::info(json_encode(["message" => $message, "auth_user" => Auth()->user()->toArray(), "data" => $user]));
            return $this->responseRedirect('users', $message, 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            Log::info(json_encode(["message" => $e, "auth_user" => Auth()->user()->toArray()]));
            return $this->error_handler($e);
        }
    }

    public function destroy($user_id = null)
    {
        try {
            // validate  
            $user = User::findOrFail($user_id);
            // 
            DB::connection('mysql')->beginTransaction();

            $user->is_active = -2;
            $user->save();
            $user->delete();
            $message = 'Successfully Delete Account';

            DB::connection('mysql')->commit();
            Log::info(json_encode(["message" => $message, "auth_user" => Auth()->user()->toArray(), "data" => $user]));
            return $this->responseRedirect('users', $message, 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            Log::info(json_encode(["message" => $e, "auth_user" => Auth()->user()->toArray()]));
            return $this->error_handler($e);
        }
    }

    public function restore($id)
    {
        try { 
            // 
            DB::connection('mysql')->beginTransaction();

            $user = User::withTrashed()->findOrFail($id);
            $user->restore();
            $user->is_active = 1;
            $user->save();
            $message = 'Successfully Restore Account';

            DB::connection('mysql')->commit();
            Log::info(json_encode(["message" => $message, "auth_user" => Auth()->user()->toArray(), "data" => $user]));
            return $this->responseRedirect('users', $message, 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            Log::info(json_encode(["message" => $e, "auth_user" => Auth()->user()->toArray()]));
            return $this->error_handler($e);
        }
    }
}
