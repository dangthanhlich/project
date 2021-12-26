<?php

namespace App\Repositories;

use App\Libs\ValueUtil;
use App\Models\{
    MstOffice,
    MstScrapper,
    PlanCase,
};
use Illuminate\Support\Facades\{
    DB,
    Gate,
};
use Carbon\Carbon;

class PlanCaseRepository
{

    /**
     * create plan_case
     *
     * @param array $data
     * @param string $flg (CAS-010/CAS-040)
     */
    public function create(array $data, String $flg)
    {
        $planCase = new PlanCase();
        $planCase->office_code_to = auth()->user()->office_code;
        $planCase->office_code_from = $data['office_code_from'];
        if ($flg === 'CAS-010') {
            $planCase->transport_type = ValueUtil::constToValue('PlanCase.transportType.PAYMENT');
        }
        if ($flg === 'CAS-040') {
            $planCase->transport_type = ValueUtil::constToValue('PlanCase.transportType.ADVANCE_PAYMENT');
        }
        $planCase->collect_plan_date = NULL;
        $planCase->receive_plan_date = $data['receive_plan_date'];
        $planCase->collect_plan_memo = NULL;
        $planCase->receive_plan_memo = $data['receive_plan_memo'];
        $planCase->case_qty = $data['case_qty'];
        $planCase->empty_case_qty = $data['empty_case_qty'];
        $planCase->bag_qty = $data['bag_qty'];
        $planCase->plan_date_adjusted_flg = NULL;
        return $planCase->save();
    }

    /**
     * create plan_case
     *
     * @param integer $id
     * @param array $data
     * @param string $flg (CAS-010/CAS-040)
     */
    public function update($id, array $data)
    {
        $planCase = PlanCase::find($id);
        $planCase->receive_plan_date = $data['receive_plan_date'];
        $planCase->receive_plan_memo = $data['receive_plan_memo'];
        $planCase->case_qty = $data['case_qty'];
        $planCase->empty_case_qty = $data['empty_case_qty'];
        $planCase->bag_qty = $data['bag_qty'];
        return $planCase->save();
    }

    /**
     * Get plan_case with mst_scrapper by id
     * @param int $id
     * @return object
     */
    public function getPlanCaseWithMstScrapperById($id)
    {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = PlanCase::select([
            'plan_case.id',
            'plan_case.office_code_from',
            'plan_case.transport_type',
            DB::raw("DATE_FORMAT(plan_case.collect_plan_date, '%Y/%m/%d') as collect_plan_date"),
            DB::raw("DATE_FORMAT(plan_case.receive_plan_date, '%Y/%m/%d') as receive_plan_date"),
            'plan_case.case_qty',
            'plan_case.collect_plan_memo',
            'plan_case.receive_plan_memo',
            'plan_case.plan_date_adjusted_flg',
            'plan_case.empty_case_qty',
            'plan_case.bag_qty',
            DB::raw("CONCAT(mst_scrapper.office_code, ' - ', mst_scrapper.office_name) as office_code_name"),
        ])
            ->leftJoin('mst_scrapper', function ($join) use ($flgDeleted) {
                $join
                    ->on('mst_scrapper.office_code', '=', 'plan_case.office_code_from')
                    ->where('mst_scrapper.del_flg', '<>', $flgDeleted);
            })
            ->where([
                ['plan_case.del_flg', '<>', $flgDeleted],
                ['plan_case.id', $id],
            ])
            ->first();
        return $query;
    }

