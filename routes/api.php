<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AddMasterDataController;
use App\Http\Controllers\MasterDataCheckController;
use App\Http\Controllers\MasterDataGetController;
use App\Http\Controllers\AddPaymentShopTableDataController;
use App\Http\Controllers\BuyCurrencyController;
use App\Http\Controllers\StaminaRecoveryController;
use App\Http\Controllers\TestStaminaConsumptionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::get('/register',RegistrationController::class);
Route::post('/register',RegistrationController::class); /*登録*/

//Route::get('/login',LoginController::class);
Route::post('/login',LoginController::class); /*ログイン */

Route::get('/addItemCategory',AddMasterDataController::class); /* アイテムカテゴリーデータ挿入 */
Route::get('/addPayment',AddPaymentShopTableDataController::class);/* 通貨ショップデータ挿入 */

//Route::get('/buyCurrency',BuyCurrencyController::class); /*通貨購入 */
Route::post('/buyCurrency',BuyCurrencyController::class); /*通貨購入 */

//Route::get('/staminaRecovery',StaminaRecoveryController::class); /*スタミナ回復 */
Route::post('/staminaRecovery',StaminaRecoveryController::class); /*スタミナ回復 */

//Route::get('/testConsumption',TestStaminaConsumptionController::class); /*スタミナ消費 */
Route::post('/testConsumption',TestStaminaConsumptionController::class); /*スタミナ消費 */

//Route::get('/masterCheck',MasterDataCheckController::class);
Route::post('/masterCheck',MasterDataCheckController::class);
//Route::get('/masterGet',MasterDataGetController::class);
Route::post('/masterGet',MasterDataGetController::class);