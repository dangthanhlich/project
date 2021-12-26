<?php

namespace App\Http\Controllers\Com;

use App\Http\Controllers\Controller;
use App\Http\Requests\Com\Com022Request;
use App\Libs\ValueUtil;
use App\Repositories\{
    CaseRepository,
    DiffReceiveReportRepository,
};
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Com02Controller extends Controller
{
    public function __construct(
        CaseRepository $caseRepository,
        DiffReceiveReportRepository $diffReceiveReportRepository
    )
    {
        $this->caseRepository = $caseRepository;
        $this->diffReceiveReportRepository = $diffReceiveReportRepository;
    }

    public function com020() {
        return view('screens.com.com02.com020');
    }

    public function com022($id) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));

        $diffReceiveReport = $this->diffReceiveReportRepository->findByIdForCom022($id);

        if (empty($diffReceiveReport)) {
            abort(404);
        }

        $misMatchArr = $diffReceiveReport->mismatch->count() > 0 ? $diffReceiveReport->mismatch->pluck('mismatch_qty', 'mismatch_type')->toArray() : [];
        $misMatchTypes = getList('Mismatch.misMatchType');

        $isHandCorrection = getConstValue('Case.caseNoChangeFlg.WITH_HAND_CORRECTION');
        return view('screens.com.com02.com022',
            compact('diffReceiveReport', 'isHandCorrection', 'misMatchArr', 'misMatchTypes')
        );
    }

    public function handleCom022(Com022Request $request)
    {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));

        if (empty($request->id)) {
            abort(404);
        }

        $this->diffReceiveReportRepository->handleCom022($request->id, $request->all());

        return redirect()->route('common.com-020_32');
    }
}
