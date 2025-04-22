<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('throttle:5,1')->group( function(){
    Route::post('/signup', 'App\Http\Controllers\AuthController@register');
    Route::post('/signin', 'App\Http\Controllers\AuthController@login');
    Route::post('/signout', 'App\Http\Controllers\AuthController@logout')->middleware('auth:sanctum');
});
Route::middleware('auth:sanctum')->group( function(){
    Route::middleware([AdminMiddleware::class])->group( function(){
        Route::post('/films', 'App\Http\Controllers\FilmController@create');
        Route::put('/films/{id}', 'App\Http\Controllers\FilmController@update');
        Route::delete('/films/{id}', 'App\Http\Controllers\FilmController@delete');
    });
    Route::post('/critics', 'App\Http\Controllers\CriticController@create')->middleware([CriticMiddleware::class]);
    
    Route::middleware([OwnUserMiddleware::class])->group( function(){
    Route::get('/users/{id}', 'App\Http\Controllers\UserController@show');
    Route::put('/users/{id}', 'App\Http\Controllers\UserController@updatePassword');
    });
});