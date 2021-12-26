<?php

return [
    'userType' => [
        1 => 'システム管理者|SYSTEM_ADMIN',
        2 => '自再協|SELF_RECONCILIATION',
        3 => '事業所|OFFICE',
    ],
    'jarpType' => [
        1 => '施設管理|FACILITY_MANAGEMENT',
        2 => '渉外|PUBLIC_RELATION',
    ],
    'officeManager' => [
        1 => '事業所管理者'
    ],
    'invalidFlg' => [
        0 => '有効|VALID',
        1 => '無効|INVALID',
    ],
    'traderAuthority' => [
        1 => '運搬NW|TR',
        2 => '指定引取場所|SY',
        3 => '二次運搬|2TR',
        4 => '再資源化施設|RP'
    ],
    'trOfficeFlg' => [
        0 => '権限なし|NO_AUTHORITY',
        1 => '権限あり|WITH_AUTHORITY',
    ],
    'syOfficeFlg' => [
        0 => '権限なし|NO_AUTHORITY',
        1 => '権限あり|WITH_AUTHORITY',
    ],
    '2ndTrOfficeFlg' => [
        0 => '権限なし|NO_AUTHORITY',
        1 => '権限あり|WITH_AUTHORITY',
    ],
    'rpOfficeFlg' => [
        0 => '権限なし|NO_AUTHORITY',
        1 => '権限あり|WITH_AUTHORITY',
    ],
    'officeAdminFlg' => [
        0 => '権限なし|NO_AUTHORITY',
        1 => '権限あり|WITH_AUTHORITY',
    ],
    'userTypeSession' => [
        'system_admin' => 'AdminUser',
        'self_reconciliation' => 'JarpUser',
        'office' => 'OfficeUser',
    ],
    'permission' => [
        'NW' => 'is_NW',
        'SY' => 'is_SY',
        'SD' => 'is_SD',
        'RP' => 'is_RP',
        'JA1' => 'is_JA1',
        'JA2' => 'is_JA2',
        'admin' => 'is_admin',
        'dismantling' => 'is_dismantling',
    ],
];
