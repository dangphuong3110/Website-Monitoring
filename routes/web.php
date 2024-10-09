<?php

use App\Http\Controllers\Api\WebsiteMonitoring\WebsiteMonitorController;
use App\Http\Controllers\Bot\TelegramController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TesseractOCRController;
use App\Http\Controllers\WebsiteMonitoring\LoginController;
use App\Http\Controllers\WebsiteMonitoring\MonitorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('ocr.index');
});

Route::prefix('/ocr/convert-image-to-text')->group(function () {
    Route::get('/', [TesseractOCRController::class, 'index'])->name('ocr.index');
    Route::post('/', [TesseractOCRController::class, 'processImage'])->name('ocr.processImage');
});

Route::prefix('/remove-background-image')->group(function () {
    Route::get('/', [TesseractOCRController::class, 'test'])->name('ocr.test');
    Route::post('/', [TesseractOCRController::class, 'removeBg'])->name('ocr.removeBg');
});

Route::prefix('/website-monitoring')->group(function () {
    Route::get('/', function () {
        return redirect()->route('wm.index');
    });
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('wm.login');
    Route::get('/login-sso', [LoginController::class, 'loginSSO'])->name('wm.loginSSO');

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [MonitorController::class, 'index'])->name('wm.index');
        Route::post('/dashboard/{monitorId}', [MonitorController::class, 'updateFeaturedMonitor'])->name('update-featured-monitor');
        Route::post('/logout', [LoginController::class, 'logout'])->name('wm.logout');
    });
});

Route::prefix('/api/website-monitoring/dashboard')->group(function () {
    Route::get('/', [WebsiteMonitorController::class, 'index'])->name('api.wm.index');
    Route::get('/get-table-monitor', [WebsiteMonitorController::class, 'getTableMonitor'])->name('api.wm.getTableMonitor');
    Route::post('/', [WebsiteMonitorController::class, 'store'])->name('api.wm.store');
    Route::get('/incidents', [WebsiteMonitorController::class, 'renderIncidents'])->name('api.wm.renderIncidents');
    Route::post('/store-multiple-monitor', [WebsiteMonitorController::class, 'storeMultipleMonitor'])->name('api.wm.storeMultipleMonitor');
    Route::post('/store-new-tab', [WebsiteMonitorController::class, 'addNewTab'])->name('api.wm.addNewTab');
    Route::get('/tabs', [WebsiteMonitorController::class, 'renderTabs'])->name('api.wm.renderTabs');
    Route::delete('/remove-tab/{tabId}', [WebsiteMonitorController::class, 'removeTab'])->name('api.wm.removeTab');
});

Route::get('/telegram', [TelegramController::class, 'index']);
Route::post('/5917377959:AAHiHqxvdY8vFufMNm0auq3GLvEWF8BPGDU/webhook', [TelegramController::class, 'webhook']);
Route::get('/telegram/getImageRandom', [TelegramController::class, 'getImageRandom']);
Route::get('/telegram/getUpdates', [TelegramController::class, 'getUpdates']);
Route::get('/telegram/getWebhookInfo', [TelegramController::class, 'getWebhookInfo']);

Route::get('/{any}', function () {
    return view('error.error');
})->where('any', '.*');
