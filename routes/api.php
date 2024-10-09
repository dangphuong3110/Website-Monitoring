<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TesseractOCRController;
use App\Http\Controllers\Api\WebsiteMonitoring\WebsiteMonitorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/ocr/convert-image-to-text', [TesseractOCRController::class, 'processImage'])->name('api.ocr.processImage');
Route::post('/remove-background-image', [TesseractOCRController::class, 'removeBg'])->name('api.ocr.removeBg');



