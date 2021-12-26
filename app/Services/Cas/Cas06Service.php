<?php

namespace App\Services\Cas;

use App\Http\Requests\Cas\{
    Cas060Request,
    Cas061Request,
    Cas062Request
};
use App\Libs\{
    ValueUtil,
    ConfigUtil,
    FileUtil
};
use App\Models\{
    MstScrapper,
    Contract
};
use App\Repositories\{
    BaseRepository,
    MstScrapperRepository,
    MstOfficeRepository,
    MstPriceRepository,
    CaseRepository,
    TempCaseRepository,
    ContractRepository,
    CarRepository,
    TempCarRepository,
};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{File, DB, Log};

class Cas06Service 
{
    private $mstScrapperRepository;
    private $mstOfficeRepository;
    private $mstPriceRepository;
    private $caseRepository;
    private $tempCaseRepository;
    private $contractRepository;
    private $carRepository;
    private $tempCarRepository;

    public function __construct(
        MstScrapperRepository $mstScrapperRepository,
        MstOfficeRepository $mstOfficeRepository,
        MstPriceRepository $mstPriceRepository,
        CaseRepository $caseRepository,
        TempCaseRepository $tempCaseRepository,
        ContractRepository $contractRepository,
        CarRepository $carRepository,
        TempCarRepository $tempCarRepository
    ) {
        $this->mstScrapperRepository = $mstScrapperRepository;
        $this->mstOfficeRepository = $mstOfficeRepository;
        $this->mstPriceRepository = $mstPriceRepository;
        $this->caseRepository = $caseRepository;
        $this->tempCaseRepository = $tempCaseRepository;
        $this->contractRepository = $contractRepository;
        $this->carRepository = $carRepository;
        $this->tempCarRepository = $tempCarRepository;
    }

    /**
     * Sort list case, temp_case follow screen
     * 
     * @param object $mstScrapperObj
     * @return array
     */
    public function sortListCaseTempCaseByConditions($mstScrapperObj, $screen = 'CAS-060') {
        try {
            // sort dataSearch follow case_no, case_id acs
            $dataSearch = [];
            if (!is_null($mstScrapperObj)) {
                if (!$mstScrapperObj->case->isEmpty()) {
                    foreach ($mstScrapperObj->case as $case) {
                        if (!is_null($case->contract)) {
                            $dataSearch[] = [
                                'id' => $case->case_id,
                                'no' => $case->case_no,
                                'receive_complete_time' => Carbon::parse($case->receive_complete_time)->format('YmdHis'),
                                'scrapper_office_code' => $case->scrapper_office_code,
                                'sy_office_code' => $case->sy_office_code,
                                'status' => $case->case_status,
                                'flag' => 'case'
                            ]; 
                        }
                    }
                }
                if (!$mstScrapperObj->temp_case->isEmpty()) {
                    foreach ($mstScrapperObj->temp_case as $tempCase) {
                        if (!is_null($tempCase->contract)) {
                            $dataSearch[] = [
                                'id' => $tempCase->temp_case_id,
                                'no' => $tempCase->temp_case_no,
                                'receive_complete_time' => Carbon::parse($tempCase->receive_complete_time)->format('YmdHis'),
                                'scrapper_office_code' => $tempCase->scrapper_office_code,
                                'sy_office_code' => $tempCase->sy_office_code,
                                'status' => $tempCase->case_status,
                                'flag' => 'temp_case'
                            ]; 
                        }
                    }
                }
            }
            if( $screen == 'CAS-062') {
                usort($dataSearch, function($a, $b) {
                    $retVal = $a['receive_complete_time'] <=> $b['receive_complete_time'];
                    return $retVal;
                });
            } else {
                usort($dataSearch, function($a, $b) {
                    $retVal = $a['no'] <=> $b['no'];
                    if ($retVal == 0) {
                        $retVal = $a['id'] <=> $b['id'];
                    }
                    return $retVal;
                });
            }
            return $dataSearch;
        } catch (\Exception $e) {
            Log::error($e);
            return [];
        }
    }

