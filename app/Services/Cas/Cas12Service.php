<?php

namespace App\Services\Cas;

use App\Libs\{
    ValueUtil,
    ConfigUtil,
    FileUtil,
};
use App\Models\{
    MstScrapper,
    Contract
};
use App\Repositories\{
    CaseRepository,
    MismatchRepository,
    MstOfficeRepository,
};
use Carbon\Carbon;


use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{File, DB, Log};

class Cas12Service 
{
    private $caseRepository;
    private $mismatchRepository;
    private $mstOfficeRepository;

    public function __construct(
        CaseRepository $caseRepository,
        MismatchRepository $mismatchRepository,
        MstOfficeRepository $mstOfficeRepository,
    ) {
        $this->caseRepository = $caseRepository;
        $this->mismatchRepository = $mismatchRepository;
        $this->mstOfficeRepository = $mstOfficeRepository;
    }

    /**
     * process handle Cas121
     * 
     * @param array $params
     * @param int $caseId
     */
    public function processHandleCas121($params, $caseId) {
        try {
            $userLogin = auth()->user();
            $now = Carbon::now();
            $case = $this->caseRepository->getByCaseId($caseId);
            // update image to S3 server
            $base64File = preg_replace('#^data:image/\w+;base64,#i', '', $params['case_picture_4']);
            $casePicture4 = $params['case_picture_4'];
            if (
                isset($params['allow_flg']) && $params['allow_flg'] == ValueUtil::constToValue('Common.allowFlg.CAN_BE') &&
                base64_encode(base64_decode($base64File)) === $base64File
            ){
                $mstOffice = $this->mstOfficeRepository->getOfficeByUserOfficeCode();
                $casePicture4Folder = 'case-picture-4';
                $firstcasePicture4FileName = null;
                $casePicture4 = $this->uploadS3Cas12(
                    isset($mstOffice->office_code) ? $mstOffice->office_code : '',
                    $case['case_no'],
                    $casePicture4Folder,
                    $params['case_picture_4'],
                    $firstcasePicture4FileName
                );
                if (!$casePicture4) {
                    return false;
                }
                $firstcasePicture4FileName = !isset($firstcasePicture4FileName)
                    ? $casePicture4
                    : $firstcasePicture4FileName;
                $casePicture4 = $casePicture4Folder. '/'. $casePicture4;
            }
            // update case
            $dataCase = [
                'case_status' => ValueUtil::constToValue('Case.caseStatus.RP_INSPECTED'),
                'rp_inspect_complete_time' => $now,
                'rp_inspect_user_id' => $userLogin->id,
                'case_picture_4' => $casePicture4,
                'actual_qty_rp' => $params['actual_qty_rp'],
            ];
            $caseSave = $this->handleUpdateCase($caseId, $dataCase);
            if (!$caseSave) return false;
            if (isset($params['allow_flg']) && $params['allow_flg'] == ValueUtil::constToValue('Common.allowFlg.CAN_BE')) {
                // update mismatch
                $dataMismatch = Arr::only($params, ['mismatch_qty_1', 'mismatch_qty_2', 'mismatch_qty_3']);
                $mismatchSave = $this->handleUpdateMismatch($caseId, $dataMismatch);
                if (!$mismatchSave) return false;
            }
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }

    /**
     * Upload to S3 for CAS-12
     * 
     * @param string $officeCode
     * @param string $caseNo
     * @param string $folder
     * @param string|null $base64File
     * @param string|null $firstFileName
     * @return string|bool
     */
    private function uploadS3Cas12(
        $officeCode,
        $caseNo,
        $folder,
        $base64File = null,
        $firstFileName = null
    ) {
        $fileName =
            $officeCode. '_'.
            $caseNo. '_'.
            ValueUtil::textToValue($folder, 'File.fileTypeToS3Folder'). '_'.
            Carbon::now()->format('YmdHis'). '.jpg';
        // is first time, upload to s3
        if (!isset($firstFileName)) {
            $uploadResult = FileUtil::uploadBase64ToS3(
                $base64File,
                $fileName,
                $folder
            );
            return $uploadResult ? $fileName : false;
        }
        // not first time, duplicate first file and rename
        $copyResult = FileUtil::copyS3File(
            $folder. '/'. $firstFileName,
            $folder. '/'. $fileName
        );
        return $copyResult ? $fileName : false;
    }

    /**
     * Handle update case
     * @param string $caseId
     * @param array $data
     */
    private function handleUpdateCase($caseId, $data) {
        $result = $this->caseRepository->updateCase($caseId, $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Handle update mismatch
     * @param string $caseId
     * @param array $data
     */
    private function handleUpdateMismatch($caseId, $data) {
        $type = [];
        $index = [];
        if (!empty($data['mismatch_qty_1'])) {
            $type[] = ValueUtil::constToValue('Mismatch.misMatchType.SHORT_CIRCUIT_DEFECTIVE_QTY');
            $index['mismatch_type_1'] = 'mismatch_qty_1';
        }
        if (!empty($data['mismatch_qty_2'])) {
            $type[] = ValueUtil::constToValue('Mismatch.misMatchType.M_TYPE_UNLOCKED_QTY');
            $index['mismatch_type_4'] = 'mismatch_qty_2';
        }
        if (!empty($data['mismatch_qty_3'])) {
            $type[] = ValueUtil::constToValue('Mismatch.misMatchType.M_TYPE_UNSTORED_QTY');
            $index['mismatch_type_5'] = 'mismatch_qty_3';
        }
        $mismatchs = $this->mismatchRepository->getMismatchType($type);
        $result = true;
        foreach ($mismatchs as $mismatch) {
            $dataSave = [
                'case_id' => $caseId,
            ];
            if (in_array($mismatch->mismatch_type, $type)) {
                $dataSave['mismatch_qty'] = $data[$index['mismatch_type_'. $mismatch->mismatch_type]];
            }
            $resultSave = $this->mismatchRepository->updateMismatch($mismatch->id, $dataSave);
            if (!$resultSave) {
                $result = false;
                break;
            }
        }
        return $result;
    }
}