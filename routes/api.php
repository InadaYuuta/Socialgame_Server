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
use App\Http\Controllers\ItemRegistrationController;
use App\Http\Controllers\UpdateItemsController;
use App\Http\Controllers\BuyExchangeShopItemController;

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

Route::get('/addMasterData',AddMasterDataController::class); /* マスターデータ挿入 */

//Route::get('/buyCurrency',BuyCurrencyController::class); /*通貨購入 */
Route::post('/buyCurrency',BuyCurrencyController::class); /*通貨購入 */

//Route::get('/staminaRecovery',StaminaRecoveryController::class); /*スタミナ回復 */
Route::post('/staminaRecovery',StaminaRecoveryController::class); /*スタミナ回復 */

//Route::get('/testConsumption',TestStaminaConsumptionController::class); /*スタミナ消費 */
Route::post('/testConsumption',TestStaminaConsumptionController::class); /*スタミナ消費 */

//Route::get('/masterCheck',MasterDataCheckController::class);
Route::post('/masterCheck',MasterDataCheckController::class); /**マスターデータチェック */
//Route::get('/masterGet',MasterDataGetController::class);
Route::post('/masterGet',MasterDataGetController::class); /*マスターデータ取得 */

//Route::get('/itemRegist',ItemRegistrationController::class); /*アイテムの登録 */
Route::post('/itemRegist',ItemRegistrationController::class); /*アイテムの登録 */

//Route::get('/itemUpdate',UpdateItemsController::class); /*アイテムの更新 */
Route::post('/itemUpdate',UpdateItemsController::class); /*アイテムの更新 */

//Route::get('/exchangeShop',BuyExchangeShopItemController::class); /*交換ショップで購入 */
Route::post('/exchangeShop',BuyExchangeShopItemController::class); /*交換ショップで購入 */