    /**
     * Search cas040
     * @param $params
     * @return array|object
     */
    public function search040($params)
    {
        $transportType = $params['transport_type'] ?? getConstValue('PlanCase.transportTypeCas040.ALL');

        if (empty(getList('PlanCase.transportTypeCas040')[$transportType])) {
            return null;
        }

        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $userOfficeCode = auth()->user()->office_code;

        $transportType1 = getConstValue('PlanCase.transportTypeCas040.ALL');
        $transportType2 = getConstValue('PlanCase.transportTypeCas040.BRING_IN');
        $transportType3 = getConstValue('PlanCase.transportTypeCas040.TRANSPORTATION_NW');
        
        $select = [
                'plan_case.id',
                'plan_case.case_qty',
                'plan_case.empty_case_qty',
                'plan_case.bag_qty',
                'plan_case.collect_plan_date',
                'plan_case.receive_plan_date',
                'plan_case.receive_plan_memo',
            ];

        if ($transportType == $transportType1 || $transportType == $transportType2) {
            $params['select'] = array_merge(
                $select, 
                [
                    'mst_scrapper.office_code as office_code',
                    'mst_scrapper.office_name',
                    'mst_scrapper.id as scrapper_id',
                    'mst_scrapper.memo_jarp',
                    'mst_scrapper.memo_tr',
                    'mst_scrapper.last_deliver_report_time',
                    'mst_scrapper.teach_complete_flg',
                    DB::raw('1 as plan_case_type')
                ]
            );

            $query = $this->queryMstScrapperWithPlanCaseCas040($params);

            if ($transportType == $transportType2) {
                return $query;
            }
        }

        if ($transportType == $transportType1 || $transportType == $transportType3) {
            $params['select'] = array_merge(
                $select, 
                [
                    'mst_office.office_code as office_code',
                    'mst_office.office_name',
                    DB::raw('
                        null as scrapper_id,
                        null as memo_jarp,
                        null as memo_tr,
                        null as last_deliver_report_time,
                        null as teach_complete_flg,
                        2 as plan_case_type
                    '),
                ]
            );

            $query2 = $this->queryPlanCaseWithMstOfficeCas040($params);

            if ($transportType == $transportType3) {
                return $query2;
            }
        }

        if ($transportType == $transportType1) {
            if (in_array($params['schedule_picked_up'], [getConstValue('PlanCase.schedulePickedUpCas040.ALL'), getConstValue('PlanCase.schedulePickedUpCas040.REGISTERED_ONLY')])) {
                $query->union($query2)
                    ->orderBy('plan_case_type', 'ASC')
                    ->orderBy('receive_plan_date', 'ASC')
                    ->orderBy('receive_plan_memo', 'ASC')
                    ->orderBy('id');
            }

            return $query;
        }

        return null;
    }

    public function getListScrapper()
    {
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

    public function queryPlanCaseWithMstOfficeCas040(array $params = []) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $userOfficeCode = auth()->user()->office_code;

        $query = PlanCase::select($params['select'] ?? 'mst_office.id')
            ->join('mst_office', function($join) use($flgDeleted) {
                $join
                ->on('plan_case.office_code_to', '=', 'mst_office.office_code')
                ->where('mst_office.del_flg', '<>', $flgDeleted);
            })
            ->whereExists(function ($query) use ($userOfficeCode, $flgDeleted, $params) {
               $query->select('id')
                    ->from('mst_scrapper')
                    ->whereColumn('plan_case.office_code_to', 'mst_scrapper.tr_office_code')
                    ->where([
                        ['mst_scrapper.sy_office_code', $userOfficeCode],
                        ['mst_scrapper.del_flg', '<>', $flgDeleted]
                    ]);

                if (!empty($params['scrapper_office_code'])) {
                    $query->where('mst_scrapper.office_code', $params['scrapper_office_code']);
                }

                $query->limit(1);
            })
            ->where([
                ['plan_case.transport_type', getConstValue('PlanCase.transportType.PAYMENT')],
                ['plan_case.del_flg', '<>', $flgDeleted]
            ]);

        if (!empty($params['receive_plan_date_from'])) {
            $query->where(DB::raw('DATE(plan_case.receive_plan_date)'), '>=', $params['receive_plan_date_from']);
        }

        if (!empty($params['receive_plan_date_to'])) {
            $query->where(DB::raw('DATE(plan_case.receive_plan_date)'), '<=', $params['receive_plan_date_to']);
        }

        $today = Carbon::now()->format('Y/m/d');
        if ($params['schedule_picked_up'] == getConstValue('PlanCase.schedulePickedUpCas040.REGISTERED_ONLY')) {
            $query->where(DB::raw('DATE(plan_case.receive_plan_date)'), '>=', $today);
        } elseif ($params['schedule_picked_up'] == getConstValue('PlanCase.schedulePickedUpCas040.UNREGISTERED_ONLY')) {
            $query->where(function($q) use ($today) {
                $q->where(DB::raw('DATE(plan_case.receive_plan_date)'), '<', $today)
                ->orWhereNull('plan_case.receive_plan_date');
            });
        }

        $query->orderBy('plan_case.receive_plan_date', 'ASC')
            ->orderBy('plan_case.receive_plan_memo', 'ASC')
            ->orderBy('plan_case.id');

        return $query;
    }

    public function queryMstScrapperWithPlanCaseCas040(array $params = [])
    {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $userOfficeCode = auth()->user()->office_code;

        $query = MstScrapper::select($params['select'] ?? 'mst_scrapper.id')
            ->leftjoin('plan_case', function($join) use($flgDeleted) {
                $join->on('mst_scrapper.office_code', '=', 'plan_case.office_code_from')
                    ->where('plan_case.del_flg', '<>', $flgDeleted);
            })
            ->join('case', function($join) use ($flgDeleted) {
                $join->on('mst_scrapper.office_code', '=', 'case.scrapper_office_code')
                    ->where('case_status', getConstValue('Case.caseStatus.PICK_UP_RECEPTION'))
                    ->where('case.del_flg', '<>', $flgDeleted);
            })
            ->where([
                ['mst_scrapper.sy_office_code', $userOfficeCode],
                ['mst_scrapper.transport_type', getConstValue('MstScrapper.transportType.ADVANCE_PAYMENT')],
                ['mst_scrapper.del_flg', '<>', $flgDeleted]
            ]);

        if (!empty($params['receive_plan_date_from'])) {
            $query->where(DB::raw('DATE(plan_case.receive_plan_date)'), '>=', $params['receive_plan_date_from']);
        }

        if (!empty($params['receive_plan_date_to'])) {
            $query->where(DB::raw('DATE(plan_case.receive_plan_date)'), '<=', $params['receive_plan_date_to']);
        }

        if (!empty($params['scrapper_office_code'])) {
            $query->where('mst_scrapper.office_code', $params['scrapper_office_code']);
        }

        $today = Carbon::now()->format('Y/m/d');
        if ($params['schedule_picked_up'] == getConstValue('PlanCase.schedulePickedUpCas040.REGISTERED_ONLY')) {
            $query->where(DB::raw('DATE(plan_case.receive_plan_date)'), '>=', $today);
        } elseif ($params['schedule_picked_up'] == getConstValue('PlanCase.schedulePickedUpCas040.UNREGISTERED_ONLY')) {
            $query->where(function($q) use ($today) {
                $q->where(DB::raw('DATE(plan_case.receive_plan_date)'), '<', $today)
                ->orWhereNull('plan_case.receive_plan_date');
            });
        }

        $query->groupBy('mst_scrapper.id','plan_case.id')
            ->orderBy('plan_case.receive_plan_date', 'ASC')
            ->orderBy('plan_case.receive_plan_memo', 'ASC')
            ->orderBy('plan_case.id');

        return $query;
    }
}
