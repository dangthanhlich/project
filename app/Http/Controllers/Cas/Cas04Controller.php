<?php

namespace App\Http\Controllers\Cas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cas\{
    Cas040Request,
    Cas041Request,
};
use App\Libs\ValueUtil;
use App\Models\PlanCase;
use App\Repositories\{
    BaseRepository,
    PlanCaseRepository,
    MstScrapperRepository,
    CaseRepository
};
use App\Services\Cas\Cas04Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Cas04Controller extends Controller
{

    private $planCaseRepository;

    public function __construct(
        PlanCaseRepository $planCaseRepository,
        MstScrapperRepository $mstScrapperRepository,
        CaseRepository $caseRepository
    )
    {
        $this->mstScrapperRepository = $mstScrapperRepository;
        $this->planCaseRepository = $planCaseRepository;
        $this->caseRepository = $caseRepository;
    }

    /**
     * CAS-040_ケース受入予定一覧
     */
    public function cas040(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));

        $defaultSysDate = Carbon::now()->format('Y/m/d');

        $paramsSearch = [
            'schedule_picked_up' => array_key_first(ValueUtil::get('PlanCase.schedulePickedUpCas040')),
            'transport_type' => array_key_first(ValueUtil::get('PlanCase.transportTypeCas040')),
            'receive_plan_date_from' => $defaultSysDate,
        ];

        if (session()->has('cas040')) {
            $paramsSearch = session()->get('cas040');
        }

        $query = $this->planCaseRepository->search040($paramsSearch);
        $planCases = $this->pagination($query, $request);

        list($caseOfOffice, $caseOfScrapper) = $this->getCaseByOfficeCodeOrSrapperOfficeCode($planCases);

        $sumPlanCase = $this->sumPlanCaseByTransportType($paramsSearch['transport_type'], $paramsSearch);

        $sumCaseQty = $sumPlanCase['sumCaseQty'];
        $sumEmptyCaseQty = $sumPlanCase['sumEmptyCaseQty'];
        $sumBagQty = $sumPlanCase['sumBagQty'];

        $lstOffice = $this->planCaseRepository->getListScrapper();
        
        return view('screens.cas.cas04.cas040',
            compact('planCases','lstOffice','paramsSearch','sumCaseQty', 'sumEmptyCaseQty', 'sumBagQty','defaultSysDate', 'caseOfOffice', 'caseOfScrapper')
        );
    }

    public function handleCas040(Cas040Request $request) {
        if ($request->schedule_picked_up == ValueUtil::constToValue('PlanCase.schedulePickedUpCas040.UNREGISTERED_ONLY')) {
            $requestData = $request->except(['_token', 'receive_plan_date_from', 'receive_plan_date_to']);
        } else {
            $requestData = $request->except(['_token']);
        }
        if ($request->has('btn_export_csv')) {
            $this->exportCSV($requestData);
        } else {
            session()->forget('cas040');
            session()->put('cas040', $requestData);
            return redirect()->route('case.cas-040');
        }
    }

    public function exportCSV($requestData) {
        $planCases = $this->planCaseRepository->search040($requestData)->get();

        list($caseOfOffice, $caseOfScrapper) = $this->getCaseByOfficeCodeOrSrapperOfficeCode($planCases);

        $cas04Service = new Cas04Service();
        $cas04Service->handleExportCsv($planCases, $caseOfOffice, $caseOfScrapper);
    }


    public function getPlanCaseById($id){
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        $planCase = PlanCase::find($id);
        return response()->json(['data' => $planCase]);
    }

    public function updatePlanCase(Request $request){
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        $data = PlanCase::find($request->id);
        $data->receive_plan_date = $request->receive_plan_date;
        $data->receive_plan_memo = $request->receive_plan_memo;
        $data->save();
        return response()->json($data);
    }
    /**
     * CAS-041_ケース受入予定登録
     */
    public function cas041($id) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        $mstScrapper = $this->mstScrapperRepository->getOfficeCodeNameById($id);
        return view('screens.cas.cas04.cas041',
            compact('mstScrapper')
        );
    }

    /**
     * handle CAS-041
     */
    public function handleCas041(Cas041Request $request) {
        $this->planCaseRepository->create($request->input(), 'CAS-040');
        return redirect()->route('case.cas-040');
    }

    /**
     * CAS-042_ケース受入予定詳細
     */
    public function cas042($id) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        // get plan_case
        $planCase = $this->planCaseRepository->getPlanCaseWithMstScrapperById($id, 'id');
        if (empty($planCase)) {
            abort(404);
        }
        return view('screens.cas.cas04.cas042', compact('planCase'));
    }

    /**
     * handle CAS-042
     */
    public function handleCas042(Cas041Request $request, $id) {
        $this->planCaseRepository->update($id, $request->input());
        return redirect()->route('case.cas-040');
    }

    public function getCaseByOfficeCodeOrSrapperOfficeCode($planCases) {
        $scrapperOfficeCodes = $trOfficeCodes = [];
        foreach ($planCases as $planCase) {
            if ($planCase->plan_case_type == getConstValue('PlanCase.planCaseTypeCas040.SCRAPPER_PLAN_CASE')) {
                $scrapperOfficeCodes[$planCase->office_code] = $planCase->office_code;
            } else {
                $trOfficeCodes[$planCase->office_code] = $planCase->office_code;
            }
        }

        $caseOfOffice = !empty($trOfficeCodes) 
            ? $this->caseRepository->getCaseOfOfficeForCas040($trOfficeCodes)
                ->mapWithKeys(function ($item, $key) {
                    return [$item['tr_office_code'] => $item];
                })->toArray() 
            : [];

        $caseOfScrapper = !empty($scrapperOfficeCodes) 
            ? $this->caseRepository->getCaseOfScrapperForCas040($scrapperOfficeCodes)
                ->mapWithKeys(function ($item, $key) {
                    return [$item['scrapper_office_code'] => $item];
                })->toArray()
            : [];

        return [$caseOfOffice, $caseOfScrapper];
    }

    public function sumPlanCaseByTransportType($transportType = 1, array $paramsSearch = [])
    {
        $data = [
            'sumCaseQty' => 0,
            'sumEmptyCaseQty' => 0,
            'sumBagQty' => 0
        ];
        
        if ($transportType == getConstValue('PlanCase.transportTypeCas040.ALL') || $transportType == getConstValue('PlanCase.transportTypeCas040.BRING_IN')) {
            $query = $this->planCaseRepository
                ->queryMstScrapperWithPlanCaseCas040($paramsSearch)
                ->select(DB::raw("
                    sum(distinct(plan_case.case_qty)) as sum_case_qty,
                    sum(distinct(plan_case.empty_case_qty)) as sum_empty_case_qty,
                    sum(distinct(plan_case.bag_qty)) as sum_bag_qty
                "));

            $query->getQuery()->groups = null;
            $sumPlanCaseScrapper = $query->first();

            $data['sumCaseQty'] += $sumPlanCaseScrapper->sum_case_qty;
            $data['sumEmptyCaseQty'] += $sumPlanCaseScrapper->sum_empty_case_qty;
            $data['sumBagQty'] += $sumPlanCaseScrapper->sum_bag_qty;
        }

        if ($transportType == getConstValue('PlanCase.transportTypeCas040.ALL') || $transportType == getConstValue('PlanCase.transportTypeCas040.TRANSPORTATION_NW')) {
            $sumPlanCaseOffice = $this->planCaseRepository
                ->queryPlanCaseWithMstOfficeCas040($paramsSearch)
                ->select(DB::raw("
                    sum(plan_case.case_qty) as sum_case_qty,
                    sum(plan_case.empty_case_qty) as sum_empty_case_qty,
                    sum(plan_case.bag_qty) as sum_bag_qty
                "))
                ->first();
                
            $data['sumCaseQty'] += $sumPlanCaseOffice->sum_case_qty;
            $data['sumEmptyCaseQty'] += $sumPlanCaseOffice->sum_empty_case_qty;
            $data['sumBagQty'] += $sumPlanCaseOffice->sum_bag_qty;
        }
        
        return $data;
    }

    public function handleRedirectCas080(Request $request)
    {
        $requestData = $request->except(['_token']);
        session()->forget('cas080');
        session()->put('cas080', $requestData);
        return redirect()->route('case.cas-080');
    }
}
