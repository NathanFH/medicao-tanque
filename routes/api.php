<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeituraController;

Route::post('/leituras', [LeituraController::class, 'store']);
Route::get('/leituras/latest', [LeituraController::class, 'latest']);