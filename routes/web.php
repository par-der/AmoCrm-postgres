<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DealsController;
use App\Models\deal;
use App\Models\responsible_user;
use App\Models\company;
use App\Models\contact;

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
session_start();

Route::get('/', function () {
    return view('welcome', ['url' => env('CRM_URL')]);
});

Route::get('/update',[DealsController::class,'crmtodb']);

Route::get('/dashboard', function () {
    return view('dashboard', ['leads' => deal::all(),'users' => responsible_user::all(),'contacts' => contact::all(),'companies' => company::all()]);
});