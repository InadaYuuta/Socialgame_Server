<?php

return [
    // ユーザーテーブル
    'USER_NAME' => 'ユウタ',
    'HANDOVER_PASSHASH' => '',
    'HAS_REINFORCE_POINT' => 0,
    'USER_RANK' => 1,
    'LOGIN_DAYS' => 0,
    'MAX_STAMINA' => 200,
    'LAST_STAMINA' => 200,
    // ウォレットテーブル
    'FREE_AMOUNT' => 0,
    'PAID_AMOUNT' => 0,
    'MAX_AMOUNT' => 99999,
    // アイテムテーブル
    'ITEM_NUM' => 0,
    'USED_NUM' => 0,

    // アイテムID
    'STAMINA_RECOVERY_ITEM_ID' => 10001,
    'EXCHANGE_ITEM_ID' => 30001,
    'CONVEX_ITEM_ID' => 40001,
    'NORMAL_SWORD_ITEM_ID' => 40002,
    'NORMAL_BOW_ITEM_ID' => 40003,
    'NORMAL_SPEAR_ITEM_ID' => 40004,
    'STRONG_BOW_ITEM_ID' => 40005,
    'VERY_STRONG_SWORD_ITEM_ID' => 40006,

    // 武器ID
    'NORMAL_SWORD_ID' => 1010001,
    'NORMAL_BOW_ID' => 1020001,
    'NORMAL_SPEAR_ID' => 1030001,
    'STRONG_BOW_ID' => 2020001,
    'VERY_STRONG_SWORD_ID' => 3010001,

    // マスタデータ
    'MASTER_DATA_VERSION' => 1,

    // スタミナ関連
    'STAMINA_RECOVERY_SECOND' => 180,  // スタミナ回復にかかる時間
    'STAMINA_RECOVERY_VALUE' => 1,     // 1回のスタミナ回復量

    /*レスポンス*/
    /*100番台...情報 
    /*200番台...成功
    /*300番台...リダイレクト
    /*400番台...クライアントエラー
    /*500番台...サーバーエラー
    */

    /* 情報 */

    /* 成功 */
    'RESPONSE_SUCCESS' => 200,

    /* リダイレクト */
    'ERRCODE_LOGIN_SESSION' => 400,

    // クライアントエラー

    /* サーバーエラー */
    'ERRCODE_VALIDATION' => 500,
    'ERRCODE_MASTER_VERSION' => 501,

    // Auth関連
    'ERRCODE_LOGIN_USER_NOT_FOUND' => 502, // ログインユーザーが見つからなかった
    'ERRCODE_NOT_LOGGED_IN' => 503, // ログインできなかった
    'ERRCODE_LOST_CONNECT' => 504, // 通信が切断された

    'ERRCODE_CANT_REGISTRATION' => 505, // 登録ができなかった
    'ERRCODE_CANT_LOGIN' => 506, // ログインできなかった
    'ERRCODE_CANT_UPDATE_HOME' => 507,
    'ERRCODE_CANT_STAMINA_RECOVERY' => 508, // スタミナ回復ができなかった
    'ERRCODE_CANT_STAMINA_CONSUMPTION' => 509, // スタミナ消費ができなかった
    'ERRCODE_CANT_RECOVERY_ANY_MORE_STAMINA' => 510, // これ以上スタミナが回復できない
    'ERRCODE_CANT_BUY_CURRENCY' => 511,
    'ERRCODE_CANT_EXCHANGE_ITEM' => 512,
    'ERRCODE_NOT_ENOUGH_EXCHANGE_ITEM' => 513,
    'ERRCODE_CANT_LEVEL_UP' => 514,
    'ERRCODE_CANT_EVOLUTION' => 515,
    'ERRCODE_VALIDATION' => 516, // バリデーションエラー
    'ERRCODE_CANT_BUY_CURRENCY' => 517, // 通貨購入に失敗した

    /* エラーメッセージ */

    // Auth関連
    'LOGIN_USER_NOT_FOUND' => 'ログインしているユーザーは見つかりませんでした',
    'USER_IS_NOT_LOGGED_IN' => 'ユーザーはログインしていません',
    'LOST_CONNECT' => '接続が切れました',

    // 登録
    'CANT_REGISTRATION' => '登録ができませんでした',
    'INCORRECT_STRING' => '文字列は空白や記号を含まない半角アルファベットと半角数字で文字以上12文字以内で入力して下さい',

    // ログイン
    'CANT_LOGIN' => 'ログインができませんでした',

    // ホーム
    'CANT_UPDATE_HOME' => 'ホーム情報を更新できませんでした',

    // スタミナ
    'CANT_STAMINA_RECOVERY' => 'スタミナ回復ができませんでした',
    'CANT_STAMINA_CONSUMPTION' => 'スタミナ消費ができませんでした',
    'CANT_RECOVERY_ANY_MORE_STAMINA' => 'これ以上スタミナを回復できません',

    // ショップ
    'CANT_BUY_CURRENCY' => '通貨の購入ができませんでした',
    'CANT_EXCHANGE_ITEM' => 'アイテム交換ができませんでした',
    'NOT_ENOUGH_EXCHANGE_ITEM' => '交換アイテムが足りません',

    // 武器
    'CANT_LEVEL_UP' => '強化ができませんでした',
    'CANT_EVOLUTION' => '進化ができませんでした',
    'CANT_LIMIT_BREAK' => '限界突破ができませんでした',
    'HAS_WEAPON' => '所持している武器です',
    'HASNT_WEAPON' => '所持していない武器です',
    'NOT_ENOUGH_LEVEL' => 'レベルが足りません',
    'MAX_LEVEL' => 'レベルが上限を超えています',
    'MAX_LIMIT_BREAK' => '限界突破が上限を超えています',
    'NOT_ENOUGH_REINFORCEPOINT' => '所持強化ポイントが足りません',
    'NOT_ENOUGH_CONVEX_ITEM' => '所持凸アイテムが足りません',

    // ミッション
    'MISSION_ALREADY_ADDED' => '既に追加済みのミッションです',
    'CANT_ADD_MISSION' => 'ミッションを追加できませんでした',
    'CANT_UPDATE_MISSION' => 'ミッションを更新できませんでした',
    'MISSION_ALREADY_RECEIVE' => '既に受け取ったミッションです',
    'MISSION_ALREADY_COMPLETE' => '達成済みのミッションです',
    'MISSION_NOT_ACCOMPLISHED' => '達成していないミッションです',
    'CANT_RECEIVE_MISSION' => 'ミッション報酬を受け取れませんでした',

    // プレゼント
    'CANT_ADD_PRESENT' => 'プレゼントを追加できませんでした',
    'PRESENT_ALREADY_RECEIVE' => '既に受け取ったプレゼントです',
    'CANT_RECEIVE_PRESENT' => 'プレゼントを受け取れませんでした',

    // ガチャ
    'NOT_ENOUGH_CURRENCY' => '所持通貨が足りません',
    'CANT_GACHA' => 'ガチャが引けませんでした',
    'CANT_GET_GACHA_LOG' => 'ガチャ履歴を取得できませんでした',

    /* ログ */
    // ユーザー情報更新
    'USER_DATA' => 1,
    'REGISTRATION_USER' => 'registration_user/',
    'LOGIN_USER' => 'login_user/',
    'CHANGE_USER_NAME' => 'change_user_name/',
    'CHANGE_HANDOVER_PASSHASH' => 'change_handover_passhash/',
    'GET_HAS_REINFORCE_POINT' => 'get_has_reinforce_point/',
    'USE_HAS_REINFORCE_POINT' => 'use_has_reinforce_point/',
    'UPDATE_USER_RANK' => 'update_user_rank/',
    'UPDATE_MAX_STAMINA' => 'update_max_stamina/',
    'UPDATE_LAST_STAMINA' => 'update_last_stamina/',
    'UPDATE_STAMINA' => 'update_stamina/',
    'STAMINA_RECOVERY' => 'stamina_recovery/',
    'CONSUMPTION_STAMINA' => 'consumption_stamina/',

    // 通貨情報更新
    'CURRENCY_DATA' => 2,
    'REGISTRATION_WALLET' => 'registration_wallet/',
    'BUY_CURRENCY' => 'buy_currency/',
    'GET_CURRENCY' => 'get_currency/',
    'USE_CURRENCY' => 'use_currency/',

    // アイテム情報更新
    'ITEM_DATA' => 3,
    'REGISTRATION_Item' => 'registration_item/',
    'GET_ITEM' => 'get_item/',
    'USE_ITEM' => 'use_item/',

    // 武器情報更新
    'WEAPON_DATA' => 4,
    'GET_WEAPON' => 'get_weapon/',
    'LEVEL_UP_WEAPON' => 'level_up_weapon/',
    'LIMIT_BREAK_WEAPON' => 'limit_break_weapon/',
    'EVOLUTION_WEAPON' => 'evolution_weapon/',

    // ミッション情報更新
    'MISSION_DATA' => 5,
    'ACHIEVED_MISSION' => 'achieved_mission/',
    'RECEIPT_MISSION' => 'receipt_mission/',
    'PROGRESS_MISSION' => 'progress_mission/',

    // シーズンパス情報更新
    // TODO:シーズンパスのテーブルおよび処理ができたら追記する
    'SEASON_PASS_DATA' => 6,

    // プレゼントボックス情報更新
    'PRESENT_BOX_DATA' => 7,
    'RECEIPT_PRESENT_BOX' => 'receipt_present_box',
    'DELETE_PRESENT_BOX' => 'delete_present_box',
];
