<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
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


Route::group(['prefix'=>'v1'], function () {

        Route::post('/signup', [AuthController::class, 'signup']);
        Route::post('/signin', [AuthController::class, 'signin']);

  Route::group(['middleware' => 'auth:api'], function () {
//------------------------admin----------------------------------
        Route::post('/post-create', [AdminController::class, 'post_create']);
        Route::get('/post-lists', [AdminController::class, 'post_lists']);
        Route::get('/single-post-{post_id}', [AdminController::class, 'single_post']);
        Route::delete('/single-post-delete-{post_id}', [AdminController::class, 'single_post_delete']);
        Route::put('/single-post-update-{post_id}', [AdminController::class, 'single_post_update']);
//------------------------admin----------------------------------

//------------------------user----------------------------------
        Route::get('/user-job-post-lists', [UserController::class, 'job_post_lists']);
        Route::get('/user-single-post-{post_id}', [AdminController::class, 'single_post']);
//------------------------user----------------------------------
        Route::post('/logout',[AuthController::class,'logout']);
   });
});

Route::fallback(function () {
    return response(['success' => false, 'message' => 'Invalid Url Or root not found'], 404);
});
