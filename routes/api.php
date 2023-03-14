<?php

use App\Http\Controllers\CepController;
use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/cep/{numero}', [CepController::class, 'show']);
Route::get('/teste', [PacienteController::class, 'teste']);
Route::apiResource('/pacientes', PacienteController::class);
Route::post('/import', [PacienteController::class, 'import']);
