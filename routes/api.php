<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource("users", UserController::class);
Route::apiResource("games", GameController::class);

Route::get("/teste", function(){
    return response()->json(["message" => "Hello World!"]);
});


