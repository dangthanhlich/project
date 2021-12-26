<?php

namespace App\Services\Rep;

use App\Libs\{
    ConfigUtil,
    FileUtil,
    DateUtil,
    ValueUtil,
};
use App\Repositories\{
    RecycleReportRepository,
};
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use ImportExcel;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Rep01Service 
{
    private $recycleReportRepository;

    public function __construct(
        RecycleReportRepository $recycleReportRepository,
    ) {
        $this->recycleReportRepository = $recycleReportRepository;
    }
    /**
     * Handle export csv
     * 
     * @param array $params
     * @return mixed
     */
    public function handleExportCsv($params) {
        $group = 'Rep.rep010';
        $options = ConfigUtil::getCsv("{$group}.options");
        $fileName = ConfigUtil::getCsv("{$group}.fileName") . '_' . DateUtil::parseStringFullDateTime() . '.csv';
        $header = array_column($options, 'title');
        $lstData = [];
        $recycleReports = $this->recycleReportRepository->searchRep01($params)->get()->toArray();
        foreach ($recycleReports as $recycleReport) {
            $arrData = $options;
            foreach ($arrData as $key => &$value) {
                if (isset($recycleReport[$key])) {
                    $value = $recycleReport[$key];
                } else {
                    $value = '';
                }
            }
            $lstData[] = $arrData;
        }
        FileUtil::exportCsv($header, $lstData, $fileName);
    }

    /**
     * Handle import excel
     * 
     * @param UploadedFile|null $file
     * @return bool
     */
    public function handleImportExcel(UploadedFile $file = null) {
        try {
            if (!isset($file) || empty($file)) {
                return false;
            }
            $saveData = [
                'rp_office_code' => auth()->user()->office_code,
                'report_month' => Carbon::now()->startOfMonth()->subMonth()->format('Ym'),
            ];
            $rep01ExcelImportConfig = ValueUtil::get('RecycleReport.rep01ExcelImportConfig', ['get_value' => true]);
            $reader = ImportExcel::createReaderForFile($file->path());
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->path());
            // get visible sheets
            $sheetNames = $spreadsheet->getSheetNames();
            $visibleSheet = [];
            foreach($sheetNames as $sheetName) {
                $sheetState = $spreadsheet->getSheetByName($sheetName)->getSheetState();
                if ($sheetState === Worksheet::SHEETSTATE_VISIBLE) {
                    $visibleSheet[] = $sheetName;
                }
            }
            // get cells value
            foreach ($rep01ExcelImportConfig as $key => $configs) {
                $sheetName = $configs['sheet'];
                if (is_numeric($configs['sheet'])) {
                    $sheetIndex = $configs['sheet'] - 1;
                    $sheetName = isset($visibleSheet[$sheetIndex])
                        ? $visibleSheet[$sheetIndex]
                        : '';
                }
                $workSheet = $spreadsheet->getSheetByName($sheetName);
                $cellData = $workSheet->getCell($configs['cell']);
                $saveData[$key] = $cellData->getCalculatedValue();
            }
            // process data before save
            $saveData['weight_before'] = round($saveData['weight_before'], 2);
            $saveData['weight_after'] = round($saveData['weight_after'], 2);
            $saveData['recycle_rate'] = $saveData['weight_after'] / $saveData['weight_before'] * 100;
            $saveData['recycle_rate'] = round($saveData['recycle_rate'], 2);
            $saveData['operation_rate'] = $saveData['max_process_qty'] / $saveData['total_process_qty'] * 100;
            $saveData['operation_rate'] = round($saveData['operation_rate'], 2);
            $saveData['weight_per_piece'] = $saveData['weight_after'] / $saveData['total_process_qty'];
            $saveData['weight_per_piece'] = round($saveData['weight_per_piece'], 2);
            return $this->recycleReportRepository->saveImport($saveData);
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }

}