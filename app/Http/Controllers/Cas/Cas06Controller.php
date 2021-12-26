<?php

namespace App\Http\Controllers\Cas;

use App\Http\Controllers\Controller;
use App\Services\Cas\Cas06Service;
use App\Http\Requests\Cas\{
    Cas060Request,
    Cas061Request,
    Cas062Request
};
use App\Libs\{
    ValueUtil,
    ConfigUtil
};
use App\Repositories\MstScrapperRepository;
use App\Models\MstScrapper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class Cas06Controller extends Controller
{
    private $cas06Service;
    private $mstScrapperRepository;

    public function __construct(
        Cas06Service $cas06Service,
        MstScrapperRepository $mstScrapperRepository
    )
    {
        $this->cas06Service = $cas06Service;
        $this->mstScrapperRepository = $mstScrapperRepository;
    }

    /**
     * CAS-060_解体業者持込受入ケース選択
     */
    public function cas060() {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'dismantling', 'SY'
        ]));
        $paramsSearch = [];
        if (session()->has('cas060')) {
            $paramsSearch = session()->get('cas060');
        }
        // get list office_code, office_name from mstScrapper
        $lstOffice = $this->mstScrapperRepository->getListOfficeWithSyOfficeCodeByOfficeCodeOfUser();
        // get case & temp_case from mstScrapper
        $mstScrapperObj = $this->mstScrapperRepository->getCaseTempCaseFromMstScrapper($paramsSearch);
        // sort dataSearch follow case_no, case_id acs
        $dataSearch = $this->cas06Service->sortListCaseTempCaseByConditions($mstScrapperObj);
        $disabled = true;
        foreach ($dataSearch as $data) {
            if ($data['status'] == ValueUtil::constToValue('Case.caseStatus.BEFORE_INSPECTION')) {
                $disabled = false;
            }
        }
        return view('screens.cas.cas06.cas060',
            compact('lstOffice', 'paramsSearch', 'mstScrapperObj', 'dataSearch', 'disabled')
        );
    }

    /**
     * handle CAS-060
     */
    public function handleCas060(Request $request) {
        $requestData = $request->except(['_token']);
        session()->forget('cas060');
        session()->put('cas060', $requestData);
        return redirect()->route('case.cas-060');
    }

    /**
     * CAS-061_解体業者持込受入ケース確認
     */
    public function cas061($id, $flag) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        // check correct url
        $flagArr = ['case', 'temp_case'];
        if (!in_array($flag, $flagArr)) {
            abort(404);
        }
        // get case, temp_case by id
        $dataDisplay = $this->cas06Service->getDataCas061($id, $flag);
        if (empty($dataDisplay)) {
            abort(404);
        }
        return view('screens.cas.cas06.cas061', 
            compact('dataDisplay')
        );
    }

    /**
     * handle CAS-061
     */
    public function handleCas061(Cas061Request $request, $id, $flag) {
        $data = $this->cas06Service->processHandelCas061($id, $flag, $request);
        if (!$data) {
            return back()->withErrors(ConfigUtil::getMessage('SERVER_ERROR'));
        }
        return redirect()->route('case.cas-060');
    }

    /**
     * CAS-062_解体業者持込電子サイン
     */
    public function cas062($officeCode) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        // get case & temp_case from mstScrapper
        $paramsSearch = [
            'office_code' => $officeCode
        ];
        $mstScrapperObj = $this->mstScrapperRepository->getCaseTempCaseFromMstScrapper($paramsSearch, 'CAS-062');
        if (is_null($mstScrapperObj)) {
            abort(404);
        }
        // sort dataSearch follow receive_complete_time
        $dataSort = $this->cas06Service->sortListCaseTempCaseByConditions($mstScrapperObj, 'CAS-062');
        return view('screens.cas.cas06.cas062',
                compact('officeCode', 'dataSort')
            );
    }

    /**
     * handle CAS-062
     */
    public function handleCas062(Cas062Request $request, $officeCode) {
        $saveResult = $this->cas06Service->saveCas062($officeCode, $request);
        if ($saveResult) {
            return redirect()->route('common.com-030');
        }
        return abort(400);
    }

    /**
     * Ajax create new temp_case
     */
    public function addTempCase(Cas060Request $request) {
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        $params = [
            'mst_scrapper_id' => $request->input()['mst_scrapper_id'],
            'temp_case_no' => $request->input()['temp_case_no'],
            'case_picture_3' => $request->input()['case_picture_3'],
        ];
        // create new case, temp_case, contract
        $data = $this->cas06Service->createNew060($params);
        return response()->json($data);
    }

    /**
     * Ajax cancel case, temp_case by id
     */
    public function cas061CancelCase(Request $request) {
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        $caseId = $request->input()['case_id'];
        $flag = $request->input()['flag'];
        $case = $this->cas06Service->cancelCaseTempCase($caseId, $flag);
        return response()->json($case);
    }
}
