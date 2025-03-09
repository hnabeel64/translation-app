<?php

use App\Http\Controllers\API\TranslationController;
use Illuminate\Http\Request;
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

/**
 * @OA\PathItem(
 *      path="/api"
 * )
 */

Route::middleware('auth:sanctum')->controller(TranslationController::class)->group(function () {
    Route::post('/translations', 'store');
    Route::put('/translations/{translation}', 'update');
    Route::delete('/translations/{translation}', 'destroy');
    Route::get('/translations/search/{locale}', 'search');
    Route::get('/translations/export', 'exportToJson');
});
