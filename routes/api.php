<?php

use App\Http\Controllers\AdminSessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\AdminAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("/csrf", function(){
    return response()->json(["csrf_token" => csrf_token()]);
})->middleware("web");


//add a v1/admin prefix to all the routes
Route::middleware([StartSession::class, AdminAuthMiddleware::class])->prefix("v1/admin")->group(function(){
    Route::post('/login', [AdminSessionController::class, 'login'])->withoutMiddleware([AdminAuthMiddleware::class]);
    Route::get('/logout', [AdminSessionController::class, 'logout']);
    Route::post('/categories', [CategoryController::class, "store"]);
    Route::post("/products", [ProductController::class, "store"]);
});
