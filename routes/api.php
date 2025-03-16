<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\usercontroller;
use App\Http\Controllers\test;
use App\Http\Controllers\LinkController;





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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//user register and login 
Route::post('userRegister',[usercontroller::class,'userRegister']);
Route::post('userLogin',[usercontroller::class,'userLogin']);
Route::post('userLogout',[usercontroller::class,'userLogout']);
Route::get('show-user',[usercontroller::class,'show']); //by username
Route::get('show-all-users',[usercontroller::class,'showall']);
Route::post('userupdate',[usercontroller::class,'userupdate']);
Route::post('uploadAvatar',[usercontroller::class,'uploadAvatar']);
Route::get('show-avatar',[usercontroller::class,'showpic']);
Route::post('test',[usercontroller::class,'saveavatar']);
Route::post('store-avatar',[usercontroller::class,'saveavatar']);
Route::post('upload-background',[usercontroller::class,'savebackground']);







Route::post('link',[LinkController::class,'store']);
Route::delete('delete-link',[LinkController::class,'delete']);
Route::get('show-link',[LinkController::class,'show']);  //BY LINK ID AND ITS USER OWNER
Route::get('show-all-links',[LinkController::class,'show_all']);
Route::post('link-update',[LinkController::class,'update']);
Route::get('show_link',[LinkController::class,'show_link']);





