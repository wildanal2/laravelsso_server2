<?php

namespace App\Http\Controllers\Entity;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use App\Models\SsoEntity;
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
        $query = SsoEntity::all();
        
        return DataTables::of($query)
            ->addColumn('secret', function ($eloquent) {
                return '**********';
            })
            ->addColumn('buttons', function ($eloquent) {
                $array = [];
                $user = auth()->user();
                $array[] = [
                    'url' => url('entity/' . $eloquent->id . '/detail'),
                    'className' => 'btn-outline-secondary',
                    'text' => 'Detail',
                    'fontawesome' => 'lni lni-eye',
                ];
                $array[] = [
                    'url' => url('entity/' . $eloquent->id . '/edit'),
                    'className' => 'btn-outline-secondary',
                    'text' => 'Edit',
                    'fontawesome' => 'lni lni-cogs',
                ];
                if ($user->hasPermissionTo('sso.manage-entity')) {
                    $array[] = [
                        'url' => 'javascript:void(0)',
                        'className' => 'btn-outline-secondary btn-entity-destroy',
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

    public function submit($entity_id = null)
    {
        $except = '';
        try {
            if ($entity_id != null) {
                $entity = SsoEntity::find($entity_id);
                $except = $entity_id ? ',' . $entity_id : '';
            }

            // validate
            request()->validate([
                'company_reg' => 'required|string|max:255|unique:sso_entities,company_reg' . $except,
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Validasi file gambar
            ]);
            //
            $data = request()->except('logo');

            // Menyimpan file logo jika diunggah
            if (request()->hasFile('logo')) {
                $logoPath = request()->file('logo')->storeAs('uploads/logo_entity', uniqid() . '.' . request()->file('logo')->getClientOriginalExtension(), 'public');
                $data['logo'] = $logoPath;
            }
            // 
            DB::connection('mysql')->beginTransaction();

            if (!isset($entity)) {
                SsoEntity::create($data);

                $message = 'Successfully Create Entity';
            } else {
                $entity->update($data);

                $message = 'Successfully Update Entity';
            }

            DB::connection('mysql')->commit();
            Log::info(json_encode(["message" => $message, "auth_user" => Auth()->user()->toArray(), "data" => $data]));
            return $this->responseRedirect('entity', $message, 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            Log::info(json_encode(["message" => $e, "auth_user" => Auth()->user()->toArray()]));
            return $this->error_handler($e);
        }
    }

    public function destroy($id = null)
    {
        try {
            DB::connection('mysql')->beginTransaction();
            // validate  
            $entity = SsoEntity::findOrFail($id); 

            $entity->delete();
            $message = 'Successfully Delete Entity';

            DB::connection('mysql')->commit();
            Log::info(json_encode(["message" => $message, "auth_user" => Auth()->user()->toArray(), "data" => $entity]));
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

        $query = SsoEntity::with([])
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
