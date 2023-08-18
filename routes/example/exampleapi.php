<?php

use App\Http\Controllers\api\AboutApiController;
use App\Http\Controllers\api\BannerApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ServeApiController;

use App\Http\Controllers\api\FakeImageApiController;
use App\Http\Controllers\api\ServeClassificationApiController;
use App\Http\Controllers\api\TeamMemberApiController;

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
Route::post('contact',function()
{
    return \App\Models\Contact::create(request()->all());
    // name phone back_body email
});
Route::apiResource('/serve-classification', ServeClassificationApiController::class, ['except' => ['create' , 'edit','update','destroy','store']]);
Route::apiResource('/banner', BannerApiController::class, ['except' => ['create' , 'edit','update','destroy','store']]);
Route::apiResource('/news', FakeImageApiController::class, ['except' => ['create' , 'edit','update','destroy','store']]);
Route::apiResource('/serve', ServeApiController::class, ['except' => ['create' , 'edit','update','destroy','store']]);
Route::apiResource('/teamMember', TeamMemberApiController::class, ['except' => ['create' , 'edit','update','destroy','store']]);
Route::get('/about/index',[AboutApiController::class,'index']);