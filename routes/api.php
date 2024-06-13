<?php

use Illuminate\Http\Request;
use App\Http\Controllers\admin;
use App\Http\Controllers\userlogin;
use App\Http\Middleware\adminUser;
use Illuminate\Support\Facades\Route;

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

// Don't ask me for what this is it was build in so don't addapt it
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Makes sure that the user is admin
Route::middleware([adminUser::class . ":2"])->group(function () {
    Route::get("/houra/{id}", [admin::class, "disaproveHours"]);
    Route::get("/hourc/{id}", [admin::class, "approveHours"]);
    Route::get("/userd/{id}", [admin::class, "deleteUser"]);
    Route::post("/useru", [admin::class, "updateUser"]);
    Route::get("/uPass/{id}", [admin::class, "updatePass"]);
    Route::post("/addUser", [admin::class, "addUser"]);
    Route::get("/cancel/{id}", [userlogin::class, "cancel"]);
    Route::post("/updateOwnAccount", [admin::class, "updateOwnAccount"]);
    Route::get("/freeAprove/{id}/{toDo}", [admin::class, "aproveOrDenyFree"]);
    Route::get("/getDataForPie/{month}/{year}",[admin::class,"getData"]);
});

// Makes sure that the user is a employee
Route::middleware([adminUser::class . ":1"])->group(function () {
    Route::post("/addHour", [userlogin::class, "addHour"]);
    Route::post("/free", [userlogin::class, "free"]);
    Route::get("/cancel/{id}", [userlogin::class, "cancel"]);
    Route::get("/statistics/{year}/{select}", [userlogin::class, "statistics"]);
});
