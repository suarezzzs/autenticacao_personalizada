<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


//
Route::middleware("guest")->group(function(){

    //login routes
    Route::get("/login", [AuthController::class , "login"])->name("login");
    Route::post("/login", [AuthController::class , "authenticate"])->name("authenticate");

    // registration routes
    Route::get("/register", [AuthController::class , "register"])->name("register");
    Route::post("/register", [AuthController::class , "store_user"])->name("store_user");

});

// Mesmo que tento entrar na pagina inicial ele vai me direcionar ao login
Route::middleware("auth")->group(function(){
    Route::get("/", function(){
        echo "ola mundo";
    })->name("home");

    Route::get("/logout", [AuthController::class , "logout"])->name("logout");
});


