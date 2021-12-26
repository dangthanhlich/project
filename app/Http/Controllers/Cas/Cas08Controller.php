<?php

namespace App\Http\Controllers\Cas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cas\Cas080Request;
use App\Libs\ValueUtil;
use App\Repositories\{
    CaseRepository,
    PlanCaseRepository,
    MstScrapperRepository,
    MstUserRepository,
};
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Cas08Controller extends Controller
{
    public function __construct(
        CaseRepository $caseRepository,
        PlanCaseRepository $planCaseRepository,
        MstScrapperRepository $mstScrapperRepository,
        MstUserRepository $mstUserRepository
    )
    {
        $this->caseRepository = $caseRepository;
        $this->mstScrapperRepository = $mstScrapperRepository;
        $this->planCaseRepository = $planCaseRepository;
        $this->mstUserRepository = $mstUserRepository;
    }

    /**
     * CAS-080_ケース一覧（③SY）
     */
    public function cas080(Cas080Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));

        //list search
        $params = [];
        if (session()->has('cas080')) {
            $params = session()->get('cas080');
        }

        $officeCode = auth()->user()->office_code;
        $query = $this->caseRepository->queryCas08($officeCode, $params);
        $cases = $this->pagination($query, $request);

        $lstMstScrapper = $this->mstScrapperRepository->getListOfficeWithSyOfficeCodeByOfficeCodeOfUser();
        return view('screens.cas.cas08.cas080',
            compact('cases', 'lstMstScrapper', 'params')
        );
    }

    public function handleCas080(Cas080Request $request) {
        $requestData = $request->except(['_token']);
        session()->forget('cas080');
        session()->put('cas080', $requestData);
        return redirect()->route('case.cas-080');
    }

    /**
     * CAS-081_ケース詳細（③SY
     */
    public function cas081(Request $request, $id) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));

        $case = $this->caseRepository->getCaseByCaseIdForCas081($id);
        if (empty($case)) {
            abort(404);
        }

        $inspectUser = $this->mstUserRepository->getUser($case->inspect_user_id);

        $misMatchArr = $case->mismatch->count() > 0 ? $case->mismatch->pluck('mismatch_qty', 'mismatch_type')->toArray() : [];
        $misMatchTypes = getList('Mismatch.misMatchType');

        $isHandCorrection = getConstValue('Case.caseNoChangeFlg.WITH_HAND_CORRECTION');

        return view('screens.cas.cas08.cas081',
            compact('case', 'isHandCorrection', 'inspectUser', 'misMatchArr', 'misMatchTypes')
        );
    }
}
