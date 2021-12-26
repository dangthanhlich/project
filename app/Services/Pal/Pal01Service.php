<?php

namespace App\Services\Pal;

use App\Libs\{
    ValueUtil,
    ConfigUtil,
    FileUtil
};
use App\Repositories\{
    CaseRepository,
    PalletRepository,
    PalletCaseRepository
};
use App\Models\{
    Pallet,
    PalletCase
};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{File, DB, Log};

class Pal01Service 
{
    private $caseRepository;

    public function __construct(
        CaseRepository $caseRepository,
        PalletRepository $palletRepository,
        PalletCaseRepository $palletCaseRepository
    ) {
        $this->caseRepository = $caseRepository;
        $this->palletRepository = $palletRepository;
        $this->palletCaseRepository = $palletCaseRepository;
    }

    /**
     * get data display to screen
     * 
     * @param $paramsSearch
     * @return array
     */
    public function getDataToDisplayOnScreen($paramsSearch) {
        try {
            $caseNo = [];
            $palletNo = [];
            $palletId = NULL;
            $totalCaseConstraint = 0;
            if (!empty($paramsSearch)) {
                $caseNo = $this->caseRepository->getDataPal010WithStatus($paramsSearch);
                $palletObj = $this->palletRepository->getDataPal010WithRelations($paramsSearch);
                if (empty($palletObj)) {
                    // insert new pallet
                    $palletObj = $this->createPallet($paramsSearch);
                }
                $palletId = $palletObj->pallet_id;
                $totalCaseConstraint = $palletObj->cases->count();
                foreach ($palletObj->cases as $case) {
                    $palletNo[] = [
                        'case_id' => $case['case_id'],
                        'case_no' => $case['case_no']
                    ];
                }
                usort($palletNo, function($a, $b) {
                    $retVal = $a['case_no'] <=> $b['case_no'];
                    if ($retVal == 0) {
                        $retVal = $a['case_id'] <=> $b['case_id'];
                    }
                    return $retVal;
                });
                // remove case_id conflict
                foreach ($palletNo as $compare) {
                    $keySearch = $this->searchForValue('case_id', $compare['case_id'], $caseNo);
                    unset($caseNo[$keySearch]);
                }
            }
            $dataSearch = [
                'caseNo' => $caseNo,
                'palletNo' => $palletNo,
                'palletId' => $palletId,
                'count' => $totalCaseConstraint
            ];
            return $dataSearch;
        } catch (\Exception $e) {
            Log::error($e);
            return [];
        }
    }

    /**
     * Insert new pallet
     * 
     * @param $paramsSearch
     * @return array
     */
    private function createPallet($paramsSearch = [])
    {
        DB::beginTransaction();
        $isError = false;
        try {
            $paramsInsert = [
                'pallet_no' => $paramsSearch['pallet_no'],
                'pallet_status' => ValueUtil::constToValue('Pallet.palletStatus.BEFORE_LOADING'),
                'sy_office_code' => $paramsSearch['officeCodeUser']
            ];
            $pallet = Pallet::create($paramsInsert);
        } catch (\Exception $e) {
            Log::error($e);
            $isError = true;
        }
        if ($isError) {
            DB::rollback();
            return false;
        }
        DB::commit();
        $paramsQuery = [
            'pallet_no' => $pallet->pallet_no,
            'officeCodeUser' => $pallet->sy_office_code,
        ];
        $palletObj = $this->palletRepository->getDataPal010WithRelations($paramsQuery);
        return $palletObj;
    }

    /**
     * Insert/update pallet_case
     *
     * @param Request $request
     * @return array
     */
    public function processPalletCase(Request $request) {
        DB::beginTransaction();
        $isError = false;
        try {
            $totalPalletCaseCurrent = $request->input()['totalPalletCase'];
            $idPalletTable = !empty($request->input()['idPalletTable']) ? $request->input()['idPalletTable'] : [];
            $idCaseTable = !empty($request->input()['idCaseTable']) ? $request->input()['idCaseTable'] : [];
            $palletId = $request->input()['palletId'];
            // get case_id not exist/exist in pallet_case
            $palletExistToInsert = $this->palletCaseRepository->getDataByCaseIds($idPalletTable, $palletId);
            $idsToInsert = [];
            foreach ($palletExistToInsert as $insert) {
                if (!empty($insert['case'])) {
                    $idsToInsert[] = $insert['case_id'];
                }
            }
            $palletExistToDelete = $this->palletCaseRepository->getDataByCaseIds($idCaseTable, $palletId);
            $idsToDelete = [];
            foreach ($palletExistToDelete as $delete) {
                if (!empty($delete['case'])) {
                    $idsToDelete[] = $delete['case_id'];
                }
            }
            // case_id not exist
            $caseIdsNotExist = array_diff($idPalletTable, $idsToInsert);
            // case_id exist
            $caseIdsExist = array_intersect($idCaseTable, $idsToDelete);
            // If total case constraint with pallet_case > 25 return error message
            if (count($idPalletTable) > ValueUtil::get('PalletCase.maxCase')) {
                return false;
            } 
            // Insert new pallet_case
            if (!empty($caseIdsNotExist)) {
                foreach ($caseIdsNotExist as $caseId) {
                    $paramsInsert = [
                        'case_id' => $caseId,
                        'pallet_id' => $palletId,
                        'link_time' => Carbon::now(),
                        'link_user_id' => auth()->user()->id,
                    ];
                    PalletCase::create($paramsInsert);
                }
            }
            // Delete pallet_case
            if (!empty($caseIdsExist)) {
                foreach ($caseIdsExist as $caseId) {
                    $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
                    $palletCaseQuery = PalletCase::where([
                        ['del_flg', '<>', $flgDeleted],
                        ['case_id', $caseId]
                    ]);
                    $paramsDelete = [
                        'del_flg' => $flgDeleted,
                        'updated_at' => Carbon::now(),
                        'updated_by' => auth()->user()->id,
                        'deleted_by' => auth()->user()->id,
                        'deleted_at' => Carbon::now(),
                    ];
                    $palletCaseQuery->update($paramsDelete);
                }
            }
        } catch (\Exception $e) {
            Log::error($e);
            $isError = true;
        }
        if ($isError) {
            DB::rollback();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * compare value 2 array
     * @param $keySearch
     * @param $valSearch
     * @param $array
     * @return int|boolean
     */
    private function searchForValue($keySearch, $valSearch, $array) {
        foreach ($array as $key => $val) {
            if ($val[$keySearch] === $valSearch) {
                return $key;
            }
        }
        return false;
     }
}