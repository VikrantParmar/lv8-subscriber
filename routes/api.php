<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\SubscriberController;
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

Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{id}', [BlogController::class, 'show']);
Route::post('/blog', [BlogController::class, 'create']);
#Route::post('/blog/{id}', [BlogController::class, 'update']);
#Route::delete('/blog/{id}', [BlogController::class, 'destroy']);


//Website
Route::get('/website', [WebsiteController::class, 'index']);
Route::get('/website/{id}', [WebsiteController::class, 'show']);
Route::post('/website', [WebsiteController::class, 'create']);


Route::post('/subscriber', [SubscriberController::class, 'create']);
Route::get('/subscriber/send', [SubscriberController::class, 'sendEmail']);

Route::get('/test/email', function(){

    $send_mail = 'parmarvikrantr@gmail.com';

    dispatch(new App\Jobs\SendEmailJob($send_mail));

    dd('send mail successfully !!');
});