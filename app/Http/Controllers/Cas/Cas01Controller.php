<?php

namespace App\Http\Controllers\Cas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cas\{
    Cas011Request,
    Cas012Request,
};
use App\Libs\{
    ConfigUtil,
    ValueUtil,
    FileUtil,
    DateUtil
};
use App\Repositories\{
    BaseRepository,
    PlanCaseRepository,
    MstScrapperRepository,
};
use App\Services\Cas\Cas01Service;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class Cas01Controller extends Controller
{
    private $planCaseRepository;
    private $mstScrapperRepository;

    public function __construct(
        PlanCaseRepository $planCaseRepository,
        MstScrapperRepository $mstScrapperRepository,
    ) {
        $this->planCaseRepository = $planCaseRepository;
        $this->mstScrapperRepository = $mstScrapperRepository;
    }

    /**
     * CAS-010_ケース集荷予定一覧
     */
    public function cas010(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'NW',
        ]));
        $now = Carbon::now();
        $defaultSysDate = $now->format('Y/m/d');
        $paramsSearch = [
            'schedule_picked_up' => array_key_first(ValueUtil::get('PlanCase.schedulePickedUp')),
            'collect_plan_date_from' => $defaultSysDate,
        ];
        if (session()->has('cas010')) {
            $paramsSearch = session()->get('cas010');
        }
        $lstOffice = $this->mstScrapperRepository->getListOfficeByOfficeCodeOfUser();
        $query = $this->mstScrapperRepository->searchCas010($paramsSearch);
        $mstScrappers = $this->pagination($query, $request);
        $cas01Service = new Cas01Service();
        $sumQty = $cas01Service->handleSumQuantity($mstScrappers);
        $sumCaseQty = $sumQty['sumCaseQty'];
        $sumEmptyCaseQty = $sumQty['sumEmptyCaseQty'];
        $sumBagQty = $sumQty['sumBagQty'];
        return view('screens.cas.cas01.cas010',
            compact(
                'lstOffice', 'defaultSysDate', 'paramsSearch', 'mstScrappers',
                'sumCaseQty', 'sumEmptyCaseQty', 'sumBagQty', 'now'
            )
        );
    }

    /**
     * Handle CAS-010
     */
    public function handleCas010(Request $request) {
        $cas01Service = new Cas01Service();
        if ($request->schedule_picked_up == ValueUtil::constToValue('PlanCase.schedulePickedUp.UNREGISTERED_ONLY')) {
            $requestData = $request->except(['_token', 'collect_plan_date_from', 'collect_plan_date_to']);
        } else {
            $requestData = $request->except(['_token']);
        }
        if ($request->has('btn_export_csv')) {
            $cas01Service->handleExportCsv($requestData);
        } else {
            session()->forget('cas010');
            session()->put('cas010', $requestData);
            return redirect()->route('case.cas-010');
        }
    }

    /**
     * CAS-011_ケース集荷予定登録
     */
    public function cas011($id) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'NW',
        ]));
        $mstScrapper = $this->mstScrapperRepository->getOfficeCodeNameById($id);
        return view('screens.cas.cas01.cas011', 
            compact('mstScrapper')
        );
    }

    /**
     * Handle CAS-011
     */
    public function handleCas011(Cas011Request $request) {
        $params = $request->except(['_token', 'collect_receive_plan_date']);
        $params['office_code_to'] = auth()->user()->office_code;
        $params['transport_type'] = ValueUtil::constToValue('PlanCase.transportType.PAYMENT');
        $params['collect_plan_date'] = $request->collect_receive_plan_date;
        $params['receive_plan_date'] = $request->collect_receive_plan_date;
        $params['plan_date_adjusted_flg'] = isset($request->plan_date_adjusted_flg[0]) ? $request->plan_date_adjusted_flg[0] : 0;
        $baseRepository = new BaseRepository('plan_case');
        $result = $baseRepository->create($params);
        if ($result) {
            return redirect()->route('case.cas-010');
        } else {
            abort(400);
        }
    }

    /**
     * CAS-012_ケース集荷予定詳細
     */
    public function cas012($id) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'NW',
        ]));
        $planCase = $this->planCaseRepository->getPlanCaseWithMstScrapperById($id);
        if (empty($planCase)) {
            abort(404);
        }
        return view('screens.cas.cas01.cas012', 
            compact('planCase')
        );
    }

    /**
     * Handle CAS-012
     */
    public function handleCas012(Cas012Request $request, $id) {
        $params = $request->except(['_token']);
        $params['plan_date_adjusted_flg'] = isset($request->plan_date_adjusted_flg[0]) ? $request->plan_date_adjusted_flg[0] : 0;
        $baseRepository = new BaseRepository('plan_case');
        $result = $baseRepository->update($id, $params);
        if ($result) {
            return redirect()->route('case.cas-010');
        } else {
            abort(400);
        }
    }

}
