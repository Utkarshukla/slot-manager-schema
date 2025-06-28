<?php

use App\Http\Controllers\PlanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
//
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('plans', PlanController::class);
    Route::post('websites', [WebsiteController::class, 'store'])->middleware('check.website.limit');
    Route::put('websites/{id}', [WebsiteController::class, 'update'])->middleware('check.website.limit');
    Route::resource('websites', WebsiteController::class)->except(['store', 'update']);
});
