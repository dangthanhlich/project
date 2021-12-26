<?php

namespace App\Services\Pal;

use App\Libs\{
    FileUtil,
    ValueUtil,
    ConfigUtil,
    DateUtil
};
use App\Repositories\{
    PalTransportRepository
};

class Pal05Service
{
    /**
     * Handle export csv
     * @param array $params
     * @return mixed
     */
    public function handleExportCsv($params)
    {
        $palTransportRepository = new PalTransportRepository();
        $group = 'Pal.pal050';
        $options = ConfigUtil::getCsv("{$group}.options");
        $fileName = ConfigUtil::getCsv("{$group}.fileName") . '_' . DateUtil::parseStringFullDateTime() . '.csv';
        $header = array_column($options, 'title');
        $listData = [];
        $officeCode = auth()->user()->office_code;
        $items = $palTransportRepository->queryPal050($officeCode, $params)->get()->toArray();
        
        foreach ($items as $item) {
            $totalCase = 0;
            foreach ($item['pallets'] as $pallet) {
                $totalCase += count($pallet['cases']);
            }
            $arrData = $options;
            foreach ($arrData as $key => &$value) {
                if ($key == 'deliver_complete_time') {
                    $value = date('Y/m/d', strtotime($item['deliver_complete_time'])) ?? '';
                } else if ($key == 'sy_office_code') {
                    $value = end($item['pallets'])['pallet_mst_office']['office_name'] ?? '';
                } else if ($key == 'pallet_quantity') {
                    $value = count($item['pallets']) ?? '';
                } else if ($key == 'case_quantity') {
                    $value = $totalCase;
                } else if ($key == 'receive_complete_time') {
                    $value = isset(end($item['pallets'])['receive_complete_time']) ? date('Y/m/d', strtotime(end($item['pallets'])['receive_complete_time'])) : '';
                } else if ($key == 'car_no') {
                    $value = $item['car_no'] ?? '';
                } else if ($key == 'rp_office_code') {
                    $value = $palTransportRepository->getMstOfficeByRpOfficeCode($item['rp_office_code']) ?? '';
                }
            }
            $listData[] = $arrData;
        }
        FileUtil::exportCsv($header, $listData, $fileName);
    }
}
