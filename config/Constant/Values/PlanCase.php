<?php

return [
    'transportType' => [
        1 => '元払|ADVANCE_PAYMENT',
        2 => '着払|PAYMENT',
        3 => 'なし|NONE',
    ],
    'planDateAdjustedFlg' => [
        0 => '未調整|UNADJUSTED',
        1 => '調整済|ADJUSTED',
    ],
    'scheduledDateAdjustment' => [1 => ''],
    'schedulePickedUp' => [
        1 => '全て|ALL',
        2 => '登録済のみ|REGISTERED_ONLY',
        3 => '未登録のみ|UNREGISTERED_ONLY',
        4 => '予定日登録済未調整のみ|UNADJUSTED_ONLY',
    ],
    'schedulePickedUpCas040' => [
        1 => '全て|ALL',
        2 => '登録済のみ|REGISTERED_ONLY',
        3 => '未登録のみ|UNREGISTERED_ONLY',
    ],
    'transportTypeCas040' => [
        1 => '全て|ALL',
        2 => '持込|BRING_IN',
        3 => '運搬NW利用|TRANSPORTATION_NW',
    ],
    'linkking' => [
        1 => '紐付前',
        2 => '紐付済'
    ],
    'planCaseTypeCas040' => [
        1 => 'SCRAPPER_PLAN_CASE|SCRAPPER_PLAN_CASE',
        2 => 'OFFICE_PLAN_CASE|OFFICE_PLAN_CASE'
    ]
];