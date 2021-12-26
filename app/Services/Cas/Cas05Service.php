<?php

namespace App\Services\Cas;

use App\Http\Requests\Cas\Cas051Request;
use App\Libs\{
    FileUtil,
    ValueUtil,
};
use App\Models\Contract;
use App\Repositories\{
    CaseRepository,
    MstOfficeRepository,
    TempCaseRepository,
};
use Carbon\Carbon;
use Illuminate\Support\Facades\{DB, Log};

class Cas05Service 
{
    private $mstOfficeRepository;
    private $caseRepository;
    private $tempCaseRepository;

    public function __construct(
        MstOfficeRepository $mstOfficeRepository,
        CaseRepository $caseRepository,
        TempCaseRepository $tempCaseRepository
    ) {
        $this->mstOfficeRepository = $mstOfficeRepository;
        $this->caseRepository = $caseRepository;
        $this->tempCaseRepository = $tempCaseRepository;
    }

    /**
     * Get list mst_office associated mst_scrapper
     * 
     * @param string $userOfficeCode
     * @return array
     */
    public function getListOfficeAssociatedScrapper($userOfficeCode) {
        try {
            return $this->mstOfficeRepository
                ->getListOfficeAssociatedScrapper($userOfficeCode)
                ->toArray();
        } catch (\Exception $e) {
            Log::error($e);
            return [];
        }
    }

    /**
     * Search case, temp_case by conditions
     * 
     * @param array $params
     * @param array $order
     * @param string $screen
     * @return array
     */
    public function searchCaseByConditions($params, $orderBy, $screen = 'cas050') {
        try {
            if (!isset($params['tr_office_code']) || empty($params['tr_office_code'])) {
                return [];
            }
            $case = $this->caseRepository->searchCas05($params, $orderBy);
            $case = $case->get();
            $tempCase = $this->tempCaseRepository->searchCas05($params, $orderBy);
            $tempCase = $tempCase->get();
            $merged = $case->merge($tempCase);
            if ($screen === 'cas050') {
                $merged = $merged
                    ->sortBy('case_id')
                    ->sortBy(function($data) {
                        return strtotime(((object)$data)->collect_complete_time);
                    })
                    ->all();
            } else if ($screen === 'cas051') {
                $merged = $merged
                    ->sortBy('case_id')
                    ->sortBy(function($data) {
                        return strtotime(((object)$data)->receive_complete_time);
                    })
                    ->all();
            }
            $result = [];
            foreach ($merged as $data) {
                $result[] = $data;
            }
            return $result;
        } catch (\Exception $e) {
            Log::error($e);
            return [];
        }
    }

    /**
     * Update case(temp_case).case_status
     * 
     * @param string|int $caseId
     * @param string|int $caseStatus
     * @param bool $isTempCase
     * @return bool
     */
    public function updateCaseStatus($caseId, $caseStatus, $isTempCase) {
        try {
            $updateParams = ['case_status' => $caseStatus];
            if ($caseStatus == ValueUtil::constToValue('Case.caseStatus.BEFORE_INSPECTION')) {
                $updateParams['receive_complete_time'] = Carbon::now();
                $updateParams['receive_user_id'] = auth()->user()->id;
            } else {
                $updateParams['receive_complete_time'] = null;
                $updateParams['receive_user_id'] = null;
            }
            // case
            if (!$isTempCase) {
                return $this->caseRepository->updateCaseStatus($caseId, $updateParams);
            }
            // temp_case
            return $this->tempCaseRepository->updateCaseStatus($caseId, $updateParams);
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }

    /**
     * Upload file and update DB for CAS-051
     * 
     * @param string $officeCode
     * @param Cas051Request $request
     */
    public function saveCas051($officeCode, Cas051Request $request) {
        $formData = $request->only(['sign_tr_2', 'sign_sy']);
        // search case(temp_case) by office_code
        $params = [
            'tr_office_code' => $officeCode,
            'case_status' => [
                ValueUtil::constToValue('Case.caseStatus.BEFORE_INSPECTION'),
            ],
            'transport_type' => [
                ValueUtil::constToValue('Case.transportType.PAYMENT'),
            ],
        ];
        $orderBy = [
            'receive_complete_time' => 'ASC',
        ];
        $cases = $this->searchCaseByConditions($params, $orderBy, 'cas051');
        DB::beginTransaction();
        $isError = false;
        try {
            $firstSignTr2FileName = null;
            $firstSignSyFileName = null;
            foreach ($cases as $case) {
                // upload sign-tr-2 file
                $signTr2Folder = 'sign-tr-2';
                $signTr2FileName = $this->uploadS3Cas051(
                    $case['sy_office_code'],
                    $case['case_no'],
                    $signTr2Folder,
                    $formData['sign_tr_2'],
                    $firstSignTr2FileName
                );
                if (!$signTr2FileName) {
                    $isError = true;
                    break;
                }
                $firstSignTr2FileName = !isset($firstSignTr2FileName)
                    ? $signTr2FileName
                    : $firstSignTr2FileName;
                // upload sign-sy file
                $signSyFolder = 'sign-sy';
                $signSyFileName = $this->uploadS3Cas051(
                    $case['sy_office_code'],
                    $case['case_no'],
                    $signSyFolder,
                    $formData['sign_sy'],
                    $firstSignSyFileName
                );
                if (!$signSyFileName) {
                    $isError = true;
                    break;
                }
                $firstSignSyFileName = !isset($firstSignSyFileName)
                    ? $signSyFileName
                    : $firstSignSyFileName;
                // update contract related to case(temp_case)
                $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
                $contractQuery = Contract::where('del_flg', '<>', $flgDeleted)
                    ->whereNull('sign_sy');
                if (isset($case['temp_case_id'])) {
                    // temp_case
                    $contractQuery->where('temp_case_id', '=', $case['temp_case_id']);
                } else {
                    // case
                    $contractQuery->where('case_id', '=', $case['case_id']);
                }
                $updateParams = [
                    'sign_tr_2' => $signTr2Folder. '/'. $signTr2FileName,
                    'sign_sy' => $signSyFolder. '/'. $signSyFileName,
                    'updated_at' => Carbon::now(),
                    'updated_by' => auth()->user()->id,
                ];
                $contractQuery->update($updateParams);
            }
        } catch (\Exception $e) {
            Log::error($e);
            $isError = true;
        }
        if ($isError) {
            DB::rollback();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * Upload to S3 for CAS-051
     * 
     * @param string $syOfficeCode
     * @param string $caseNo
     * @param string $folder
     * @param string|null $base64File
     * @param string|null $firstFileName
     * @return string|bool
     */
    private function uploadS3Cas051(
        $syOfficeCode,
        $caseNo,
        $folder,
        $base64File = null,
        $firstFileName = null
    ) {
        $fileName =
            $syOfficeCode. '_'.
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

}