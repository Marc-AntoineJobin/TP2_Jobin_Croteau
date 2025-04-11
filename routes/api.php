<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
        Route::post('/film', 'App\Http\Controllers\FilmController@post');
        Route::put('/film', 'App\Http\Controllers\FilmController@update');
        Route::delete('/film', 'App\Http\Controllers\FilmController@delete');
    });
    Route::post('/critic', 'App\Http\Controllers\CriticController@post')->middleware([CriticMiddleware::class]);
    Route::get('/users/id', 'App\Http\Controllers\UserController@show');
    Route::put('/user', 'App\Http\Controllers\UserController@updatePassword');
});