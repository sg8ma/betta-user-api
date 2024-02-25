<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\RankingController;

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

//=================================
// User
//=================================
Route::post('/user', [UserController::class, 'Create']); 
Route::get('/user/{user_id}', [UserController::class, 'Read']);     
Route::put('/user/{user_id}', [UserController::class, 'Update']);
Route::delete('/user/{user_id}', [UserController::class, 'Delete']);

//=================================
// Stage
//=================================
Route::get('/stage', [StageController::class, 'List']);
Route::get('/stage/{stage_id}', [StageController::class, 'Read']);

//=================================
// Session
//=================================
Route::post('/session/login', [SessionController::class, 'Login']);   //ログイン
Route::get('/session', [SessionController::class, 'Status']);      //ログイン状態
Route::delete('/session', [SessionController::class, 'Logout']);   //ログアウト

//=================================
// Mail
//=================================
Route::get('/mail', [MailController::class, 'Create']);         //仮登録
Route::get('/mail/test', [MailController::class, 'TestMail']);  //テストメール
Route::post('/mail/{token}', [MailController::class, 'Auth']); //メール認証

//=================================
// Ranking
//=================================
Route::get('/ranking/{stage_id}', [RankingController::class, 'Read']);   //ランキング
Route::put('/ranking/{stage_id}', [RankingController::class, 'Update']); //ランキング登録