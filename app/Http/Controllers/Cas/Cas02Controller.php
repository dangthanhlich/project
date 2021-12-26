<?php

namespace App\Http\Controllers\Cas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cas\Cas020Request;
use App\Libs\{
    ConfigUtil,
    ValueUtil,
    FileUtil,
    DateUtil,
};
use App\Repositories\{
    CaseRepository,
    ContractRepository,
    MstScrapperRepository,
    MstUserRepository,
};
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Cas02Controller extends Controller
{
    public function __construct(
        CaseRepository $caseRepository,
        ContractRepository $contractRepository,
        MstScrapperRepository $mstScrapperRepository)
    {
        $this->caseRepository = $caseRepository;
        $this->contractRepository = $contractRepository;
        $this->mstScrapperRepository = $mstScrapperRepository;
    }

    /**
     * CAS-020_ケース一覧(運搬NW)
     */
    public function cas020(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), ['NW']));

        //list search
        $params = [];
        if (session()->has('cas020')) {
            $params = session()->get('cas020');
        }

        $userOfficeCode = auth()->user()->office_code;
        $cases = $this->pagination(
            $this->caseRepository->queryCas02($userOfficeCode, $params),
            $request
        );

        $lstMstScrapper = $this->mstScrapperRepository->findBy([
            ['tr_office_code', $userOfficeCode]
        ])->mapWithKeys(function ($item, $key) {
            return [$item['office_code'] => $item['office_code'] . ' - ' . $item['office_name']];
        })->toArray();
        // dd($cases);
        return view('screens.cas.cas02.cas020',
            compact('lstMstScrapper', 'cases', 'params')
        );
    }

    public function handleCas020(Cas020Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), ['NW']));
        
        $requestData = $request->except(['_token']);
        session()->forget('cas020');
        session()->put('cas020', $requestData);
        return redirect()->route('case.cas-020');
    }

    /**
     * CAS-020_ケース一覧(運搬NW): download file PDF
     */
    public function downloadFileCas020(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), ['NW']));

        $contractPdfs = $filePath = $request->contract_pdfs;

        if (empty($contractPdfs) || !is_array($contractPdfs)) {
            return back();
        }

        if (count($contractPdfs) == 1) {
            $filePath = $contractPdfs[0];
        } else {
            $zipName = '契約書_'. DateUtil::parseStringFullDateTime() .'.zip';
        }

        if (!FileUtil::downloadFileOrZipFromS3($filePath, $zipName ?? null)) {
            $messageErrors = [];
            foreach($contractPdfs as $contractPdf) {
                $fileArr = explode('/', $contractPdf);
                $messageErrors[] =  ConfigUtil::getMessage('c-017', [$fileArr[array_key_last($fileArr)]]);
            }

            return back()->withInput()->withErrors($messageErrors);
        }

        return back();
    }

    public function cas021($caseId) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), ['NW']));

        $case = $this->caseRepository->findCaseForCas021($caseId,  auth()->user()->office_code);
        
        if (empty($case)) {
            abort(404);
        }

        $collectUser = app(MstUserRepository::class)->getUser($case->collect_user_id);
        $isHandCorrection = getConstValue('Case.caseNoChangeFlg.WITH_HAND_CORRECTION');

        return view('screens.cas.cas02.cas021'
            ,
            compact('case', 'collectUser', 'isHandCorrection')
        );
    }
}
