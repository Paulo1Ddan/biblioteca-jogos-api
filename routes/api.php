<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



/* Route::get("/users", [UserController::class, 'index'])->name("users.index");
Route::post("/users/create", [UserController::class, 'create'])->name("users.create");
Route::get("/users/{id}", [UserController::class, 'show'])->name("users.show");
Route::put("/users/update/{id}", [UserController::class, 'show'])->name("users.show"); */
Route::apiResource("users", UserController::class);

Route::get("/teste", function(){
    return response()->json(["message" => "Hello World!"]);
});


