<?php

namespace App\Repositories;

use App\Libs\ValueUtil;
use App\Models\{
    Cases,
    Car
};
use Carbon\Carbon;
use Illuminate\Support\Facades\{DB, Log};

class CaseRepository
{
    public function save(array $input, $caseId = null)
    {
        return Cases::updateOrCreate(['case_id' => $caseId], $input);
    }

    public function existsByCaseNo($caseId)
    {
        return Cases::where('case_id', $caseId)
            ->where('del_flg', ValueUtil::constToValue('Common.delFlg.DELETED'))
            ->exists();
    }

    /**
     * Search cas100
     * @param string $userOfficeCode
     * @param array $params
     * @return mixed
     */
    public function searchCas100($userOfficeCode, $params = []) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $officeCodeOfUser = auth()->user()->office_code;
        $query = Cases::select([
                'case.case_id',
                'case.case_no',
                DB::raw("DATE_FORMAT(case.receive_complete_time, '%Y/%m/%d') as receive_complete_time"),
                'case.transport_type',
                'case.exceed_qty_flg',
                'mst_scrapper.office_name as office_name',
            ])
            ->join('mst_scrapper', function($join) use($flgDeleted) {
                $join
                    ->on('mst_scrapper.office_code', '=', 'case.scrapper_office_code')
                    ->where('mst_scrapper.del_flg', '<>', $flgDeleted);
            })
            // relation car where exceed_qty_disable_flg = 0
            ->with([
                'car' => function($q) use($flgDeleted) {
                    return $q
                        ->select([
                            'car.car_id',
                            'car.case_id',
                            'exceed_qty_disable_flg',
                        ])
                        ->where([
                            ['car.del_flg', '<>', $flgDeleted],
                            ['car.exceed_qty_disable_flg', ValueUtil::constToValue('Car.exceedQtyDisableFlg.UNPROCESSED')],
                        ]);
                }
            ])
            ->where([
                ['case.del_flg', '<>', $flgDeleted],
                ['case.sy_office_code', $userOfficeCode],
                ['case.case_status', ValueUtil::constToValue('Case.caseStatus.BEFORE_THE_TAKE_BACK_REPORT')]
            ]);
        if (isset($params['order_by'])) {
            $cas100OrderBy = ValueUtil::get('Case.cas100OrderBy', ['get_value' => true]);
            $orderByConfig = isset($cas100OrderBy[$params['order_by']]['orderBy'])
                ? $cas100OrderBy[$params['order_by']]['orderBy']
                : [];
            foreach ($orderByConfig as $col => $orderBy) {
                $query->orderBy($col, $orderBy);
            }
        }
        return $query;
    }

    /**
     * Get case with car by case_id
     * @param int $caseId
     * @return object
     */
    public function getCaseWithCarByCaseId($caseId) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = Cases::select([
            'case.case_id',
            'case.case_no',
            'case.slip_no',
            'mst_scrapper.id as mstScrapper_id',
            'mst_scrapper.office_name as mstScrapper_office_name',
            'mstOfficeTr.id as mstOfficeTr_id',
            'mstOfficeTr.office_name as mstOfficeTr_office_name',
        ])
            ->with([
                'car' => function($q) use($flgDeleted) {
                    return $q
                        ->select([
                            'car.car_id',
                            'car.case_id',
                            'car.car_no',
                            'car.qty',
                            'car.exceed_qty',
                        ])
                        ->where('car.del_flg', '<>', $flgDeleted);
                },
            ])
            ->join('mst_scrapper', function($join) use($flgDeleted) {
                $join
                    ->on('mst_scrapper.office_code', '=', 'case.scrapper_office_code')
                    ->where('mst_scrapper.del_flg', '<>', $flgDeleted);
            })
            ->join('mst_office as mstOfficeTr', function($join) use($flgDeleted) {
                $join
                    ->on('mstOfficeTr.office_code', '=', 'case.tr_office_code')
                    ->where('mstOfficeTr.del_flg', '<>', $flgDeleted);
            })
            ->where([
                ['case.case_id', $caseId],
                ['case.del_flg', '<>', $flgDeleted],
            ])
            ->first();
        return $query;
    }

    /**
     * Search cas090
     *
     * @param int|string $userOfficeCode
     * @param array $params
     * @return Query
     */
    public function searchCas090($userOfficeCode, $params = []) {
        $deletedFlg = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = Cases::select([
                'case.case_id',
                'case.case_no',
                DB::raw("DATE_FORMAT(case.receive_report_time, '%Y/%m/%d') as receive_report_time"),
                'case.transport_type',
                'mst_scrapper.office_name as office_name',
            ])
            ->join('mst_scrapper', function($join) use($deletedFlg) {
                $join
                    ->on('mst_scrapper.office_code', '=', 'case.scrapper_office_code')
                    ->where('mst_scrapper.del_flg', '<>', $deletedFlg);
            })
            ->where([
                ['case.sy_office_code', $userOfficeCode],
                ['case.case_status', ValueUtil::constToValue('Case.caseStatus.BEFORE_RECONFIRMING_THE_NUMBER')],
                ['case.del_flg', '<>', $deletedFlg],
            ]);
        if (isset($params['order_by'])) {
            $cas090OrderBy = ValueUtil::get('Case.cas090OrderBy', ['get_value' => true]);
            $orderByConfig = isset($cas090OrderBy[$params['order_by']]['orderBy'])
                ? $cas090OrderBy[$params['order_by']]['orderBy']
                : [];
            foreach ($orderByConfig as $col => $orderBy) {
                $query->orderBy($col, $orderBy);
            }
        } else {
            $query->orderBy('case.case_id', 'ASC');
        }
        return $query;
    }

    /**
     * Get case by case_id for cas-091
     *
     * @param int $caseId
     * @return object
     */
    public function getCaseByCaseIdForCas091($caseId) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = Cases::select([
                'case.case_id',
                'case.case_no',
                DB::raw("DATE_FORMAT(case.inspect_complete_time, '%Y/%m/%d %H:%i') as inspect_complete_time"),
                'case.inspect_user_id',
                'mst_scrapper.id as mstScrapper_id',
                'mst_scrapper.office_name as mstScrapper_office_name',
            ])
            ->with([
                'car' => function($q) use($flgDeleted) {
                    return $q
                        ->select([
                            'car.car_id',
                            'car.case_id',
                            'car.car_no',
                            'car.qty',
                            'car.car_picture',
                        ])
                        ->where('car.del_flg', '<>', $flgDeleted)
                        ->orderBy('car.car_no', 'ASC');
                },
            ])
            ->join('mst_scrapper', function($join) use($flgDeleted) {
                $join
                    ->on('mst_scrapper.office_code', '=', 'case.scrapper_office_code')
                    ->where('mst_scrapper.del_flg', '<>', $flgDeleted);
            })
            ->where([
                ['case.case_id', $caseId],
                ['case.del_flg', '<>', $flgDeleted],
            ])
            ->first();
        return $query;
    }

    /**
     * Search cas031
     * @param string $managementNo
     * @return Query
     */
    public function searchCas031($managementNo) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = Cases::select([
                'case.case_id',
                'case.case_no',
                'case.scrapper_office_code',
                'case.tr_office_code',
                DB::raw("DATE_FORMAT(case.collect_request_time, '%Y/%m/%d') as collect_request_time"),
                DB::raw("DATE_FORMAT(case.collect_complete_time, '%Y/%m/%d') as collect_complete_time"),
                'case.case_status',
                'case.deliver_report_time',
                'contract.contract_pdf as contract_pdf',
            ])
            ->join('contract', function($join) use($flgDeleted, $managementNo) {
                $join
                    ->on('contract.case_id', '=', 'case.case_id')
                    ->where([
                        ['contract.del_flg', '<>', $flgDeleted],
                        ['contract.management_no', $managementNo],
                    ]);
            })
            ->where('case.del_flg', '<>', $flgDeleted);
        return $query;
    }

    /**
     * Search for CAS-05
     *
     * @param array $params
     * @param array $orderBy
     */
    public function searchCas05($params, $orderBy) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = Cases::select([
                'case.case_id',
                'case.case_no',
                'case.collect_complete_time',
                'case.case_status',
                'case.sy_office_code',
                'case.receive_complete_time',
            ])
            ->join('contract', function($join) use($flgDeleted) {
                $join
                    ->on('contract.case_id', '=', 'case.case_id')
                    ->whereNull('contract.sign_sy')
                    ->where('contract.del_flg', '<>', $flgDeleted);
            })
            ->where('case.del_flg', '<>', $flgDeleted);
        if (isset($params['case_status'])) {
            $query->whereIn('case.case_status', $params['case_status']);
        }
        if (isset($params['transport_type'])) {
            $query->whereIn('case.transport_type', $params['transport_type']);
        }
        if (isset($params['tr_office_code'])) {
            $query->where('case.tr_office_code', $params['tr_office_code']);
        }
        foreach ($orderBy as $col => $order) {
            $query->orderBy($col, $order);
        }
        return $query;
    }

    /**
     * Update case_status
     *
     * @param string|int $caseId
     * @param array $params
     * @return bool
     */
    public function updateCaseStatus($caseId, $params) {
        try {
            $case = Cases::find($caseId);

            foreach ($params as $key => $value) {
                $case->$key = $value;
            }

            return $case->save();
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }

    public function queryCas07($officeCode, array $caseStatus = [Cases::STATUS['before_inspection'], Cases::STATUS['checking']])
    {
        return Cases::select('case.*')
            ->join('mst_user', 'mst_user.office_code', 'case.sy_office_code')
            ->join('temp_case', 'temp_case.sy_office_code', 'mst_user.office_code')
            ->whereIn('case.case_status', $caseStatus)
            ->where('case.del_flg', '!=', ValueUtil::constToValue('Common.delFlg.DELETED'))
            ->where('case.sy_office_code', $officeCode)
            ->groupBy('case.case_no');
    }

    /**
     * Get case by case_id
     * @param $caseId
     * @return object
     */
    public function getByCaseId($caseId) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        try {
            $query = Cases::where([
                    ['case_id', $caseId],
                    ['del_flg', '<>', $flgDeleted],
                ])
                ->with([
                    'car' => function($q) use($flgDeleted) {
                        return $q
                            ->select([
                                'car_id',
                                'case_id',
                                DB::raw('IF(CHAR_LENGTH(car_no) > 4, SUBSTR(car_no, -4), car_no) as car_no'),
                                'mechanical_type'
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
                                'case_id'
                            ])
                            ->where('del_flg', '<>', $flgDeleted)
                            ->whereNull('contract.sign_sy');
                    }
                ])
                ->select([
                    'case_id',
                    'case_no',
                    'scrapper_office_code',
                    'case_status',
                    'case_picture_2',
                    'actual_qty_rp',
                    'case_picture_4',
                ])->first();
            return $query;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function queryCas08($officeCode, $params) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = Cases::select([
                'case.case_id',
                'case.case_no',
                'case.case_status',
                DB::raw("DATE_FORMAT(plan_case.receive_plan_date, '%Y/%m/%d') as receive_plan_date"),
                'case.transport_type',
                'case.exceed_qty_flg',
                'mst_scrapper.office_name as office_name',
            ])
            ->leftJoin('mst_scrapper', function($join) use($flgDeleted) {
                $join
                    ->on('mst_scrapper.office_code', '=', 'case.scrapper_office_code')
                    ->where('mst_scrapper.del_flg', '<>', $flgDeleted);
            })
            ->join('plan_case', function($join) use($flgDeleted, $officeCode) {
                $join
                    ->on('plan_case.office_code_from', '=', 'mst_scrapper.office_code')
                    ->where('plan_case.office_code_to', $officeCode)
                    ->where('plan_case.receive_plan_date', '>=', Carbon::now())
                    ->where('plan_case.del_flg', '<>', $flgDeleted);
            })
            ->where('case.sy_office_code', $officeCode)
            ->where('case.del_flg', '<>', $flgDeleted);

        if (!empty($params['case_status'])) {
            $query->whereIn('case.case_status', $params['case_status']);
        }

        if (!empty($params['case_id'])) {
            $query->where('case.case_id', 'like', "%{$params['case_id']}%");
        }

        if (!empty($params['case_no'])) {
            $query->where('case.case_no', 'like', "%{$params['case_no']}%");
        }

        if (!empty($params['mst_scrapper_office_code'])) {
            $query->where('case.scrapper_office_code', $params['mst_scrapper_office_code']);
        }

        if (!empty($params['pallet_case_search']) && count($params['pallet_case_search']) == 1) {
            $palletCaseSearch = $params['pallet_case_search'][0];
            $query->leftJoin('pallet_case', 'case.case_id', '=', 'pallet_case.case_id');
            if ($palletCaseSearch == 1) {
                $query->whereNull('pallet_case.case_id');
            } elseif ($palletCaseSearch == 2) {
                $query->whereNotNull('pallet_case.case_id');
            }
        }

        $query->orderBy('plan_case.receive_plan_date', 'ASC');
        $query->orderBy('case.case_id', 'ASC');

        return $query;
    }

    /**
     * cancel case with case_id
     * @param $caseId
     */
    public function cancelCase($caseId) {
        try {
            $case = Cases::find($caseId);
            $case->case_status = ValueUtil::constToValue('Case.caseStatus.PICK_UP_RECEPTION');
            $case->receive_complete_time = NULL;
            $case->receive_user_id = NULL;
            if ($case->save()) {
                return true;
            }
            return false;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * update case with params
     * @param $params
     * @param $casePicture2
     * @param $caseId
     */
    public function updateCase061($casePicture2, $caseId, $params = []) {
        try {
            DB::beginTransaction();
            $case = Cases::find($caseId);
            $updateParams = [];
            if (strcmp($case->case_no, $params['case_no']) != 0) {
                $caseNoOld = $case->case_no;
                $updateParams = [
                    'case_no' => $params['case_no'],
                    'case_no_old' => $caseNoOld,
                    'case_no_change_flg' => ValueUtil::constToValue('Case.caseNoChangeFlg.WITH_HAND_CORRECTION'),
                    'case_no_change_time' => Carbon::now(),
                    'inspect_stop_flg' => ValueUtil::constToValue('Case.inspectStopFlg.INSPECTION_NOT_POSSIBLE'),
                ];
            }
            if (strcmp($case->case_picture_2, $casePicture2) != 0) {
                $updateParams = array_merge($updateParams, ['case_picture_2' => $casePicture2]);
            }
            $updateParams = array_merge($updateParams, [
                'case_status' => ValueUtil::constToValue('Case.caseStatus.BEFORE_INSPECTION'),
                'receive_complete_time' => Carbon::now(),
                'receive_user_id' => auth()->user()->id
            ]);
            $case->update($updateParams);
            if ($case) {
                DB::commit();
                return true;
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return false;
        }
    }

    public function getCaseByCaseIdForCas081($caseId)
    {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = Cases::select([
                '*',
                DB::raw("
                    DATE_FORMAT(case.collect_complete_time, '%Y/%m/%d %H:%i') as collect_complete_time,
                    DATE_FORMAT(case.deliver_report_time, '%Y/%m/%d %H:%i') as deliver_report_time,
                    DATE_FORMAT(case.receive_complete_time, '%Y/%m/%d %H:%i') as receive_complete_time,
                    DATE_FORMAT(case.inspect_complete_time, '%Y/%m/%d %H:%i') as inspect_complete_time,
                    DATE_FORMAT(case.return_time, '%Y/%m/%d %H:%i') as return_time
                ")
            ])
            ->where('case.del_flg', '<>', $flgDeleted)
            ->with([
                'mst_scrapper' => function($q) use($flgDeleted){
                    $q->where('mst_scrapper.del_flg', '<>', $flgDeleted);
                },
                'car' => function($q) use($flgDeleted){
                    $q->where('car.del_flg', '<>', $flgDeleted)
                    ->orderBy('car.car_id', 'ASC');
                },
                'contract' => function($q) use($flgDeleted) {
                    $q->select([
                        'contract.management_no','contract.case_id',
                        DB::raw("DATE_FORMAT(contract.management_no_print_time, '%Y/%m/%d %H:%i') as management_no_print_time")

                    ])
                    ->where('contract.del_flg', '<>', $flgDeleted);
                },
                'mismatch' => function($q) use($flgDeleted){
                    $q->select(['case_id', 'mismatch_type',
                        DB::raw('sum(mismatch_qty) as mismatch_qty')
                    ])
                    ->where('mismatch.del_flg', '<>', $flgDeleted)
                    ->groupBy(['case_id', 'mismatch_type']);
                },
            ])
            ->find($caseId);

        return $query;
    }

    public function findByCaseNo($caseNo)
    {
        return Cases::where('case_no', $caseNo)
            ->where('del_flg', '!=', ValueUtil::constToValue('Common.delFlg.DELETED'))
            ->first();
    }

    // public function findByCaseNo($caseNo)
    // {
    //     select `case`.* from `case` inner join `mst_user` on `mst_user`.`office_code` = `case`.`sy_office_code` inner join `temp_case` on `temp_case`.`sy_office_code` = `mst_user`.`office_code` where `case`.`case_status` in (3, 4) and `case`.`del_flg` != 1 and `case`.`sy_office_code` = '66666' and not exists (select * from `diff_collect_request` where `case`.`case_id` = `diff_collect_request`.`case_id`) and `case`.`temp_case_id` is null and `case`.`deliver_report_time` is not null and `case`.`case_no_change_flg` = 0 group by `case`.`case_no`
    // }

    public function getCarByCaseId($caseId)
    {
        return Car::join('case', 'case.case_id', 'car.case_id')
            ->where('car.del_flg', '!=', ValueUtil::constToValue('Common.delFlg.DELETED'))
            ->where('car.case_id', $caseId)
            ->orderBy('car.car_id', 'ASC')
            ->get();
    }

    public function findByScrapper($caseId)
    {
        return Cases::join('mst_scrapper', 'mst_scrapper.office_code', 'case.scrapper_office_code')
            ->where('case.del_flg', '!=', ValueUtil::constToValue('Common.delFlg.DELETED'))
            ->where('mst_scrapper.del_flg', '!=', ValueUtil::constToValue('Common.delFlg.DELETED'))
            ->where('case.case_id', $caseId)
            ->where('mst_scrapper.contract_output_flg', 1)
            ->first();
    }

    public function setReturnCase($caseId, array $input)
    {
        $case = Cases::find($caseId);

        $case->return_reason = $input['return_reason'];
        $case->return_time   = $input['return_time'];
        $case->updated_by    = $input['updated_by'];
        $case->updated_at    = $input['updated_at'];

        if ($case && $case->save()) {
            return true;
        }

        return false;
    }

    public function queryCas02($officeCode, $params) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $today = Carbon::today()->toDateString();
        $numOfRequestDaysQuery = "(ABS(DATEDIFF(case.collect_request_time, '{$today}')) + 1)";
        $query = Cases::select([
                'case.case_id',
                'case.case_no',
                'case.case_status',
                DB::raw("
                    DATE_FORMAT(case.collect_request_time, '%Y/%m/%d') as collect_request_time,
                    DATE_FORMAT(case.collect_complete_time, '%Y/%m/%d') as collect_complete_time,
                    DATE_FORMAT(plan_case.collect_plan_date, '%Y/%m/%d') as collect_plan_date,
                    {$numOfRequestDaysQuery} as num_of_request_days
                "),
                'case.sy_office_code',
                'mst_scrapper.office_name as mst_scrapper_office_name',
                'mst_office.office_name as mst_office_office_name',
                'contract.management_no',
                'contract.id as contract_id',
                'contract.contract_pdf'
            ])
            ->leftJoin('mst_scrapper', function($join) use($flgDeleted) {
                $join
                    ->on('case.scrapper_office_code', '=', 'mst_scrapper.office_code')
                    ->where('mst_scrapper.del_flg', '<>', $flgDeleted);
            })
            ->leftJoin('mst_office', function($join) use($flgDeleted) {
                $join
                    ->on('case.tr_office_code', '=', 'mst_office.office_code')
                    ->where('mst_office.del_flg', '<>', $flgDeleted);
            })
            ->leftJoin('plan_case', function($join) use($flgDeleted) {
                $join
                    ->on([
                        ['plan_case.office_code_from', '=', 'mst_scrapper.office_code'],
                        ['plan_case.office_code_to', '=', 'mst_office.office_code']
                    ])
                    ->where('plan_case.del_flg', '<>', $flgDeleted);
            })
            ->leftJoin('contract', function($join) use($flgDeleted) {
                $join
                    ->on('case.case_id', '=', 'contract.case_id')
                    ->where('contract.del_flg', '<>', $flgDeleted);
            })
            ->where('case.tr_office_code', $officeCode)
            ->where('case.del_flg', '<>', $flgDeleted);


        if (!empty($params['case_status'])) {
            $query->whereIn('case.case_status', $params['case_status']);
        }

        if (!empty($params['case_id'])) {
            $query->where('case.case_id', 'like', "%{$params['case_id']}%");
        }

        if (!empty($params['case_no'])) {
            $query->where('case.case_no', 'like', "%{$params['case_no']}%");
        }

        if (!empty($params['mst_scrapper_office_code'])) {
            $query->where('case.scrapper_office_code', $params['mst_scrapper_office_code']);
        }

        if (!empty($params['collect_request_time_from'])) {
            $query->where(DB::raw('DATE(case.collect_request_time)'), '>=', $params['collect_request_time_from']);
        }

        if (!empty($params['collect_request_time_to'])) {
            $query->where(DB::raw('DATE(case.collect_request_time)'), '<=', $params['collect_request_time_to']);
        }

        if (!empty($params['management_no'])) {
            $query->where('contract.management_no', 'like', "%{$params['management_no']}%");
        }

        if (!empty($params['collect_complete_time_from'])) {
            $query->where(DB::raw('DATE(case.collect_complete_time)'), '>=', $params['collect_complete_time_from']);
        }

        if (!empty($params['collect_complete_time_to'])) {
            $query->where(DB::raw('DATE(case.collect_complete_time)'), '<=', $params['collect_complete_time_to']);
        }

        if (!empty($params['num_of_request_days_from'])) {
            $query->where(
                DB::raw($numOfRequestDaysQuery),
                '>=',
                $params['num_of_request_days_from']
            );
        }

        if (!empty($params['num_of_request_days_to'])) {
            $query->where(
                DB::raw($numOfRequestDaysQuery),
                '<=',
                $params['num_of_request_days_to']
            );
        }

        $query->orderBy('case.collect_request_time', 'ASC')
            ->orderBy('case.scrapper_office_code', 'ASC')
            ->orderBy('case.case_id', 'ASC');

        return $query;
    }

    public function findById($caseId)
    {
        return Cases::where('del_flg', '!=', ValueUtil::constToValue('Common.delFlg.DELETED'))
            ->where('case_id', $caseId)
            ->first();
    }

    public function getCaseOfScrapperForCas040(array $scrapperOfficeCodes) {
        return $query = Cases::select([
                DB::raw(
                    "scrapper_office_code,
                    min(collect_request_time) as collect_request_time,
                    count(case_id) as total"
                )
            ])
            ->whereIn('scrapper_office_code', $scrapperOfficeCodes)
            ->where([
                ['case_status', ValueUtil::constToValue('Case.caseStatus.PICK_UP_RECEPTION')],
                ['transport_type', ValueUtil::constToValue('Case.transportType.ADVANCE_PAYMENT')],
                ['del_flg', '<>', ValueUtil::constToValue('Common.delFlg.DELETED')]
            ])
            ->groupBy('scrapper_office_code')
            ->get();
    }

    public function getCaseOfOfficeForCas040(array $officeCodes) {
        return $query = Cases::select([
                DB::raw(
                    "tr_office_code,
                    min(collect_request_time) as collect_request_time,
                    count(case_id) as total"
                )
            ])
            ->whereIn('tr_office_code', $officeCodes)
            ->where([
                ['case_status', ValueUtil::constToValue('Case.caseStatus.PICK_UP_RECEPTION')],
                ['transport_type', ValueUtil::constToValue('Case.transportType.PAYMENT')],
                ['del_flg', '<>', ValueUtil::constToValue('Common.delFlg.DELETED')]
            ])
            ->groupBy('tr_office_code')
            ->get();
    }

    public function findCaseForCas021($caseId, $trOfficeCode)
    {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        return Cases::select([
                'case_id', 
                'scrapper_office_code', 
                'sy_office_code',
                'case_status',
                'case_no',
                'collect_user_id',
                'collect_failure_reason',
                'transport_fee',
                'return_reason',
                'case_picture_1',
                'case_picture_2',
                'case_no_change_flg',
                DB::raw("
                    DATE_FORMAT(collect_complete_time, '%Y/%m/%d %H:%i') as collect_complete_time,
                    DATE_FORMAT(collect_request_link_time, '%Y/%m/%d %H:%i') as collect_request_link_time,
                    DATE_FORMAT(collect_failure_time, '%Y/%m/%d %H:%i') as collect_failure_time,
                    DATE_FORMAT(deliver_report_time, '%Y/%m/%d %H:%i') as deliver_report_time,
                    DATE_FORMAT(return_time, '%Y/%m/%d %H:%i') as return_time
                "),
            ])
            ->where([
                ['case_id', $caseId],
                ['tr_office_code', $trOfficeCode],
                ['del_flg', '<>', $flgDeleted]
            ])
            ->with([
               'mst_scrapper' => function($q) use($flgDeleted){
                    $q->select('id', 'office_code', 'office_name')
                        ->where('del_flg', '<>', $flgDeleted);
                },
                'mst_office_sy' => function($q) use($flgDeleted){
                    $q->select('id', 'office_code', 'office_name')
                        ->where('del_flg', '<>', $flgDeleted);
                },
                'contract' => function($q) use($flgDeleted){
                    $q->select('case_id', 'management_no', DB::raw("DATE_FORMAT(management_no_print_time, '%Y/%m/%d %H:%i') as management_no_print_time"))
                        ->where('del_flg', '<>', $flgDeleted);
                },
                'car' => function($q) use($flgDeleted){
                    $q->select('case_id', 'car_no', 'mechanical_type')
                        ->where('del_flg', '<>', $flgDeleted)
                        ->orderBy('car_id', 'ASC');
                },
            ])
            ->first();
    }

    /**
     * get data display to screen PAL-010
     * @param $paramsSearch
     * @return array
     */
    public function getDataPal010WithStatus($paramsSearch = []) 
    {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        return Cases::select([
            'case_id',
            'case_no',
            'case_status',
        ])
        ->where([
            ['del_flg', '<>', $flgDeleted],
            ['sy_office_code', auth()->user()->office_code],
            ['case_status', ValueUtil::constToValue('Case.caseStatus.COMPLETION_OF_TAKE_BACK_REPORT')]
        ])
        ->orderBy('case_no')
        ->orderBy('case_id')
        ->get()->toArray();
    }

    /**
     * Search Cas120
     * @return array
     */
    public function searchCas120() {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $userOfficeCode = auth()->user()->office_code;
        $query = Cases::select([
            'case.case_id',
            'case.case_no',
        ])  
            ->join('pallet_case', function($join) use($flgDeleted) {
                $join
                    ->on('pallet_case.case_id', '=', 'case.case_id')
                    ->where('pallet_case.del_flg', '<>', $flgDeleted);
            })
            ->join('pallet', function($join) use($flgDeleted) {
                $join
                    ->on('pallet.pallet_id', '=', 'pallet_case.pallet_id')
                    ->where('pallet.del_flg', '<>', $flgDeleted)
                    ->whereNotNull('pallet.receive_complete_time');
            })
            ->join('pallet_transport', function($join) use($flgDeleted, $userOfficeCode) {
                $join
                    ->on('pallet_transport.pallet_transport_id', '=', 'pallet.pallet_transport_id')
                    ->where([
                        ['pallet_transport.del_flg', '<>', $flgDeleted],
                        ['pallet_transport.rp_office_code', $userOfficeCode],
                    ]);
            })
            ->where([
                ['case.del_flg', '<>', $flgDeleted],
                ['case.case_status', ValueUtil::constToValue('Case.caseStatus.COMPLETION_OF_TAKE_BACK_REPORT')],
                ['case.tr_office_code', $userOfficeCode]
            ])
            ->orderBy('case.case_no')
            ->orderBy('case.case_id')
            ->get();
        return $query;
    }

    /**
     * Update data
     * 
     * @param int $id
     * @param array $params
     * @return object|mixed|boolean
     */
    public function updateCase($id, $params) {
        try {
            $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
            $now = Carbon::now();
            $query = Cases::where([
                ['del_flg', '<>', $flgDeleted],
                ['case_id', $id]
            ]);
            $params['updated_at'] = $now;
            $params['updated_by'] = auth()->user()->id;
            DB::beginTransaction();
            $result = $query->update($params);
            if ($result) {
                DB::commit();
            } else {
                DB::rollBack();
            }
            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }



}
