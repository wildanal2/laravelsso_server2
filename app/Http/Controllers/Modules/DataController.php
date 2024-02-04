<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use App\Models\SsoModule;
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
        $query = SsoModule::all();
        // dd($query);
        return DataTables::of($query)
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
                return $array;
            })
            ->make(true);
    }

    public function submit($module_id = null)
    {
        $except='';
        try {
            if ($module_id != null) {
                $module = SsoModule::find($module_id);
                $except = $module_id ? ',' . $module_id : '';
            }
            // validate
            request()->validate([
                'name' => 'required|string|max:255',
                'codename' => 'required|max:255|unique:sso_modules,codename' . $except,
                'description' => 'nullable|string|max:1000',
                'last_version' => 'nullable|string|max:50',
            ]);
            // 
            $data = request()->all();
            
            $data['last_version'] = request()->input('last_version') ?? '1.0';
            DB::connection('mysql')->beginTransaction();

            if (!isset($module)) {

                // Simpan data module
                SsoModule::create($data);

                $message = 'Successfully Create Module';
            } else {
                $module->update($data);

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
}
