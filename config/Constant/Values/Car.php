<?php

return [
    'carNoChangeFlg' => [
        0 => '手修正なし|NO_MANUAL_CORRECTION',
        1 => '手修正あり|WITH_HAND_CORRECTION',
    ],
    'exceedQtyDisableFlg' => [
        0 => '未処理|UNPROCESSED',
        1 => '処理済|PROCESSED',
    ],
    'mechanicalType' => [
        '001' => '運転席に機械式エアバッグを装備しています(但し一部電気式の場合があります)|IN_DRIVER_SEAT',
        '002' => '助手席に機械式エアバッグを装備しています|IN_PASSENGER_SEAT',
        '003' => '運転席と助手席に機械式エアバッグを装備しています|IN_DRIVER_AND_PASSENGER_SEAT',
        '006' => '運転席とプリテンに機械式を装備しています|IN_DRIVER_SEAT_AND_PRETEN',
        '007' => '全ての部位に機械式エアバッグ類を装備しています|IN_ALL_PARTS',
        '008' => 'シートベルトプリテンショナーのみ機械式を装備しています|SEATBELT_IS_SUPPORTED',
        '009' => 'シートベルトプリテンショナーのみ機械式を装備しています(一括作動に未対応です)|SEATBELT_IS_NOT_SUPPORTED',
    ],
    
];