<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\AdminAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("/csrf", function(){
    return response()->json(["csrf_token" => csrf_token()]);
})->middleware("web");


Route::prefix("/v1/admin")->group(function(){
    Route::post("/login", [AdminController::class, "login"])->withoutMiddleware(AdminAuthMiddleware::class);
    Route::get("/logout", [AdminController::class, "logout"])->middleware(AdminAuthMiddleware::class);
    Route::post("/categories", [CategoryController::class, "store"])->middleware(AdminAuthMiddleware::class);
});
