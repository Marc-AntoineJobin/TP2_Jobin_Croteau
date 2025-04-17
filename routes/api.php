<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\OwnUserMiddleware;
use App\Http\Middleware\CriticMiddleware;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Routes du TP2 ici : 
Route::get('/films', 'App\Http\Controllers\FilmController@index');

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
    Route::put('/user/{id}', 'App\Http\Controllers\UserController@updatePassword');
    });
});