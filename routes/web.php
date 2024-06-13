<?php

use App\Http\Controllers\admin;
use App\Http\Controllers\globalfunctions;
use App\Http\Middleware\adminUser;
use App\Http\Controllers\userlogin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Rhis can access everybody
Route::get('/',[userlogin::class,"index"])->name("index");
Route::post('/login',[userlogin::class,"login"])->name("login");
Route::get('/logout',[userlogin::class,"logout"])->name("logout");
Route::get("/welcome",[globalfunctions::class,"welcome"])->name("welcome")->middleware("auth");
Route::get('/search/{data}',[globalfunctions::class,'search'])->name('search')->middleware("auth");
Route::get('/downloadn',[globalfunctions::class,"download"])->name("downloadn");

// This is only for employees
Route::get('/addHours/{req}',[userlogin::class,'userActions'])->name("addHours")->middleware(adminUser::class.':1');
Route::get('/userIndex',[userlogin::class,"userIndex"])->name("userIndex")->middleware(adminUser::class.':1');

// Makes sure that only users with the role 2 has access to it
Route::middleware([adminUser::class.':2'])->prefix("admin")->group(function(){
    Route::get('/statistic',[function(){return view("statistic");}])->name("astatistic");
    Route::get("/",[admin::class,"index"])->name("main");
    Route::get("/uren/{req}",[admin::class,"uren"])->name("urencheck");
    Route::get("/usercontrol/{req}",[admin::class,"usercontrol"])->name("usercontrol");
    Route::get("/freecheck/{req}",[admin::class,"freecheck"])->name("freecheck");
    Route::get('/downloada',[globalfunctions::class,"downloada"])->name("downloada");

});
