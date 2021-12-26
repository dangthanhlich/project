<?php

namespace App\Http\Controllers\Mst;

use App\Http\Controllers\Controller;
use App\Libs\ValueUtil;
use App\Repositories\{
    MstCarRepository,
    MstOfficeRepository,
};
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Mst05Controller extends Controller
{
    public $mstCarRepository;
    public $mstOfficeRepository;

    public function __construct(
        MstCarRepository $mstCarRepository,
        MstOfficeRepository $mstOfficeRepository,
    ) {
        $this->mstCarRepository = $mstCarRepository;
        $this->mstOfficeRepository = $mstOfficeRepository;
    }

    /**
     * MST-050_認定車両一覧
     */
    public function mst050(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'JA1', 'JA2', 'admin'
        ]));
        $paramsSearch = [];
        if (session()->has('mst050')) {
            $paramsSearch = session()->get('mst050');
        }
        $query = $this->mstCarRepository->search($paramsSearch);
        $mstCars = $this->pagination($query, $request);
        $lstOffice = $this->mstOfficeRepository->getListOfficeBy2ndTrOfficeFlg();
        return view('screens.mst.mst05.mst050',
            compact('paramsSearch', 'mstCars', 'lstOffice')
        );
    }

    /**
     * Handle MST-050
     */
    public function handleMst050(Request $request) {
        $requestData = $request->except(['_token']);
        session()->forget('mst050');
        session()->put('mst050', $requestData);
        return redirect()->route('master.mst-050');
    }
}
