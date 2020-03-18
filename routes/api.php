<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'Api\\UserController@store');
Route::post('auth/login', 'Api\\AuthController@login');

Route::get('tags', function (Request $request) {
    $tags = App\Models\Tag::inRandomOrder()->limit(rand(1, 10))->get();
    return $tags;
});

Route::get('movies', function (Request $request) {
    $collection = App\Models\Movie::with(['tags'])->get();

    return $collection;
});

Route::group(['middleware' => ['api.jwt']], function () {
    Route::post('logout', 'Api\\AuthController@logout');
    Route::post('refresh', 'Api\\AuthController@refresh');
    Route::post('me', 'Api\\AuthController@me');

    Route::resource('users', 'Api\\UserController');


    Route::get('movies/sort/{order?}', 'Api\\MovieController@index');
    Route::apiResource('movies', 'Api\\MovieController');

    Route::apiResource('tags', 'Api\\TagController');

});
