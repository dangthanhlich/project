<?php

namespace App\Http\Controllers\Cas;

use App\Http\Controllers\Controller;
use App\Libs\ValueUtil;
use App\Repositories\{
    BaseRepository,
    CaseRepository,
};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
class Cas09Controller extends Controller
{

    private $caseRepository;

    public function __construct(
        CaseRepository $caseRepository,
    )
    {
        $this->caseRepository = $caseRepository;
    }

    /**
     * CAS-090_個数再確認
     */
    public function cas090(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        // search case with case.sy_office_code = login user office_code
        $userOfficeCode = auth()->user()->office_code;
        $params = $request->only(['order_by']);
        $query = $this->caseRepository->searchCas090($userOfficeCode, $params);
        $cases = $this->pagination($query, $request);
        // get config for "order by" buttons
        $cas090OrderBy = ValueUtil::get('Case.cas090OrderBy', ['get_value' => true]);

        return view('screens.cas.cas09.cas090',
            compact(
                'cas090OrderBy',
                'cases',
                'params',
            )
        );
    }

    /**
     * CAS-091_個数再確認詳細
     */
    public function cas091($id) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        $case = $this->caseRepository->getCaseByCaseIdForCas091($id);
        if (empty($case)) {
            abort(404);
        }
        return view('screens.cas.cas09.cas091',
            compact('case')
        );
    }

    /**
     * Handle CAS-091
     */
    public function handleCas091($id) {
        $baseRepository = new BaseRepository('case');
        $params = [
            'case_status' => ValueUtil::constToValue('Case.caseStatus.BEFORE_THE_TAKE_BACK_REPORT'),
            'recheck_time' => Carbon::now(),
            'recheck_user_id' => auth()->user()->id,
        ];
        $result = $baseRepository->update($id, $params, 'case_id');
        if ($result) {
            return redirect()->route('case.cas-090');
        } else {
            return abort(400);
        }
    }

}
