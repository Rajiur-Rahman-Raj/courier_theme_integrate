<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\VirtualCardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('payout/{code}', [VirtualCardController::class, 'payout'])->name('payout');
