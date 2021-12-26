<?php

namespace App\Http\Controllers\Rep;

use App\Http\Controllers\Controller;
use App\Libs\ValueUtil;
use App\Repositories\{
    MstOfficeRepository,
    RecycleReportRepository,
};
use App\Services\Rep\Rep01Service;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class Rep01Controller extends Controller {
    private $mstOfficeRepository;
    private $recycleReportRepository;
    private $rep01Service;

    public function __construct(
        MstOfficeRepository $mstOfficeRepository,
        RecycleReportRepository $recycleReportRepository,
        Rep01Service $rep01Service,
    ) {
        $this->mstOfficeRepository = $mstOfficeRepository;
        $this->recycleReportRepository = $recycleReportRepository;
        $this->rep01Service = $rep01Service;
    }

    /**
     * REP-010_再資源化情報一覧
     */
    public function rep010(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'RP', 'JA1', 'JA2', 'admin',
        ]));
        $searchParams = [];
        if (session()->has('rep010')) {
            $searchParams = session()->get('rep010');
        }
        $lstOffice = [];
        if (Gate::check('is_RP')) {
            $searchParams['rp_office_code'] = auth()->user()->office_code;
        } else {
            $lstOffice = $this->mstOfficeRepository->getLisRpOffice();
        }
        // search recycle_report by conditions
        $recycleReports = $this->recycleReportRepository->searchRep01($searchParams);
        $recycleReports = $this->pagination($recycleReports, $request);

        return view('screens.rep.rep01.rep010',
            compact(
                'recycleReports',
                'searchParams',
                'lstOffice',
            ),
        );
    }

    /**
     * Handle REP-010
     */
    public function handleRep010(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'RP', 'JA1', 'JA2', 'admin',
        ]));
        $requestData = $request->except('excel_import');
        if ($request->has('btn_export_csv')) {
            if (Gate::check('is_RP')) {
                $requestData['rp_office_code'] = auth()->user()->office_code;
            }
            $this->rep01Service->handleExportCsv($requestData);
        } else if ($request->hasFile('excel_import')) {
            if (!$this->rep01Service->handleImportExcel($request->file('excel_import'))) {
                abort(400);
            }
            return redirect()->route('recycle.rep-010');
        } else {
            session()->forget('rep010');
            session()->put('rep010', $requestData);
            return redirect()->route('recycle.rep-010');
        }
    }

}