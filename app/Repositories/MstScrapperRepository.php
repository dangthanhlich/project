<?php

namespace App\Repositories;

use App\Models\MstScrapper;
use App\Libs\ValueUtil;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class MstScrapperRepository {
    /**
     * Search mst010
     * @param $params
     * @return array|object
     */
    public function searchMst010($params) {
        $companyOfficeCode = isset($params['company_office_code']) ? $params['company_office_code'] : null;
        $officeName = isset($params['office_name']) ? $params['office_name'] : null;
        $transportType = isset($params['transport_type']) ? $params['transport_type'] : null;
        $officeCode = isset($params['office_code']) ? $params['office_code'] : null;
        $officeAddressSearch = isset($params['office_address_search']) ? $params['office_address_search'] : null;
        $officeTel = isset($params['office_tel']) ? $params['office_tel'] : null;
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $userOfficeCode = auth()->user()->office_code;
        $query = MstScrapper::where('mst_scrapper.del_flg', '<>', $flgDeleted);
        if (strlen($companyOfficeCode) > 0) {
            $query->where(function($q) use($companyOfficeCode) {
                $q
                    ->orWhere('mst_scrapper.company_code', 'like', "%$companyOfficeCode%")
                    ->orWhere('mst_scrapper.office_code', 'like', "%$companyOfficeCode%");
            });
        }
        if (strlen($officeName) > 0) {
            $query->where('mst_scrapper.office_name', 'like', "%$officeName%");
        }
        if (!empty($transportType)) {
            $query->whereIn('mst_scrapper.transport_type', array_values($transportType));
        }
        if (strlen($officeCode) > 0) {
            $query->where('mst_scrapper.sy_office_code', $officeCode);
        }
        if (strlen($officeAddressSearch) > 0) {
            $query->where('mst_scrapper.office_address_search', 'like', "%$officeAddressSearch%");
        }
        if (strlen($officeTel) > 0) {
            $query->where('mst_scrapper.office_tel', 'like', "%$officeTel%");
        }
        $query = $query
            ->select([
                'mst_scrapper.id',
                'mst_scrapper.office_code',
                'mst_scrapper.office_name',
                'mst_scrapper.office_tel',
                'mst_scrapper.pic_name',
                'mst_scrapper.transport_type',
                'mst_scrapper.tr_office_code',
                'mst_scrapper.sy_office_code',
                'mstOfficeTr.office_code as mstOfficeTr_office_code',
                'mstOfficeTr.office_name as mstOfficeTr_office_name',
                'mstOfficeSy.office_code as mstOfficeSy_office_code',
                'mstOfficeSy.office_name as mstOfficeSy_office_name',
            ])
            ->leftJoin('mst_office as mstOfficeTr', function($join) use($flgDeleted) {
                $join
                    ->on('mstOfficeTr.office_code', '=', 'mst_scrapper.tr_office_code')
                    ->where('mstOfficeTr.del_flg', '<>', $flgDeleted);
            })
            ->leftJoin('mst_office as mstOfficeSy', function($join) use($flgDeleted) {
                $join
                    ->on('mstOfficeSy.office_code', '=', 'mst_scrapper.sy_office_code')
                    ->where('mstOfficeSy.del_flg', '<>', $flgDeleted);
            })
            ->orderBy('mst_scrapper.id', 'asc');
        if (Gate::check(ValueUtil::get('MstUser.permission')['NW'])) {
            // user_type = 3 & tr_office_flg = 1
            $query
                ->addSelect(['mstOfficeTrWithUser.office_code as mstOfficeTrWithUser_office_code'])
                ->leftJoin('mst_office as mstOfficeTrWithUser', function($join) use($flgDeleted) {
                    $join
                        ->on('mstOfficeTrWithUser.office_code', '=', 'mst_scrapper.tr_office_code')
                        ->where('mstOfficeTrWithUser.del_flg', '<>', $flgDeleted);
                })
                ->join('mst_user', function($join) use($flgDeleted, $userOfficeCode) {
                    $join
                        ->on('mst_user.office_code', '=', 'mstOfficeTrWithUser.office_code')
                        ->where([
                            ['mst_user.del_flg', '<>', $flgDeleted],
                            ['mst_user.office_code', $userOfficeCode]
                        ]);
                });
        }
        if (Gate::check(ValueUtil::get('MstUser.permission')['SY'])) {
            // user_type = 3 & sy_office_flg = 1
            $query
                ->addSelect(['mstOfficeSyWithUser.office_code as mstOfficeSyWithUser_office_code'])
                ->leftJoin('mst_office as mstOfficeSyWithUser', function($join) use($flgDeleted) {
                    $join
                        ->on('mstOfficeSyWithUser.office_code', '=', 'mst_scrapper.sy_office_code')
                        ->where('mstOfficeSyWithUser.del_flg', '<>', $flgDeleted);
                })
                ->join('mst_user', function($join) use($flgDeleted, $userOfficeCode) {
                    $join
                        ->on('mst_user.office_code', '=', 'mstOfficeSyWithUser.office_code')
                        ->where([
                            ['mst_user.del_flg', '<>', $flgDeleted],
                            ['mst_user.office_code', $userOfficeCode]
                        ]);
                });
        }
        if (Gate::check(ValueUtil::get('MstUser.permission')['RP'])) {
            // user_type = 3 & rp_office_flg = 1
            $query
                ->addSelect(['mstOfficeSyWithUserRp.office_code as mstOfficeSyWithUserRp_office_code'])
                ->leftJoin('mst_office as mstOfficeSyWithUserRp', function($join) use($flgDeleted) {
                    $join
                        ->on('mstOfficeSyWithUserRp.office_code', '=', 'mst_scrapper.sy_office_code')
                        ->where('mstOfficeSyWithUserRp.del_flg', '<>', $flgDeleted);
                })
                ->join('mst_user', function($join) use($flgDeleted, $userOfficeCode) {
                    $join
                        ->on('mst_user.office_code', '=', 'mstOfficeSyWithUserRp.rp_office_code')
                        ->where([
                            ['mst_user.del_flg', '<>', $flgDeleted],
                            ['mst_user.office_code', $userOfficeCode]
                        ]);
                });
        }
        $query->groupBy('mst_scrapper.id');
        return $query;
    }

    /**
     * Get mst_scrapper with mst_ofice by mst_scrapper.id
     * @param $mstScrapperId
     * @return object|array
     */
    public function getScrapperWithOfficeById($id) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = MstScrapper::select([
                'mst_scrapper.id',
                'mst_scrapper.office_code',
                'mst_scrapper.office_name',
                'mst_scrapper.office_name_kana',
                'mst_scrapper.office_address_zip',
                'mst_scrapper.office_address_pref',
                'mst_scrapper.office_address_city',
                'mst_scrapper.office_address_town',
                'mst_scrapper.office_address_block',
                'mst_scrapper.office_address_building',
                'mst_scrapper.office_tel',
                'mst_scrapper.office_fax',
                'mst_scrapper.pic_name',
                'mst_scrapper.pic_name_kana',
                'mst_scrapper.pic_tel',
                'mst_scrapper.transport_type',
                'mst_scrapper.tr_office_code',
                'mst_scrapper.sy_office_code',
                'mst_scrapper.memo_jarp',
                'mst_scrapper.memo_jarp_updated_at',
                DB::raw("DATE_FORMAT(mst_scrapper.memo_jarp_updated_at, '%Y/%m/%d %H:%i') as memo_jarp_updated_at"),
                'mst_scrapper.memo_tr',
                DB::raw("DATE_FORMAT(mst_scrapper.memo_tr_updated_at, '%Y/%m/%d %H:%i') as memo_tr_updated_at"),
                'mstOfficeTr.id as mstOfficeTr_id',
                'mstOfficeTr.office_code as mstOfficeTr_office_code',
                'mstOfficeTr.office_name as mstOfficeTr_office_name',
                'mstOfficeSy.id as mstOfficeSy_id',
                'mstOfficeSy.office_code as mstOfficeSy_office_code',
                'mstOfficeSy.office_name as mstOfficeSy_office_name',
            ])
            ->join('mst_office as mstOfficeTr', function($join) use($flgDeleted) {
                $join
                    ->on('mstOfficeTr.office_code', '=', 'mst_scrapper.tr_office_code')
                    ->where('mstOfficeTr.del_flg', '<>', $flgDeleted);
            })
            ->join('mst_office as mstOfficeSy', function($join) use($flgDeleted) {
                $join
                    ->on('mstOfficeSy.office_code', '=', 'mst_scrapper.sy_office_code')
                    ->where('mstOfficeSy.del_flg', '<>', $flgDeleted);
            })
            ->where([
                ['mst_scrapper.del_flg', '<>', $flgDeleted],
                ['mst_scrapper.id', $id],
            ])
            ->first();
        return $query;
    }

    /**
     * Get mst_scrapper with office_code and office_name by id
     * @param int $id
     * @return object
     */
    public function getOfficeCodeNameById($id) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = MstScrapper::select([
            DB::raw("CONCAT(mst_scrapper.office_code, ' - ', mst_scrapper.office_name) as office_code_name"),
            'mst_scrapper.office_code',
        ])
            ->where([
                ['mst_scrapper.del_flg', '<>', $flgDeleted],
                ['mst_scrapper.id', $id],
            ])
            ->first();
        return $query;
    }

    /**
     * Get mst_srcapper management
     * @param string $officeCode
     * @return object
     */
    public function getScrapperManagement($officeCode) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $defaultOfficeNumber = ValueUtil::get('MstScrapper.defaultOfficeNumber');
        $officeCodeCondition = $officeCode . $defaultOfficeNumber;
        $query = MstScrapper::where([
            ['mst_scrapper.del_flg', '<>', $flgDeleted],
            ['mst_scrapper.office_code', $officeCodeCondition],
        ])
            ->first();
        return $query;
    }

    /**
     * Get list office_code by office_code of user login
     * @return array
     */
    public function getListOfficeByOfficeCodeOfUser() {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $officeCodeUser = auth()->user()->office_code;
        $query = MstScrapper::select([
                DB::raw("CONCAT(mst_scrapper.office_code, ' - ', mst_scrapper.office_name) as office_code_name"),
                'mst_scrapper.office_code as office_code',
            ])
            ->where([
                ['mst_scrapper.del_flg', '<>', $flgDeleted],
                ['mst_scrapper.tr_office_code', $officeCodeUser]
            ])
            ->orderBy('mst_scrapper.office_code', 'ASC')
            ->pluck('office_code_name', 'mst_scrapper.office_code')
            ->toArray();
        return $query;
    }

    /**
     * Search cas010
     * @param array $params
     * @return array
     */
    public function searchCas010($params) {
        $officeCodeUser = auth()->user()->office_code;
        $sysDate = Carbon::now()->format('Y-m-d');
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $planCaseConditions = [
            ['plan_case.del_flg', '<>', $flgDeleted],
        ];
        $isJoinPlanCase = false;
        $query = MstScrapper::where('mst_scrapper.del_flg', '<>', $flgDeleted);
        $query
            ->select([
                'mst_scrapper.id',
                'mst_scrapper.office_code',
                'mst_scrapper.office_name',
                'mst_scrapper.memo_jarp',
                'mst_scrapper.memo_tr',
                'mst_scrapper.office_address_search',
                'mst_scrapper.office_tel',
                'mst_scrapper.last_deliver_report_time',
                'mst_scrapper.teach_complete_flg',
            ])
            ->with([
                'case' => function($q) use($flgDeleted) {
                    return $q
                        ->select([
                            'case.case_id',
                            'case.scrapper_office_code',
                            DB::raw("DATE_FORMAT(case.collect_request_time, '%Y/%m/%d') as collect_request_time"),
                        ])
                        ->where([
                            ['case.del_flg', '<>', $flgDeleted],
                            ['case.case_status', ValueUtil::constToValue('Case.caseStatus.PICK_UP_RECEPTION')],
                        ])
                        ->orderBy('case.collect_request_time');
                }
            ])
            ->where('mst_scrapper.tr_office_code', $officeCodeUser)
            ->whereIn('mst_scrapper.office_code', function($q) use($flgDeleted) {
                $q
                    ->from('case')
                    ->select([
                        'case.scrapper_office_code',
                    ])
                    ->whereColumn('case.scrapper_office_code', 'mst_scrapper.office_code')
                    ->where([
                        ['case.del_flg', '<>', $flgDeleted],
                        ['case.case_status', ValueUtil::constToValue('Case.caseStatus.PICK_UP_RECEPTION')],
                    ]);
            })
            ->orderBy('mst_scrapper.office_code');
        if (isset($params['office_code']) && strlen($params['office_code']) > 0) {
            $query->where('mst_scrapper.office_code', $params['office_code']);
        }
        if (isset($params['office_address_search']) && strlen($params['office_address_search']) > 0) {
            $query->where('mst_scrapper.office_address_search', 'like', "%{$params['office_address_search']}%");
        }
        if (isset($params['schedule_picked_up']) && $params['schedule_picked_up'] == ValueUtil::constToValue('PlanCase.schedulePickedUp.REGISTERED_ONLY')) {
            // schedule_picked_up = 2
            $planCaseConditions[] = [DB::raw('DATE(plan_case.collect_plan_date)'), '>=', $sysDate];
        }
        if (isset($params['schedule_picked_up']) && $params['schedule_picked_up'] == ValueUtil::constToValue('PlanCase.schedulePickedUp.UNREGISTERED_ONLY')) {
            // schedule_picked_up = 3
            $planCaseConditions[] = [DB::raw('DATE(plan_case.collect_plan_date)'), '<', $sysDate];
        }
        if (isset($params['schedule_picked_up']) && $params['schedule_picked_up'] == ValueUtil::constToValue('PlanCase.schedulePickedUp.UNADJUSTED_ONLY')) {
            // schedule_picked_up = 4
            $planCaseConditions[] = ['plan_case.plan_date_adjusted_flg', ValueUtil::constToValue('PlanCase.planDateAdjustedFlg.UNADJUSTED')];
        }
        if (isset($params['office_code']) && strlen($params['office_code']) > 0) {
            $planCaseConditions[] = ['plan_case.office_code_from', $params['office_code']];
        }
        if (isset($params['collect_plan_date_from']) && strlen($params['collect_plan_date_from'])) {
            $planCaseConditions[] = [DB::raw('DATE(plan_case.collect_plan_date)'), '>=', $params['collect_plan_date_from']];
        }
        if (isset($params['collect_plan_date_to']) && strlen($params['collect_plan_date_to'])) {
            $planCaseConditions[] = [DB::raw('DATE(plan_case.collect_plan_date)'), '<=', $params['collect_plan_date_to']];
        }
        $query
            ->addSelect([
                'plan_case.id as planCase_id',
                DB::raw("DATE_FORMAT(plan_case.collect_plan_date, '%Y/%m/%d') as collect_plan_date"),
                'plan_case.plan_date_adjusted_flg as plan_date_adjusted_flg',
                'plan_case.collect_plan_memo as collect_plan_memo',
                'plan_case.case_qty as case_qty',
                'plan_case.empty_case_qty as empty_case_qty',
                'plan_case.bag_qty as bag_qty',
            ])
            ->leftJoin('plan_case', function($join) use($planCaseConditions) {
                $join
                    ->on('plan_case.office_code_from', '=', 'mst_scrapper.office_code')
                    ->where($planCaseConditions);
            })
            ->orderBy('plan_case.collect_plan_date')
            ->orderBy('plan_case.id');
        return $query;
    }

    /**
     * Get mst_scrapper by office_code
     * @param string $officeCode
     * @return object
     */
    public function getScrapperByOfficeCode($officeCode) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = MstScrapper::select([
            DB::raw("CONCAT(mst_scrapper.office_code, ' - ', mst_scrapper.office_name) as office_code_name"),
            'mst_scrapper.office_code',
            'mst_scrapper.transport_type',
        ])
            ->where([
                ['mst_scrapper.del_flg', '<>', $flgDeleted],
                ['mst_scrapper.office_code', $officeCode],
            ])
            ->first();
        return $query;
    }

    /**
     * Get list office_code by office_code of user login follow mst_scrapper.sy_office_code 
     * @return array
     */
    public function getListOfficeWithSyOfficeCodeByOfficeCodeOfUser() {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $officeCodeUser = auth()->user()->office_code;
        $query = MstScrapper::select([
                DB::raw("CONCAT(mst_scrapper.office_code, ' - ', mst_scrapper.office_name) as office_code_name"),
                'mst_scrapper.office_code as office_code',
            ])
            ->where([
                ['mst_scrapper.del_flg', '<>', $flgDeleted],
                ['mst_scrapper.sy_office_code', $officeCodeUser]
            ])
            ->orderBy('mst_scrapper.office_code', 'ASC')
            ->pluck('office_code_name', 'mst_scrapper.office_code')
            ->toArray();
        return $query;
    }

    /**
     * Get import collect request by conditions
     * @param array $params
     * @return object
     */
    public function getCaseTempCaseFromMstScrapper($params = [], $screen = 'CAS-060') {
        try {
            $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
            $officeCode = isset($params['office_code']) ? $params['office_code'] : null;
            $caseStatus = [
                ValueUtil::constToValue('Case.caseStatus.PICK_UP_RECEPTION'),
                ValueUtil::constToValue('Case.caseStatus.BEFORE_INSPECTION')
            ];
            if ($screen == 'CAS-062') {
                $caseStatus = [
                    ValueUtil::constToValue('Case.caseStatus.BEFORE_INSPECTION')
                ];
            } 
            $transportType = ValueUtil::constToValue('Case.transportType.ADVANCE_PAYMENT');
            $query = MstScrapper::select([
                    'mst_scrapper.id',
                    'mst_scrapper.office_code',
                ])
                ->where([
                    ['mst_scrapper.del_flg', '<>', $flgDeleted],
                    ['mst_scrapper.office_code', $officeCode],
                ])
                ->with([
                    'case' => function($q) use($flgDeleted, $caseStatus, $transportType) {
                        return $q
                            ->select([
                                'case.case_id',
                                'case.case_no',
                                'case.receive_complete_time',
                                'case.sy_office_code',
                                'case.scrapper_office_code',
                                'case.case_status'
                            ])
                            ->where([
                                ['case.del_flg', '<>', $flgDeleted],
                                ['case.transport_type', $transportType],
                            ])
                            ->whereIn('case.case_status', $caseStatus)
                            ->with([
                                'contract' => function($q) use($flgDeleted) {
                                    return $q
                                        ->where([
                                            ['contract.del_flg', '<>', $flgDeleted],
                                        ])
                                        ->whereNull('contract.sign_sy');
                                }
                            ])
                            ->orderBy('case.receive_complete_time', 'asc');
                    }     
                ])
                ->with([
                    'temp_case' => function($q) use($flgDeleted, $caseStatus, $transportType) {
                        return $q
                            ->select([
                                'temp_case.temp_case_id',
                                'temp_case.temp_case_no',
                                'temp_case.receive_complete_time',
                                'temp_case.sy_office_code',
                                'temp_case.scrapper_office_code',
                                'temp_case.case_status'
                            ])
                            ->where([
                                ['temp_case.del_flg', '<>', $flgDeleted],
                                ['temp_case.transport_type', $transportType],
                            ])
                            ->whereIn('temp_case.case_status', $caseStatus)
                            ->with([
                                'contract' => function($q) use($flgDeleted) {
                                    return $q
                                        ->where([
                                            ['contract.del_flg', '<>', $flgDeleted],
                                        ])
                                        ->whereNull('contract.sign_sy');
                                }
                            ])
                            ->orderBy('temp_case.receive_complete_time', 'asc');
                    }
                ])->first();
            return $query;
        } catch (\Exception $error) {
            throw $error;
        }
    }

    /**
     * Get mst_scrapper by management_no
     * @param string $managementNo
     * @return object
     */
    public function getScrapperByManagementNo($managementNo) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = MstScrapper::select([
            DB::raw("CONCAT(mst_scrapper.office_code, ' - ', mst_scrapper.office_name) as office_code_name"),
        ])
            ->join('case', function($join) use($flgDeleted) {
                $join
                    ->on('case.scrapper_office_code', '=', 'mst_scrapper.office_code')
                    ->where('case.del_flg', '<>', $flgDeleted);
            })
            ->join('contract', function($join) use($flgDeleted, $managementNo) {
                $join
                    ->on('contract.case_id', '=', 'case.case_id')
                    ->where([
                        ['contract.del_flg', '<>', $flgDeleted],
                        ['contract.management_no', $managementNo],
                    ]);
            })
            ->where('mst_scrapper.del_flg', '<>', $flgDeleted)
            ->first();
        return $query;
    }

    /**
     * Get mst scrappers
     * @param array $criteria
     * @param Closure $builder
     * @return collection
     */
    public function findBy(array $criteria = [], Closure $builder = null)
    {
        $criteria[] = ['mst_scrapper.del_flg', '<>', ValueUtil::constToValue('Common.delFlg.DELETED')];
        $query = MstScrapper::where($criteria);

        if (is_callable($builder)) {
            $builder($query);
        }

        return $query->get();
    }
}