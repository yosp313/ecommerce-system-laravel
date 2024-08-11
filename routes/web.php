<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/csrf", function(){
    return csrf_token();
});


Route::post("/admin/login", [AdminController::class, "login"]);
