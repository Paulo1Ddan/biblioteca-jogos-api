<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\GameLibraryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource("users", UserController::class);
Route::apiResource("games", GameController::class);
Route::get("/library/{user}", [GameLibraryController::class, "index"])->name("library");
Route::post("/library/add", [GameLibraryController::class, "addGame"])->name("library.addGame");
Route::delete("/library/remove", [GameLibraryController::class, "removeGame"])->name("library.removeGame");

Route::get("/teste", function(){
    return response()->json(["message" => "Hello World!"]);
});


