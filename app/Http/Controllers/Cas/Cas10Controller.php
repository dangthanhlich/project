<?php

namespace App\Http\Controllers\Cas;

use App\Http\Controllers\Controller;
use App\Libs\{ValueUtil};
use App\Repositories\{
    CaseRepository,
    BaseRepository
};
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Cas10Controller extends Controller
{
    private $caseRepository; 

    public function __construct(CaseRepository $caseRepository) {
        $this->caseRepository = $caseRepository;
    }

    /**
     * CAS-100_引取報告用確認
     */
    public function cas100(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), ['SY']));
        $userOfficeCode = auth()->user()->office_code;
        $params = $request->only(['order_by']);
        $query = $this->caseRepository->searchCas100($userOfficeCode, $params);
        $cases = $this->pagination($query, $request);
        $cas100OrderBy = ValueUtil::get('Case.cas100OrderBy', ['get_value' => true]);
        return view('screens.cas.cas10.cas100',
            compact('cases', 'params', 'cas100OrderBy')
        );
    }

    /**
     * CAS-101_引取報告用確認詳細
     */
    public function cas101($id) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), ['SY']));
        $case = $this->caseRepository->getCaseWithCarByCaseId($id);
        if (empty($case)) {
            abort(404);
        }
        foreach ($case->car as $car) {
            $car->total_qty = intval($car->qty + $car->exceed_qty);
            $case->sum_total_qty += $car->total_qty;
        }
        return view('screens.cas.cas10.cas101',
            compact('case')
        );
    }

    /**
     * Handle CAS-101
     */
    public function handleCas101($id) {
        $baseRepository = new BaseRepository('case');
        $params = [
            'case_status' => ValueUtil::constToValue('Case.caseStatus.PICK_UP_REPORT_ENTERED')
        ];
        $result = $baseRepository->update($id, $params, 'case_id');
        if ($result) {
            return redirect()->route('case.cas-100');
        } else {
            return back()->withErrors('update failed');
        }
    }
}
