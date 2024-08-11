<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get("/csrf", function(){
    return csrf_token();
});

Route::get("/hello", function(){
    return response()->json(["message" => "Hello World"]);
});


Route::prefix("/v1/admin")->group(function(){
    Route::post("/login", [AdminController::class, "login"]);
    Route::post("/categories", [CategoryController::class, "store"]);
});

