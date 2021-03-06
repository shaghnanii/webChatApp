<?php

use App\Http\Controllers\Chat\ChatsController;
use App\Http\Controllers\MorphController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('/home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('chats', [ChatsController::class, 'showChats']);
Route::post('chats', [ChatsController::class, 'sendChat']);

Route::post('getMyChat', [ChatsController::class, 'getUserChat']);


Route::get('/morph', [MorphController::class, 'index']);