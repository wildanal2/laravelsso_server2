<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function error_handler($e)
    {
        if (1) { # if not production
            throw $e;
        }
        return $this->responseBack("Terjadi Kesalahan Server", 500);
    }

    public function responseJson($message, $array, $code)
    {
        return response()->json([
            'data' => $array,
            'message' => $message,
        ], $code);
    }

    public function responseBack($message, $code)
    {
        if (request()->ajax()) {
            return response()->json([
                'message' => $message,
            ], $code);
        }
        return back()->with([
            'message' => $message,
            'code' => $code,
        ]);
    }

    public function responseRedirect($url, $message, $code)
    {
        if (request()->ajax()) {
            return response()->json([
                'message' => $message,
                'redirect' => url($url),
            ], $code);
        }
        return redirect($url)->with([
            'message' => $message,
            'code' => $code,
        ]);
    }
}
