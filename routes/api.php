<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// 登録
use App\Http\Controllers\RegistrationController;
// ログイン
use App\Http\Controllers\LoginController;
// ホーム
use App\Http\Controllers\HomeController;
// マスタデータ
use App\Http\Controllers\AddMasterDataController;
use App\Http\Controllers\AddMissionMasterDataController;
use App\Http\Controllers\MasterDataCheckController;
use App\Http\Controllers\MasterDataGetController;
// ショップ
use App\Http\Controllers\AddPaymentShopTableDataController;
use App\Http\Controllers\BuyCurrencyController;
use App\Http\Controllers\BuyExchangeShopItemController;
// スタミナ
use App\Http\Controllers\StaminaRecoveryController;
use App\Http\Controllers\TestStaminaConsumptionController;
// ガチャ
use App\Http\Controllers\GachaExecuteController;
use App\Http\Controllers\GetGachaLogController;
// 武器
use App\Http\Controllers\LevelUpController;
use App\Http\Controllers\LimitBreakController;
use App\Http\Controllers\EvolutionController;
// プレゼントボックス
use App\Http\Controllers\CreatePrezentController;
use App\Http\Controllers\ReceivePrezentController;
// ミッション
use App\Http\Controllers\CreateMissionController;
use App\Http\Controllers\UpdateMissionController;
use App\Http\Controllers\ReceiveMissionController;
// ニュース
use App\Http\Controllers\AddNewsController;
use App\Http\Controllers\GetNewsController;


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

//Route::get('/home',HomeController::class); /* ホーム */
Route::post('/home',HomeController::class); /* ホーム */

Route::get('/addMasterData',AddMasterDataController::class); /* マスターデータ挿入 */
Route::get('/addMission',AddMissionMasterDataController::class); /*ミッションマスターデータ挿入 */

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

//Route::get('/exchangeShop',BuyExchangeShopItemController::class); /*交換ショップで購入 */
Route::post('/exchangeShop',BuyExchangeShopItemController::class); /*交換ショップで購入 */

//Route::get('/gachaExecute',GachaExecuteController::class); /*ガチャ */
Route::post('/gachaExecute',GachaExecuteController::class); /*ガチャ */

//Route::get('/getGachaLog',GetGachaLogController::class); /*ガチャログ取得*/
Route::post('/getGachaLog',GetGachaLogController::class); /*ガチャログ取得*/

// TODO: この下はクライアント側を未実装なので順次実装を行う

Route::get('/levelUp',LevelUpController::class); /*レベルアップ*/

Route::get('/limitBreak',LimitBreakController::class); /*限界突破*/

Route::get('/evolution',EvolutionController::class); /*進化*/

Route::get('/createPrezent',CreatePrezentController::class); /*プレゼント作成*/

Route::get('/receivePrezent',ReceivePrezentController::class); /*プレゼント受け取り*/

Route::get('/createMission',CreateMissionController::class); /*ミッション作成*/

Route::get('/updateMission',UpdateMissionController::class); /*ミッション更新*/

Route::get('/receiveMission',ReceiveMissionController::class); /*ミッション受け取り*/

Route::get('/addNews',AddNewsController::class); /*ニュース追加*/

Route::get('/getNews',GetNewsController::class); /*ニュース取得*/