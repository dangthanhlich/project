<?php

namespace App\Repositories;

use App\Libs\ValueUtil;
use App\Models\RecycleReport;
use Illuminate\Support\Facades\{DB, Log};

class RecycleReportRepository
{

    /**
     * Search rep010
     * 
     * @param array $params
     * @return Query
     */
    public function searchRep01($params) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = RecycleReport::select([
                'mst_office.office_name as office_name',
                'recycle_report.report_month',
                'recycle_report.weight_before',
                'recycle_report.weight_after',
                'recycle_report.recycle_rate',
                'recycle_report.total_process_qty',
                'recycle_report.max_process_qty',
                'recycle_report.operation_rate',
                'recycle_report.weight_per_piece',
            ])
            ->leftJoin('mst_office', function($join) use($flgDeleted) {
                $join
                    ->on('mst_office.office_code', '=', 'recycle_report.rp_office_code')
                    ->where('mst_office.del_flg', '<>', $flgDeleted);
            })
            ->where('recycle_report.del_flg', '<>', $flgDeleted);
        if (isset($params['report_month_from'])) {
            $reportMonthFrom = str_replace('-', '', $params['report_month_from']);
            $query->where('recycle_report.report_month', '>=', $reportMonthFrom);
        }
        if (isset($params['report_month_to'])) {
            $reportMonthTo = str_replace('-', '', $params['report_month_to']);
            $query->where('recycle_report.report_month', '<=', $reportMonthTo);
        }
        if (isset($params['rp_office_code'])) {
            $query->where('recycle_report.rp_office_code', $params['rp_office_code']);
        }
        $query
            ->orderBy('recycle_report.rp_office_code', 'ASC')
            ->orderBy('recycle_report.report_month', 'DESC');
        return $query;
    }

    /**
     * Save data for import excel screen REP-010
     * 
     * @param array $recycleReport
     * @return bool
     */
    public function saveImport($recycleReport) {
        try {
            DB::beginTransaction();
            $entity = RecycleReport::query()
                ->where([
                    ['rp_office_code', $recycleReport['rp_office_code']],
                    ['report_month', $recycleReport['report_month']],
                    ['del_flg', '<>', ValueUtil::constToValue('Common.delFlg.DELETED')],
                ])
                ->first();
            if (!$entity) {
                $entity = new RecycleReport();
            }
            $entity->fill($recycleReport);
            if ($entity->save()) {
                DB::commit();
                return true;
            }
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return false;
        }
    }

}
