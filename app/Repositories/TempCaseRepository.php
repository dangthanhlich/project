<?php

namespace App\Repositories;

use App\Libs\ValueUtil;
use App\Models\TempCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TempCaseRepository {
    public function searchCas05($params, $orderBy) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = TempCase::select([
                'temp_case.temp_case_id',
                DB::raw("concat(temp_case.temp_case_id, '') as case_id"),
                'temp_case.temp_case_no',
                DB::raw('temp_case.temp_case_no as case_no'),
                'temp_case.case_status',
                'temp_case.collect_complete_time',
                'temp_case.sy_office_code',
                'temp_case.receive_complete_time',
            ])
            ->join('contract', function($join) use($flgDeleted) {
                $join
                    ->on('contract.temp_case_id', '=', 'temp_case.temp_case_id')
                    ->whereNull('contract.sign_sy')
                    ->where('contract.del_flg', '<>', $flgDeleted);
            })
            ->where('temp_case.del_flg', '<>', $flgDeleted);
        if (isset($params['case_status'])) {
            $query->whereIn('temp_case.case_status', $params['case_status']);
        }
        if (isset($params['transport_type'])) {
            $query->whereIn('temp_case.transport_type', $params['transport_type']);
        }
        if (isset($params['tr_office_code'])) {
            $query->where('temp_case.tr_office_code', $params['tr_office_code']);
        }
        foreach ($orderBy as $col => $order) {
            $query->orderBy($col, $order);
        }
        return $query;
    }

    /**
     * Update case_status
     * 
     * @param string|int $tempCaseId
     * @param array $params
     * @return bool
     */
    public function updateCaseStatus($tempCaseId, $params) {
        try {
            $tempCase = TempCase::find($tempCaseId);
            $tempCase->case_status = $params['case_status'];
            $tempCase->receive_complete_time = $params['receive_complete_time'];
            $tempCase->receive_user_id = $params['receive_user_id'];
            return $tempCase->save();
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }
    
    /**
     * create temp_case
     *
     * @param array $data
     */
    public function createTempCaseCas060($data = []) {
        try {
            $tempCase = new TempCase();
            $tempCase->temp_case_no = $data['temp_case_no'];
            $tempCase->case_status = ValueUtil::constToValue('Case.caseStatus.PICK_UP_RECEPTION');
            $tempCase->transport_type = ValueUtil::constToValue('Case.transportType.ADVANCE_PAYMENT');
            $tempCase->scrapper_office_code = $data['office_code'];
            $tempCase->sy_office_code = auth()->user()->office_code;
            $tempCase->case_picture_3 = $data['case_picture_3'];
            $tempCase->save();
            return $tempCase;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getByNotExistInCase($officeCode) {
        return TempCase::doesntHave('cases')
            ->where('del_flg', '!=', ValueUtil::constToValue('Common.delFlg.DELETED'))
            ->where('sy_office_code', $officeCode)
            ->select('temp_case_no')
            ->groupBy('temp_case_no')
            ->get();
    }

    /**
     * Get temp_case by case_id
     * @param $caseId
     * @return object
     */
    public function getByTempCaseId($caseId) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        try {
            $query = TempCase::where([
                    ['temp_case_id', $caseId],
                    ['del_flg', '<>', $flgDeleted],
                ])
                ->with([
                    'temp_car' => function($q) use($flgDeleted) {
                        return $q
                            ->select([
                                'temp_car_id as car_id',
                                'temp_case_id',
                                'temp_car_no as car_no'
                            ])
                            ->where('del_flg', '<>', $flgDeleted)
                            ->orderBy('car_no', 'ASC');
                    }
                ])
                ->with([
                    'contract' => function($q) use($flgDeleted) {
                        return $q
                            ->select([
                                'sign_sy',
                                'temp_case_id'
                            ])
                            ->where('del_flg', '<>', $flgDeleted)
                            ->whereNull('sign_sy');
                    }
                ])
                ->select([
                    'temp_case_id',
                    'temp_case_no',
                    'scrapper_office_code',
                    'case_status',
                    'case_picture_2'
                ])->first();
            return $query;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * cancel temp_case with temp_case_id
     * @param $tempCaseId
     */
    public function cancelTempCase($tempCaseId) {
        try {
            $tempCase = TempCase::find($tempCaseId);
            $tempCase->case_status = ValueUtil::constToValue('Case.caseStatus.PICK_UP_RECEPTION');
            $tempCase->receive_complete_time = NULL;
            $tempCase->receive_user_id = NULL;
            if ($tempCase->save()) {
                return true;
            }
            return false;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * update temp_case with params
     * @param $params
     * @param $casePicture2
     * @param $tempCaseId
     */
    public function updateTempCase061($casePicture2, $tempCaseId, $params = []) {
        try {
            DB::beginTransaction();
            $tempCase = TempCase::find($tempCaseId);
            $updateParams = [
                'temp_case_no' => $params['case_no'],
                'case_status' => ValueUtil::constToValue('Case.caseStatus.BEFORE_INSPECTION'),
                'receive_complete_time' => Carbon::now(),
                'receive_user_id' => auth()->user()->id
            ];
            if (strcmp($tempCase->case_picture_2, $casePicture2) != 0) {
                $updateParams = array_merge($updateParams, ['case_picture_2' => $casePicture2]);
            }
            $tempCase->update($updateParams);
            if ($tempCase) {
                DB::commit();
                return true;
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return false;
        }
    }
}
