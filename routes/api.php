<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/a', function () {
    app(\src\OpenAI\Http\Post\ChatComplement::class)->resolveEndpoint();
});
