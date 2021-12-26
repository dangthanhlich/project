<?php

namespace App\Repositories;

use App\Libs\ValueUtil;
use App\Models\PalletCase;
use Illuminate\Support\Facades\{DB, Log};

class PalletCaseRepository {
	/**
     * get data by caseIds
     * @param $caseIds
     * @param $palletId
     * @return array
     */
    public function getDataByCaseIds($caseIds = [], $palletId) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        try {
            return $query = PalletCase::select([
                'case_id'
            ])
            ->where([
                ['del_flg', '<>', $flgDeleted],
                ['pallet_id', $palletId]
            ])
            ->whereIn('case_id', $caseIds)
            ->with([
                'case' => function ($q) use($flgDeleted) {
                    $q->select([
                            'case_id'
                        ])
                        ->where([
                            ['del_flg', '<>', $flgDeleted],
                            ['sy_office_code', auth()->user()->office_code],
                            ['case_status', ValueUtil::constToValue('Case.caseStatus.COMPLETION_OF_TAKE_BACK_REPORT')],
                        ]);
                },
            ])
            ->get()->toArray();
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
