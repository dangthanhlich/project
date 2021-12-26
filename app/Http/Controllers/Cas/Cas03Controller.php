<?php

namespace App\Http\Controllers\Cas;

use App\Http\Controllers\Controller;
use App\Libs\{
    ConfigUtil,
    ValueUtil,
    FileUtil,
    DateUtil,
};
use App\Http\Requests\Cas\Cas030Request;
use App\Repositories\{
    MstScrapperRepository,
    MstOfficeRepository,
    ContractRepository,
    CaseRepository,
};
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Cas03Controller extends Controller
{
    private $mstScrapperRepository;
    private $mstOfficeRepository;
    private $contractRepository;
    private $caseRepository;

    public function __construct(
        MstScrapperRepository $mstScrapperRepository,
        MstOfficeRepository $mstOfficeRepository,
        ContractRepository $contractRepository,
        CaseRepository $caseRepository,
    ) {
        $this->mstScrapperRepository = $mstScrapperRepository;
        $this->mstOfficeRepository = $mstOfficeRepository;
        $this->contractRepository = $contractRepository;
        $this->caseRepository = $caseRepository;
    }

    /**
     * CAS-030_管理番号入力画面
     */
    public function cas030() {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'dismantling',
        ]));
        return view('screens.cas.cas03.cas030');
    }

    /**
     * Handle CAS-030
     */
    public function handleCas030(Cas030Request $request) {
        $officeCode = $request->office_code;
        $managementNo = $request->management_no;
        $mstScrapper = $this->mstScrapperRepository->getScrapperManagement($officeCode);
        $contract = $this->contractRepository->getContractManagement($officeCode, $managementNo);
        if (empty($mstScrapper) || empty($contract)) {
            return back()->withInput()->withErrors(ConfigUtil::getMessage('e-001', ['事業所コード', '管理番号']));
        } else {
            return redirect()->route('case.cas-031', ['managementNo' => $managementNo, 'officeCode' => $officeCode]);
        }
    }

    /**
     * CAS-031_ケース一覧
     */
    public function cas031(Request $request, $managementNo = null, $officeCode = null) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'dismantling', 'NW', 'SY'
        ]));
        $query = $this->caseRepository->searchCas031($managementNo);
        $cases = $this->pagination($query, $request);
        if (empty($managementNo) && empty($query->get()->toArray())) {
            abort(404);
        }
        $mstScrapperManagementNo = $this->mstScrapperRepository->getScrapperByManagementNo($managementNo);
        $mstOfficeManagementNo = $this->mstOfficeRepository->getOfficeByManagementNo($managementNo);
        $mstScrapperOfficeCode = $this->mstScrapperRepository->getScrapperByOfficeCode($officeCode);
        return view('screens.cas.cas03.cas031',
            compact(
                'managementNo', 'cases',
                'mstScrapperManagementNo', 'mstOfficeManagementNo', 'mstScrapperOfficeCode'
            )
        );
    }

    /**
     * Handle CAS-031
     */
    public function handleCas031(Request $request) {
        if (count($request->contract_pdf) == 1) {
            $filePath = $request->contract_pdf[0];
            $messageErrors = ConfigUtil::getMessage('c-017', [explode('/', $filePath)[1]]);
        } else {
            $filePath = $request->contract_pdf;
            $messageErrors = [];
            foreach ($filePath as $file) {
                $messageErrors[] = ConfigUtil::getMessage('c-017', [explode('/', $file)[1]]);
            }
        }
        $zipName = '契約書_'. DateUtil::parseStringFullDateTime() .'.zip';
        $result = FileUtil::downloadFileOrZipFromS3($filePath, $zipName);
        if (!$result) {
            return back()->withInput()->withErrors($messageErrors);
        }
        return back();
    }
}
