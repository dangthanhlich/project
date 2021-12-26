<?php

namespace App\Http\Controllers\Pal;

use App\Http\Controllers\Controller;
use App\Libs\ValueUtil;
use App\Repositories\{
    CaseRepository,
    PalletRepository,
};
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Pal02Controller extends Controller
{
    private CaseRepository $caseRepository;
    private PalletRepository $palletRepository;

    public function __construct(
        CaseRepository $caseRepository,
        PalletRepository $palletRepository,
    )
    {
        $this->caseRepository = $caseRepository;
        $this->palletRepository = $palletRepository;
    }

    /**
     * PAL_020_パレット一覧（③SY）
     */
    public function pal020(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));

        //list search
        if (session()->has('pal020')) {
            $params = session()->get('pal020');
        } else {
            $params = ['pallet_status' => [getConstValue('Pallet.palletStatus.BEFORE_LOADING')]];
        }

        $officeCode = auth()->user()->office_code;

        $query = $this->palletRepository->queryPal02($officeCode, $params);

        $pallets = $this->pagination($query, $request);

        return view('screens.pal.pal02.pal020',
            compact('pallets', 'params')
        );
    }

    public function handlePal020(Request $request) {
        $requestData = $request->except(['_token']);
        session()->forget('pal020');
        session()->put('pal020', $requestData);
        return redirect()->route('palette.pal-020');
    }

    /**
     * PAL_021_パレット詳細（③SY
     */
    public function pal021($palletNo) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        
        $pallet = $this->palletRepository->queryPal021($palletNo);
        if (empty($pallet)) {
            abort(404);
        }

        return view('screens.pal.pal02.pal021',
            compact('pallet')
        );
    }
}
