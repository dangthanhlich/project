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

class Cas01Service 
{
    /**
     * Handle sum quantity of plan_case relation with mst_scrapper
     * @param array $datas
     * @return array<int>
     */
    public function handleSumQuantity($datas) {
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
    public function handleExportCsv($params) {
        $mstScrapperRepository = new MstScrapperRepository();
        $group = 'Cas.cas010';
        $options = ConfigUtil::getCsv("{$group}.options");
        $fileName = ConfigUtil::getCsv("{$group}.fileName") . '_' . DateUtil::parseStringFullDateTime() . '.csv';
        $header = array_column($options, 'title');
        $lstData = [];
        $mstScrappers = $mstScrapperRepository->searchCas010($params)->get()->toArray();
        foreach ($mstScrappers as $mstScrapper) {
            $arrData = $options;
            foreach ($arrData as $key => &$value) {
                if ($key == 'mst_scrapper_office_name') {
                    $value = $mstScrapper['office_name'];
                } else if ($key == 'memo_jarp_tr') {
                    $value = !empty($mstScrapper['memo_jarp']) || !empty($mstScrapper['memo_tr']) 
                        ? 'あり' : '';
                } else if ($key == 'case_count') {
                    $value = count($mstScrapper['case']);
                } else if ($key == 'collect_request_time') {
                    $value = isset($mstScrapper['case'][0]['collect_request_time']) 
                        ? $mstScrapper['case'][0]['collect_request_time'] : '';
                } else {
                    if (isset($mstScrapper)) {
                        if (isset($value['number_format'])) {
                            $value = number_format($mstScrapper[$key]);
                        } else {
                            $value = $mstScrapper[$key];
                        }
                    } else {
                        $value = '';
                    }
                }
            }
            $lstData[] = $arrData;
        }
        FileUtil::exportCsv($header, $lstData, $fileName);
    }

}