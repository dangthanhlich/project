<?php

namespace App\Http\Controllers\Cas;

use App\Http\Controllers\Controller;
use App\Services\Cas\Cas12Service;
use App\Libs\{
    ValueUtil,
    ConfigUtil,
};
use App\Repositories\{
    CaseRepository,
};
use App\Http\Requests\Cas\{
    Cas121Request,
};
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Cas12Controller extends Controller
{
    private $cas12Service;
    private $caseRepository;

    public function __construct(
        Cas12Service $cas12Service,
        CaseRepository $caseRepository
    ) {
        $this->cas12Service = $cas12Service;
        $this->caseRepository = $caseRepository;
    }

    /**
     * CAS-120_RP検品ケース選択
     */
    public function cas120(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), ['RP']));
        $cases = $this->caseRepository->searchCas120();
        return view('screens.cas.cas12.cas120', compact(
            'cases',
        ));
    }

    /**
     * CAS-121_RPケース検品
     */
    public function cas121($id) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), ['RP']));
        $case = $this->caseRepository->getByCaseId($id);
        if (empty($case)) {
            abort(404);
        }
        return view('screens.cas.cas12.cas121', compact(
            'case',
        ));
    }

    /**
     * Handle CAS-121
     */
    public function handleCas121(Cas121Request $request, $id) {
        $data = $this->cas12Service->processHandleCas121($request->all(), $id);
        if (!$data) {
            return back()->withErrors(ConfigUtil::getMessage('SERVER_ERROR'));
        }
        return redirect()->route('case.cas-120');
    }
}
