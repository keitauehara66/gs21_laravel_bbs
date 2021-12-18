<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// 以下の三行を無効に
// Route::get('/', function () {
//     return view('welcome');
// });

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'PostController@index')->name('posts.index');
Route::get('/posts/search', 'PostController@search')->name('posts.search');

Route::resource('/posts', 'PostController', ['except' => ['index']]);
Route::resource('/users', 'UserController');
Route::resource('/categories', 'CategoryController');
Route::resource('/comments', 'CommentController')->middleware('auth');

Route::post('create', [\App\Http\Controllers\PostController::class, 'store']);

// Camera Capture
Route::get('camera_capture', [\App\Http\Controllers\CameraCaptureController::class, 'create']);
Route::post('camera_capture', [\App\Http\Controllers\CameraCaptureController::class, 'store']);