<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Program;
use App\Models\Notifications;
use App\Models\Project;
use App\Models\UserNotification;
// use App\Models\SystemLog;
use Illuminate\Support\Facades\DB;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.app', function ($view) {
            $segment = [
                1 => request()->segment(1),
                2 => request()->segment(2),
                3 => request()->segment(3),
            ]; 
            $menu = [
                [
                    'text' => 'Dashboard',
                    'icon' => 'bi bi-house-fill',
                    'url' => route('home'),
                    'active' => $segment[1] == null || $segment[1] == 'home',
                ],
                [
                    'text' => 'Menu',
                    'permission' => ['manage-entitas', 'view-user', 'manage-role', 'manage-permission'],
                ],
                [
                    'text' => 'Users',
                    'icon' => 'bx bx-user',
                    'permission' => ['user.list'],
                    'url' => route('users'),
                    'active' => $segment[1] == 'users',
                ],
                [
                    'text' => 'Role',
                    'icon' => 'bx bxs-user-account',
                    'permission' => ['user.list'],
                    'url' => route('roles'),
                    'active' => $segment[1] == 'roles',
                ],
                [
                    'text' => 'Permission',
                    'icon' => 'bi bi-person-lines-fill',
                    'permission' => ['permission.list'],
                    'url' => route('permission'),
                    'active' => $segment[1] == 'permission',
                ],
                [
                    'text' => 'Others',
                    'permission' => ['manage-entitas', 'view-user', 'manage-role', 'manage-permission'],
                ],  
                [
                    'text' => 'Module',
                    'icon' => 'bx bx-vector',
                    'permission' => ['module.list'],
                    'url' => route('module'),
                    'active' => $segment[1] == 'modules',
                ],
                [
                    'text' => 'Entity',
                    'icon' => 'bx bx-buildings',
                    'permission' => ['entity.list'],
                    'url' => route('entity'),
                    'active' => $segment[1] == 'entity',
                ],
                [
                    'text' => 'OAuth Client',
                    'icon' => 'bx bx-network-chart',
                    'permission' => ['oclient.list'],
                    'url' => route('oclient'),
                    'active' => $segment[1] == 'oclient',
                ],
                [
                    'text' => "Logs",
                    'icon' => 'lni lni-indent-increase',
                    'permission' => ['logs.view'],
                    'active' => $segment[1] == 'logss',
                    'dropdown' => [
                        [
                            'url' => route('logs.system'),
                            'text' => 'System Logs',
                            'active' => $segment[2] == 'system-logs',
                        ]
                    ],
                ],
            ];
            $menu = $this->filterMenu($menu);
            // $notif = $this->getNotification();
            // $log = $this->getLogAktivitas();
            $view->with('menu', $menu);
            // $view->with('notif', $notif);
            // $view->with('log', $log);
        });
    }

    public function getNotification()
    {
        // $date = date('Y-m-d', strtotime('+30 days'));

        // $notif_program = Program::where('tahun_selesai', '<=', $date)->where('tahun_selesai', '>=', date('Y-m-d'))
        //     ->get(['id', 'nama', 'status', 'uuid', 'tahun_selesai'])->toArray();

        // DB::connection('mysql')->beginTransaction();

        // $data = [];
        // $data2 = [];
        // foreach ($notif_program as $val) {
        //     $msg = 'Program ' . $val['nama'] . ' akan berakhir kurang dari 30 hari lagi';
        //     $notif = Notifications::where('pesan', $msg)->first();
        //     if (empty($notif)) {
        //         $new_notif = Notifications::create([
        //             'message' => $val['nama'] . ' Program will end in less than 30 days',
        //             'pesan' => $msg,
        //             'type' => 'program',
        //             'url' => 'program/' . $val['uuid'],
        //         ]);

        //         $diff = date_diff(date_create($new_notif->created_at), date_create(date('Y-m-d H:i:s')));
        //         if ($diff->d > 1) {
        //             $diff = $diff->d;
        //             $time = session('lng') == 'in' ? $diff . ' hari yang lalu' : $diff . ' days ago';
        //         } else if ($diff->h > 1) {
        //             $diff = $diff->h;
        //             $time = session('lng') == 'in' ? $diff . ' jam yang lalu' : $diff . ' hours ago';
        //         } else {
        //             $diff = $diff->i;
        //             $time = session('lng') == 'in' ? $diff . ' menit yang lalu' : $diff . ' minutes ago';
        //         }

        //         $data[] = [
        //             'id' => $new_notif->id,
        //             'message' => session('lng') == 'in' ? $new_notif->pesan : $new_notif->message,
        //             'type' => $new_notif->type,
        //             'url' => $new_notif->url,
        //             'time' => $time,
        //             'is_read' => 0
        //         ];
        //     } else {

        //         $diff = date_diff(date_create($notif->created_at), date_create(date('Y-m-d H:i:s')));
        //         if ($diff->d > 1) {
        //             $diff = $diff->d;
        //             $time = session('lng') == 'in' ? $diff . ' hari yang lalu' : $diff . ' days ago';
        //         } else if ($diff->h > 1) {
        //             $diff = $diff->h;
        //             $time = session('lng') == 'in' ? $diff . ' jam yang lalu' : $diff . ' hours ago';
        //         } else {
        //             $diff = $diff->i;
        //             $time = session('lng') == 'in' ? $diff . ' menit yang lalu' : $diff . ' minutes ago';
        //         }

        //         $cek_user_notif = UserNotification::where('user_id', auth()->id())->where('notifications_id', $notif->id)->count();
        //         if ($cek_user_notif > 0) {
        //             $data2[] = [
        //                 'id' => $notif->id,
        //                 'message' => session('lng') == 'in' ? $notif->pesan : $notif->message,
        //                 'type' => $notif->type,
        //                 'url' => $notif->url,
        //                 'time' => $time,
        //                 'is_read' => 1
        //             ];
        //         } else {
        //             $data[] = [
        //                 'id' => $notif->id,
        //                 'message' => session('lng') == 'in' ? $notif->pesan : $notif->message,
        //                 'type' => $notif->type,
        //                 'url' => $notif->url,
        //                 'time' => $time,
        //                 'is_read' => 0
        //             ];
        //         }
        //     }
        // }

        // $date = date('Y-m-d', strtotime('+7 days'));
        // $notif_project = Project::with(['program'])->where('tanggal_mulai', '<=', $date)->where('tanggal_mulai', '>=', date('Y-m-d'))
        //     ->get(['id', 'program_id', 'nama', 'tanggal_mulai', 'uuid', 'status'])->toArray();
        // foreach ($notif_project as $val) {

        //     $msg = 'Project ' . $val['nama'] . ' akan dimulai kurang dari 7 hari lagi';
        //     $notif = Notifications::where('pesan', $msg)->first();
        //     if (empty($notif)) {
        //         $new_notif = Notifications::create([
        //             'message' => $val['nama'] . ' Activity will start in less than 7 days',
        //             'pesan' => $msg,
        //             'type' => 'project',
        //             'url' => 'program/' . $val['program']['uuid'] . '/project/' . $val['uuid'],
        //         ]);


        //         $diff = date_diff(date_create($new_notif->created_at), date_create(date('Y-m-d H:i:s')));
        //         if ($diff->d > 1) {
        //             $diff = $diff->d;
        //             $time = session('lng') == 'in' ? $diff . ' hari yang lalu' : $diff . ' days ago';
        //         } else if ($diff->h > 1) {
        //             $diff = $diff->h;
        //             $time = session('lng') == 'in' ? $diff . ' jam yang lalu' : $diff . ' hours ago';
        //         } else {
        //             $diff = $diff->i;
        //             $time = session('lng') == 'in' ? $diff . ' menit yang lalu' : $diff . ' minutes ago';
        //         }

        //         $data[] = [
        //             'id' => $new_notif->id,
        //             'message' => session('lng') == 'in' ? $new_notif->pesan : $new_notif->message,
        //             'type' => $new_notif->type,
        //             'url' => $new_notif->url,
        //             'time' => $time,
        //             'is_read' => 0
        //         ];
        //     } else {

        //         $diff = date_diff(date_create($notif->created_at), date_create(date('Y-m-d H:i:s')));
        //         if ($diff->d > 1) {
        //             $diff = $diff->d;
        //             $time = session('lng') == 'in' ? $diff . ' hari yang lalu' : $diff . ' days ago';
        //         } else if ($diff->h > 1) {
        //             $diff = $diff->h;
        //             $time = session('lng') == 'in' ? $diff . ' jam yang lalu' : $diff . ' hours ago';
        //         } else {
        //             $diff = $diff->i;
        //             $time = session('lng') == 'in' ? $diff . ' menit yang lalu' : $diff . ' minutes ago';
        //         }

        //         $cek_user_notif = UserNotification::where('user_id', auth()->id())->where('notifications_id', $notif->id)->count();
        //         if ($cek_user_notif > 0) {
        //             $data2[] = [
        //                 'id' => $notif->id,
        //                 'message' => session('lng') == 'in' ? $notif->pesan : $notif->message,
        //                 'type' => $notif->type,
        //                 'url' => $notif->url,
        //                 'time' => $time,
        //                 'is_read' => 1
        //             ];
        //         } else {
        //             $data[] = [
        //                 'id' => $notif->id,
        //                 'message' => session('lng') == 'in' ? $notif->pesan : $notif->message,
        //                 'type' => $notif->type,
        //                 'url' => $notif->url,
        //                 'time' => $time,
        //                 'is_read' => 0
        //             ];
        //         }
        //     }
        // }

        // DB::connection('mysql')->commit();
        // return [$data, $data2];
    }

    public function getLogAktivitas($limit = 100)
    {
        // $log = SystemLog::with(['user'])->limit($limit)->orderBy('id', 'desc')->get()->toArray();
        $log = [];
        return $log;
    }

    private function filterMenu($menu)
    {

        $result = [];
        foreach ($menu as $item) {
            if (!($item['show'] ?? true)) continue;
            // if (isset($item['permission']) && !auth()->user()->canany($item['permission'])) continue;


            if (isset($item['dropdown'])) {
                $temp = [];
                foreach ($item['dropdown'] as $val) {
                    if (isset($val['permission']) && !auth()->user()->canany($val['permission'])) {
                    } else {
                        $temp[] = $val;
                    }
                }
                $item['dropdown'] = $temp;
            }

            $result[] = $item;
        }

        return $result;
    }
}