    /**
     * create new case, temp_case, contract
     * 
     * @param array $params
     */
    public function createNew060($params) {
        try {
            $mstScrapper = MstScrapper::find($params['mst_scrapper_id']);
            // update image to S3 server
            $casePicture3Folder = 'case-picture-3';
            $firstCasePicture3FileName = null;
            $casePicture3 = $this->uploadS3Cas06(
                $mstScrapper->office_code,
                $params['temp_case_no'],
                $casePicture3Folder,
                $params['case_picture_3'],
                $firstCasePicture3FileName
            );
            if (!$casePicture3) {
                return back()->withErrors(ConfigUtil::getMessage('SERVER_ERROR'));
            }
            $firstCasePicture3FileName = !isset($firstCasePicture3FileName)
                ? $casePicture3
                : $firstCasePicture3FileName;
            // add new temp_case
            $dataCreateTempCase = [
                'office_code' => $mstScrapper->office_code,
                'temp_case_no' => $params['temp_case_no'],
                'case_picture_3' => $casePicture3Folder. '/'. $casePicture3
            ];
            $tempCase = $this->tempCaseRepository->createTempCaseCas060($dataCreateTempCase);
            // add new contract
            if ($tempCase) {
                $mstOfficeObj = $this->mstOfficeRepository->getOfficeBySyOfficeCode($tempCase->sy_office_code);
                $mstPriceObj = $this->mstPriceRepository->getDataWithConditions([
                    'transport_type' => $tempCase->transport_type,
                    'sy_office_code' => $tempCase->sy_office_code
                ]);
                // set value to management_no
                $managementNo = $mstScrapper->office_code.
                                Carbon::now()->format('YmdHis').
                                $this->generateUpcCheckDigit($mstScrapper->office_code.
                                Carbon::now()->format('YmdHis'));
                $dataCreateContract = [
                    'temp_case_id' => $tempCase->temp_case_id,
                    'management_no' => $managementNo,
                    'contract_office_name_1' => $mstScrapper->office_name,
                    'contract_office_address_1' => $mstScrapper->office_address_search,
                    'contract_office_name_3' => !is_null($mstOfficeObj) ? $mstOfficeObj->office_name : NULL,
                    'contract_office_address_3' => !is_null($mstOfficeObj) ? $mstOfficeObj->office_address_search : NULL,
                    'contract_type' => ValueUtil::get('Contract.contractType'),
                    'contract_price' => !is_null($mstPriceObj) ? $mstPriceObj->unit_price : NULL,
                    'contract_scope' => ValueUtil::get('Contract.contractScope'),
                    'contract_period' => ValueUtil::get('Contract.contractPeriod'),
                    'contract_case_no' => $tempCase->temp_case_no,
                ];
                $contract = $this->contractRepository->createContractCas060($dataCreateContract);
                return $contract;
            }
            return $tempCase;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }

    /**
     * get data from case, temp_case by id
     * 
     * @param $id
     * @param string $flag
     * @return array
     */
    public function getDataCas061($id, $flag) {
        try {
            $dataDisplay = [];
            // get case by case_id
            if ($flag === 'case') {
                $case = $this->caseRepository->getByCaseId($id);
                if (!is_null($case)) {
                    $dataDisplay = [
                        'flag' => $flag,
                        'case_id' => $case->case_id,
                        'scrapper_office_code' => $case->scrapper_office_code,
                        'case_no' => $case->case_no,
                        'case_status' => $case->case_status,
                        'case_picture_2' => $case->case_picture_2,
                        'car' => $case->car,
                        'contract' => !is_null($case->contract) ?? $case->contract
                    ];
                }
            }
            // get temp_case by temp_case_id
            if ($flag === 'temp_case') {
                $tempCase = $this->tempCaseRepository->getByTempCaseId($id);
                if (!is_null($tempCase)) {
                    $dataDisplay = [
                        'flag' => $flag,
                        'case_id' => $tempCase->temp_case_id,
                        'scrapper_office_code' => $tempCase->scrapper_office_code,
                        'case_no' => $tempCase->temp_case_no,
                        'case_status' => $tempCase->case_status,
                        'case_picture_2' => $tempCase->case_picture_2,
                        'car' => $tempCase->temp_car,
                        'contract' => !is_null($tempCase->contract) ?? $tempCase->contract
                    ];
                }
            }
            // check correct url
            if (!isset($dataDisplay['contract'])) {
                abort(404);
            }
            return $dataDisplay;
        } catch (\Exception $e) {
            Log::error($e);
            return [];
        }
    }

    /**
     * process handel Cas061
     * 
     * @param $id
     * @param string $flag
     * @param Cas061Request $request
     */
    public function processHandelCas061($id, $flag, Cas061Request $request) {
        try {
            // get list of car_id to delete
            $idsDelete = !is_null($request->input()['cars_delete']) ? json_decode($request->input()['cars_delete']) : [];
            // update image to S3 server
            $base64File = preg_replace('#^data:image/\w+;base64,#i', '', $request->input()['case_picture_2']);
            $casePicture2 = $request->input()['case_picture_2'];
            if (base64_encode(base64_decode($base64File)) === $base64File){
                $casePicture2Folder = 'case-picture-2';
                $firstCasePicture2FileName = null;
                $casePicture2 = $this->uploadS3Cas06(
                    $request->input()['scrapper_office_code'],
                    $request->input()['case_no'],
                    $casePicture2Folder,
                    $request->input()['case_picture_2'],
                    $firstCasePicture2FileName
                );
                if (!$casePicture2) {
                    return false;
                }
                $firstCasePicture2FileName = !isset($firstCasePicture2FileName)
                    ? $casePicture2
                    : $firstCasePicture2FileName;
                $casePicture2 = $casePicture2Folder. '/'. $casePicture2;
            }
            // update case, insert/delete car
            if ($flag == 'case') {
                $caseUpdate =  $this->caseRepository->updateCase061($casePicture2, $id, $request->input());
                if (!$caseUpdate) return false;
                if (isset($request->input()['car_no'])) {
                    $carInsert = $this->carRepository->insertCar061($request->input(), $id);
                    if (!$carInsert) return false;
                }
                if (!empty($idsDelete)) {
                    $carDelete = $this->carRepository->deleteCar061($idsDelete);
                    if (!$carDelete) return false;
                }
            }
            // update temp_case, insert/delete car
            if ($flag == 'temp_case') { 
                $tempCaseUpdate = $this->tempCaseRepository->updateTempCase061($casePicture2, $id, $request->input());
                if (!$tempCaseUpdate) return false;
                if (isset($request->input()['car_no'])) {
                    $tempCarInsert = $this->tempCarRepository->insertTempCar061($request->input(), $id);
                    if (!$tempCarInsert) return false;
                }
                if (!empty($idsDelete)) {
                    $tempCarDelete = $this->tempCarRepository->deleteTempCar061($idsDelete);
                    if (!$tempCarDelete) return false;
                }
            }
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }

    /**
     * Cancel case, temp_case
     * @param $caseId
     * @param string $flag
     * 
     */
    public function cancelCaseTempCase($caseId, $flag) {
        try {
            if ($flag == 'case') {
                $case = $this->caseRepository->cancelCase($caseId);
            } 
            if ($flag == 'temp') {
                $case = $this->tempCaseRepository->cancelTempCase($caseId);
            }
            return $case; 
        } catch (\Exception $e) {
            Log::error($e);
            return [];
        }
    }
    
    /**
     * Upload file and update DB for CAS-062
     * 
     * @param string $officeCode
     * @param Cas062Request $request
     */
    public function saveCas062($officeCode, Cas062Request $request) {
        $formData = $request->only(['sign_scrapper', 'sign_sy']);
        // search case(temp_case) by office_code
        $paramsSearch = [
            'office_code' => $officeCode
        ];
        $mstScrapperObj = $this->mstScrapperRepository->getCaseTempCaseFromMstScrapper($paramsSearch, 'CAS-062');
        // sort dataSearch follow receive_complete_time
        $cases = $this->sortListCaseTempCaseByConditions($mstScrapperObj, 'CAS-062');
        DB::beginTransaction();
        $isError = false;
        try {
            $firstSignScrapperFileName = null;
            $firstSignSyFileName = null;
            foreach ($cases as $case) {
                // upload sign-scrapper file
                $signScrapperFolder = 'sign-scrapper';
                $signScrapperFileName = $this->uploadS3Cas06(
                    $case['scrapper_office_code'],
                    $case['no'],
                    $signScrapperFolder,
                    $formData['sign_scrapper'],
                    $firstSignScrapperFileName
                );
                if (!$signScrapperFileName) {
                    $isError = true;
                    break;
                }
                $firstSignScrapperFileName = !isset($firstSignScrapperFileName)
                    ? $signScrapperFileName
                    : $firstSignScrapperFileName;
                // upload sign-sy file
                $signSyFolder = 'sign-sy';
                $signSyFileName = $this->uploadS3Cas06(
                    $case['sy_office_code'],
                    $case['no'],
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
                if ($case['flag'] == 'temp_case') {
                    // temp_case
                    $contractQuery->where('temp_case_id', '=', $case['id']);
                } else {
                    // case
                    $contractQuery->where('case_id', '=', $case['id']);
                }
                // set value to management_no
                $managementNo = $mstScrapperObj->office_code.
                                Carbon::now()->format('YmdHis').
                                $this->generateUpcCheckDigit($mstScrapperObj->office_code.
                                Carbon::now()->format('YmdHis'));
                $updateParams = [
                    'contract_date' => Carbon::now(),
                    'management_no' => $managementNo,
                    'sign_scrapper' => $signScrapperFolder. '/'. $signScrapperFileName,
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
     * Upload to S3 for CAS-06
     * 
     * @param string $syOfficeCode
     * @param string $caseNo
     * @param string $folder
     * @param string|null $base64File
     * @param string|null $firstFileName
     * @return string|bool
     */
    private function uploadS3Cas06(
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
     * check digit 
     */
    private function generateUpcCheckDigit($upc_code) {
        if (!ctype_digit($upc_code) ) return false;
        $upc_code = (string)$upc_code;
        $odd_total  = 0;
        $even_total = 0;
        for ($i = 0; $i < strlen($upc_code); $i++) {
            if ((($i + 1) % 2) == 0) {
                /* Sum even digits */
                $even_total += $upc_code[$i];
            } else {
                /* Sum odd digits */
                $odd_total += $upc_code[$i];
            }
        }
        $sum = (3 * $odd_total) + $even_total;
        /* Get the remainder MOD 10*/
        $check_digit = $sum % 10;
        /* If the result is not zero, subtract the result from ten. */
        return (10 - $check_digit) % 10;
    }
}