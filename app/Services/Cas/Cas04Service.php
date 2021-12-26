<?php

namespace App\Services\Cas;

use App\Libs\{
    ConfigUtil,
    ValueUtil,
    FileUtil,
    DateUtil,
};
use App\Repositories\{
    MstScrapperRepository,
};
use App\Repositories\PlanCaseRepository;

class Cas04Service
{
    /**
     * Handle sum quantity of plan_case relation with mst_scrapper
     * @param array $datas
     * @return array<int>
     */
    public function handleSumQuantity($datas)
    {
        $sumCaseQty = 0;
        $sumEmptyCaseQty = 0;
        $sumBagQty = 0;
        foreach ($datas as $data) {
            $sumCaseQty += isset($data->case_qty) ? $data->case_qty : 0;
            $sumEmptyCaseQty += isset($data->empty_case_qty) ? $data->empty_case_qty : 0;
            $sumBagQty += isset($data->bag_qty) ? $data->bag_qty : 0;
        }
        return [
            'sumCaseQty' => $sumCaseQty,
            'sumEmptyCaseQty' => $sumEmptyCaseQty,
            'sumBagQty' => $sumBagQty,
        ];
    }

    /**
     * Handle export csv
     * @param array $params
     * @return mixed
     */
    public function handleExportCsv($planCases, array $caseOfOffice, array $caseOfScrapper)
    {
        $group = 'Cas.cas040';
        $header = ConfigUtil::getCsv("{$group}.header");
        $fileName = ConfigUtil::getCsv("{$group}.fileName") . '_' . DateUtil::parseStringFullDateTime() . '.csv';
        $headerVal = array_values($header);
        
        $lstData = [];
        foreach ($planCases as $pCase) {
            $row = [
                'office_name' => $pCase->office_name,
                'memo_jarp_tr' => '',
                'plan_case_transport_type' => '',
                'case_count' => '', 
                'collect_request_time' => '',
                'collect_plan_date' => '',
                'receive_plan_date' => '',
                'case_qty' => '',
                'receive_plan_memo' => '',
                'empty_case_qty' => '',
                'bag_qty' => ''
            ];

            if (!empty($pCase->id)) {
                //scrapper
                if ($pCase['plan_case_type'] == getConstValue('PlanCase.planCaseTypeCas040.SCRAPPER_PLAN_CASE')) {
                    $row['memo_jarp_tr'] = !empty($pCase->memo_jarp) || !empty($pCase->memo_tr) ? 'あり' : '';
                    $row['plan_case_transport_type'] = '持込';
                    $row['case_count'] = !empty($caseOfScrapper[$pCase->office_code]) ? $caseOfScrapper[$pCase->office_code]['total'] : '';
                    $row['collect_request_time'] = !empty($caseOfScrapper[$pCase->office_code]) ? formatDate($caseOfScrapper[$pCase->office_code]['collect_request_time'], 'Y/m/d') : '';
                } 
                //office
                else {
                    $row['plan_case_transport_type'] = '運搬NW利用';
                    $row['case_count'] = !empty($caseOfOffice[$pCase->office_code]) ? $caseOfOffice[$pCase->office_code]['total'] : '';
                    $row['collect_request_time'] = !empty($caseOfOffice[$pCase->office_code]) ? formatDate($caseOfOffice[$pCase->office_code]['collect_request_time'], 'Y/m/d') : '';
                }

                $row['collect_plan_date'] = !empty($pCase->collect_plan_date) ? formatDate($pCase->collect_plan_date, 'Y/m/d') : '';
                $row['receive_plan_date'] = !empty($pCase->receive_plan_date) ? formatDate($pCase->receive_plan_date, 'Y/m/d') : '';
                $row['case_qty'] = number_format($pCase->case_qty);
                $row['receive_plan_memo'] = $pCase->receive_plan_memo;
                $row['empty_case_qty'] = number_format($pCase->empty_case_qty);
                $row['bag_qty'] = number_format($pCase->bag_qty);
            }

            $lstData[] = $row;
        }

        FileUtil::exportCsv($headerVal, $lstData, $fileName);
    }
}