<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;



//
Route::middleware("guest")->group(function(){

    //login routes
    Route::get("/login", [AuthController::class , "login"])->name("login");
    Route::post("/login", [AuthController::class , "authenticate"])->name("authenticate");

    // registration routes
    Route::get("/register", [AuthController::class , "register"])->name("register");
    Route::post("/register", [AuthController::class , "store_user"])->name("store_user");

    // New user confirmation
    Route::get("/new_user_confirmation/{token}", [AuthController:: class, "new_user_confirmation"])->name("new_user_confirmation");
});

// Rota pública que NÃO exige login
Route::get("/", [MainController::class, "home"])->name("home");

// Rotas que EXIGEM login
Route::middleware("auth")->group(function () {
    Route::get("/logout", [AuthController::class, "logout"])->name("logout");

    // Profile
    Route::get("/profile", [AuthController::class, "profile"])->name("profile");

    // Rota para ALTERAR a senha
    Route::post("/profile/change-password", [AuthController::class, "change_password"])->name("change_password");
});


