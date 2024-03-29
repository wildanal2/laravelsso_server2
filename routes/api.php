<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/ 

Route::middleware(['auth:api'])->get('/user-detail', function (Request $request) {
    $query = User::with(['hasEntities', 'hasModule', 'permissions', 'roles'])->findOrFail($request->user()->id);

    return $query->toArray();
});
Route::middleware(['auth:api'])->get('/logmeout', function (Request $request) {
    $user = $request->user();
    $accessToken = $user->token();
    DB::table('oauth_refresh_tokens')->where('access_token_id', $accessToken->id)->delete();

    $accessToken->delete();
    return response()->json([
        'message' => "Revoked"
    ]);
});
