<?php

namespace App\Http\Controllers\Mst;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mst\{
    Mst021Request,
    Mst022Request,
};
use App\Libs\ValueUtil;
use App\Repositories\MstOfficeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Mst02Controller extends Controller {

    private $mstOfficeRepostory;

    public function __construct(MstOfficeRepository $mstOfficeRepostory) {
        $this->mstOfficeRepostory = $mstOfficeRepostory;
    }

    /**
     * MST-020_その他業者一覧
     */
    public function mst020(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'NW', 'SY', 'RP', 'JA1', 'JA2', 'admin',
        ]));
        $searchParams = [];
        if (session()->has('mst020')) {
            $searchParams = session()->get('mst020');
        }
        $authOfficeCode = auth()->user()->office_code;
        // search mst_office by conditions
        $searchParams['search_office_code'] = true;
        $mstOffices = $this->mstOfficeRepostory->search($searchParams, $authOfficeCode);
        $mstOffices = $this->pagination($mstOffices, $request);

        return view('screens.mst.mst02.mst020',
            compact(
                'mstOffices',
                'searchParams',
            ),
        );
    }

    /**
     * Handle MST-020
     */
    public function handleMst020(Request $request) {
        $requestData = $request->all();
        session()->forget('mst020');
        session()->put('mst020', $requestData);
        return redirect()->route('master.mst-020');
    }

    /**
     * MST-021_その他業者登録
     */
    public function mst021() {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'NW', 'SY', 'RP', 'JA1', 'JA2', 'admin',
        ]));

        return view('screens.mst.mst02.mst021');
    }

    /**
     * handle event for mst021 form submit
     */
    public function handleMst021(Mst021Request $request) {
        // insert new mst_office
        $mstUser = $this->mstOfficeRepostory->create($request->input());
        if ($mstUser) {
            return redirect()->route('master.mst-020');
        } else {
            return abort(400);
        }
    }

    /**
     * MST-022_その他の業者詳細
     */
    public function mst022($id) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'NW', 'SY', 'RP', 'JA1', 'JA2', 'admin',
        ]));
        // get mst_office
        $mstOffice = $this->mstOfficeRepostory->getById($id);
        if (!$mstOffice) {
            abort(404);
        }
        // process 業者区分 checkbox
        $officeFlg = [];
        $officeFlgConstList = ValueUtil::getConstList('MstOffice.officeFlgScreen');
        foreach ($officeFlgConstList as $officeFlgConst) {
            $vofficeFlgValue = ValueUtil::constToValue('MstOffice.officeFlgScreen.'.$officeFlgConst);
            $officeFlgDb = strtolower($officeFlgConst). '_flg';
            if (
                isset($mstOffice[$officeFlgDb]) &&
                $mstOffice[$officeFlgDb] == ValueUtil::constToValue('MstOffice.officeFlg.WITH_AUTHORITY')
            ) {
                $officeFlg[] = $vofficeFlgValue;
            }
        }
        // check is created by running batch
        $isBatch = $mstOffice->created_by === 0;

        return view('screens.mst.mst02.mst022',
            compact(
                'mstOffice',
                'officeFlg',
                'isBatch',
            ),
        );
    }

    /**
     * Handle event for mst022 form submit
     */
    public function handleMst022(Mst022Request $request, $id) {
        // update mst_office
        $mstUser = $this->mstOfficeRepostory->update($id, $request->input());
        if ($mstUser) {
            return redirect()->route('master.mst-020');
        } else {
            return abort(400);
        }
    }

}