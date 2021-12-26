<?php

namespace App\Http\Controllers\Cas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cas\Cas051Request;
use App\Libs\ValueUtil;
use App\Repositories\MstOfficeRepository;
use App\Services\Cas\Cas05Service;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Cas05Controller extends Controller
{
    private $cas05Service;
    private $mstOfficeRepository;

    public function __construct(
        Cas05Service $cas05Service,
        MstOfficeRepository $mstOfficeRepository
    ) {
        $this->cas05Service = $cas05Service;
        $this->mstOfficeRepository = $mstOfficeRepository;
    }

    /**
     * CAS-050_運搬NW利用受入ケース選択
     */
    public function cas050(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        $userOfficeCode = auth()->user()->office_code;
        // get user mst_office company_code
        $userCompanyCode = $this->mstOfficeRepository->getByOfficeCode($userOfficeCode);
        $userCompanyCode = $userCompanyCode ? $userCompanyCode['company_code'] : null;
        // get list mst_office
        $lstOfficeAssociatedScrapper = $this->cas05Service->getListOfficeAssociatedScrapper($userOfficeCode);

        return view('screens.cas.cas05.cas050',
            compact(
                'userCompanyCode',
                'lstOfficeAssociatedScrapper',
            )
        );
    }

    /**
     * Ajax search case, temp_case by office_code
     * 
     * @param string $officeCode
     */
    public function cas050SearchByOfficeCode(Request $request) {
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        $params = $request->only(['office_code']);
        $params = [
            'tr_office_code' => $params['office_code'],
            'case_status' => [
                ValueUtil::constToValue('Case.caseStatus.COLLECTED'),
                ValueUtil::constToValue('Case.caseStatus.BEFORE_INSPECTION'),
            ],
            'transport_type' => [
                ValueUtil::constToValue('Case.transportType.PAYMENT'),
                ValueUtil::constToValue('Case.transportType.NONE'),
            ],
        ];
        $orderBy = [
            'collect_complete_time' => 'ASC',
            'case_id' => 'ASC',
        ];
        $data = $this->cas05Service->searchCaseByConditions($params, $orderBy);
        return response()->json(['data' => $data]);
    }

    /**
     * Handle CAS-050 update case(temp_case).case_status
     * 
     * @param string|int $caseId
     */
    public function cas050UpdateCaseStatus(Request $request) {
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        $params = $request->only(['case_id', 'case_status', 'isTempCase']);
        $result = $this->cas05Service->updateCaseStatus(
            $params['case_id'],
            $params['case_status'],
            $params['isTempCase']
        );
        return response()->json(['result' => $result]);
    }

    /**
     * CAS-051_運搬NW利用電子サイン
     */
    public function cas051($officeCode) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        $params = [
            'tr_office_code' => $officeCode,
            'case_status' => [
                ValueUtil::constToValue('Case.caseStatus.BEFORE_INSPECTION'),
            ],
            'transport_type' => [
                ValueUtil::constToValue('Case.transportType.PAYMENT'),
            ],
        ];
        $orderBy = [
            'receive_complete_time' => 'ASC',
        ];
        $cases = $this->cas05Service->searchCaseByConditions($params, $orderBy, 'cas051');

        return view('screens.cas.cas05.cas051',
            compact(
                'officeCode',
                'cases',
            )
        );
    }

    /**
     * Handle save data CAS-051
     * 
     * @param string $officeCode
     * @param Cas051Request $request
     */
    public function handleCas051($officeCode, Cas051Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        $saveResult = $this->cas05Service->saveCas051($officeCode, $request);

        if ($saveResult) {
            return redirect()->route('common.com-030');
        }
        return abort(400);
    }

}
