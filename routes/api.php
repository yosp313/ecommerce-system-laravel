<?php

use App\Http\Controllers\AdminSessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\AdminAuthMiddleware;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

Route::get("v1/users/verify-email", function(EmailVerificationRequest $request){
    $request->fulfill();
    return response()->json(["message" => "Email verified"]);
})->middleware(["web", "auth"])->name("verification.verify");

Route::post("v1/users/register", function(Request $request){
    $request->validate([
        "name" => "required",
        "email" => "required|email|unique:users",
        "password" => "required|confirmed"
    ]);

    $user = User::create([
        "name" => $request->name,
        "email" => $request->email,
        "password" => Hash::make($request->password)
    ]);

    Auth::login($user);

    return response()->json(["message" => "User registered successfully",
        "debugging" => [
                             "auth:check" => Auth::check(),
                             "auth:id" => Auth::id(),
                             "auth:user" => Auth::user()
        ]]);
})->middleware("web");

Route::get("v1/users/debug", function(Request $request){
    return response()->json([
        "auth:check" => Auth::check(),
        "auth:id" => Auth::id(),
        "auth:user" => Auth::user()
    ]);
})->middleware("web");
