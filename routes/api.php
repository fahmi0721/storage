<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', 'Api\AuthController@login');
Route::get('/logout', 'Api\AuthController@logout')->middleware('auth:sanctum');
Route::get('/me', 'Api\AuthController@detail_user_login')->middleware('auth:sanctum');
Route::get('/dashboard', 'Api\HomeController@index')->middleware('auth:sanctum');

Route::post('/registrasi', 'Api\UserController@registrasi');

Route::prefix('/user')->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'Api\UserController@index');
    Route::delete('/hapus/{id}', 'Api\UserController@delete_data');
});

Route::prefix('/chat')->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'Api\ChatController@index');
    Route::post('/send', 'Api\ChatController@send');
});

Route::prefix('/list_chat')->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'Api\ListChatController@index');
    Route::get('/detail/{id}', 'Api\ListChatController@detail_perhitungan');
    Route::get('/detail/{id}', 'Api\ListChatController@detail_perhitungan');
    Route::delete('/hapus/{id}', 'Api\ListChatController@delete_data');
});

Route::prefix('/pengetahuan')->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'Api\PengetahuanController@index');
    Route::get('/find/{id}', 'Api\PengetahuanController@find_data');
    Route::post('/simpan', 'Api\PengetahuanController@create_data');
    Route::put('/ubah/{id}', 'Api\PengetahuanController@update_data');
    Route::delete('/hapus/{id}', 'Api\PengetahuanController@delete_data');
});