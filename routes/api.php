<?php

use Illuminate\Http\Request;
use App\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', function (Request $request) {
    $credentials = $request->only(['email', 'password']);

    try {
        if (!$token = \Tymon\JWTAuth\JWTAuth::attempt($credentials)) {
            return response()->json([
                'error' => 'invalid credentials'
            ], 400);
        }
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response(['error' => 'could not create token'], 500);
    }

    return response(['msg' => 'logged in'], 200)->header('Authorization', $token);
});
Route::middleware('jwt.refresh')->get('refresh', function () {
    return response(['msg' => 'refresh ok'], 200);
});
Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('logout', function () {
        \Tymon\JWTAuth\JWTAuth::invalidate();

        return response(['msg' => 'logged out'], 200);
    });
    Route::get('users', function () {
        return User::all();
    });

    Route::get('users/{id}', function ($id) {
        return User::findOrFail($id);
    });

    Route::post('users', function (Request $request) {
        return User::create($request->all());
    });

    Route::put('users/{id}', function (Request $request, $id) {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return $user;
    });

    Route::delete('users/{id}', function ($id) {
        $user = User::findOrFail($id);

        $user->delete();

        return 204;
    });
});