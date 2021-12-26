<?php

return [
    'caseStatus' => [
        1 => '集荷受付|PICK_UP_RECEPTION',
        2 => '集荷済|COLLECTED',
        3 => '検品前|BEFORE_INSPECTION',
        4 => '問い合わせ確認中|CHECKING_INQUIRIES',
        5 => '個数再確認前|BEFORE_RECONFIRMING_THE_NUMBER',
        6 => '引取報告前|BEFORE_THE_TAKE_BACK_REPORT',
        7 => '引取報告入力済|PICK_UP_REPORT_ENTERED',
        8 => '引取報告完了|COMPLETION_OF_TAKE_BACK_REPORT',
        9 => 'RP検品済|RP_INSPECTED',
    ],
    'transportType' => [
        1 => '元払|ADVANCE_PAYMENT',
        2 => '着払|PAYMENT',
        3 => 'なし|NONE',
    ],
    'caseNoChangeFlg' => [
        0 => '手修正なし|NO_MANUAL_CORRECION',
        1 => '手修正あり|WITH_HAND_CORRECTION',
    ],
    'exceedQtyFlg' => [
        0 => '超過個数なし|NO_EXCESS_QUANTITY',
        1 => '超過個数あり|THERE_IS_AN_EXCESS_NUMBER',
    ],
    'inspectStopFlg' => [
        0 => '検品可能|CAN_BE_INSPECTED',
        1 => '検品不可|INSPECTION_NOT_POSSIBLE',
    ],
    'receiveReportDiffFlg' => [
        0 => '差分なし|NO_DIFFERENCE',
        1 => '差分あり|THERE_IS_DIFFERENCE',
    ],
    'collectRequestCancelFlg' => [
        0 => '未取消|NOT_CANCELLED',
        1 => '取消済|CANCELLED',
    ],
    'cas090OrderBy' => [
        'deliver_report_time_asc' => [
            'label' => '引渡報告日（昇順）+解体業者事業所名',
            'orderBy' => [
                'case.deliver_report_time' => 'ASC',
                'mst_scrapper.office_name_kana' => 'ASC',
                'case.case_id' => 'ASC',
            ],
        ],
        'deliver_report_time_desc' => [
            'label' => '引渡報告日（降順）+解体業者事業所名',
            'orderBy' => [
                'case.deliver_report_time' => 'DESC',
                'mst_scrapper.office_name_kana' => 'ASC',
                'case.case_id' => 'ASC',
            ],
        ],
        'office_name_kana_asc' => [
            'label' => '解体業者事業所名+引渡報告日（昇順）',
            'orderBy' => [
                'mst_scrapper.office_name_kana' => 'ASC',
                'case.deliver_report_time' => 'ASC',
                'case.case_id' => 'ASC',
            ],
        ],
        'case_id_asc' => [
            'label' => '荷姿ID',
            'orderBy' => [
                'case.case_id' => 'ASC',
            ],
        ],
        'case_no_asc' => [
            'label' => 'ケース番号+解体業者事業所名',
            'orderBy' => [
                'case.case_no' => 'ASC',
                'mst_scrapper.office_name_kana' => 'ASC',
                'case.case_id' => 'ASC',
            ],
        ],
    ],
    'cas100OrderBy' => [
        'deliver_report_time_asc' => [
            'label' => '引渡報告日（昇順）+ 解体業者事業所名',
            'orderBy' => [
                'case.deliver_report_time' => 'ASC',
                'mst_scrapper.office_name_kana' => 'ASC',
                'case.case_id' => 'ASC',
            ],
        ],
        'deliver_report_time_desc' => [
            'label' => '引渡報告日（降順）+ 解体業者事業所名',
            'orderBy' => [
                'case.deliver_report_time' => 'DESC',
                'mst_scrapper.office_name_kana' => 'ASC',
                'case.case_id' => 'ASC',
            ],
        ],
        'office_name_kana_asc' => [
            'label' => '解体業者事業所名+引渡報告日（昇順）',
            'orderBy' => [
                'mst_scrapper.office_name_kana' => 'ASC',
                'case.deliver_report_time' => 'ASC',
                'case.case_id' => 'ASC',
            ],
        ],
        'case_id_asc' => [
            'label' => '荷姿ID',
            'orderBy' => [
                'case.case_id' => 'ASC',
            ],
        ],
        'case_no_asc' => [
            'label' => 'ケース番号 + 解体業者事業所名',
            'orderBy' => [
                'case.case_no' => 'ASC',
                'mst_scrapper.office_name_kana' => 'ASC',
                'case.case_id' => 'ASC',
            ],
        ],
    ],
    'cas020CaseStatus' => [
        1 => '集荷前|BEFORE_COLLECTION',
        2 => '集荷済|COLLECTED'
    ],
];