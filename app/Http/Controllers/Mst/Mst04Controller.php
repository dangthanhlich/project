<?php

namespace App\Http\Controllers\Mst;

use App\Http\Controllers\Controller;
use App\Libs\ValueUtil;
use App\Repositories\{
    MstOfficeRepository,
    MstPriceRepository,
};
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class Mst04Controller extends Controller {

    private $mstPriceRepository;
    private $mstOfficeRepository;

    public function __construct(
        MstPriceRepository $mstPriceRepository,
        MstOfficeRepository $mstOfficeRepository,
    ) {
        $this->mstPriceRepository = $mstPriceRepository;
        $this->mstOfficeRepository = $mstOfficeRepository;
    }

    /**
     * MST-040_単価一覧
     */
    public function mst040(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'JA1', 'admin'
        ]));
        // get search params from session
        $paramsSearch = [];
        if (session()->has('mst040')) {
            $paramsSearch = session()->get('mst040');
        }
        // get scrapper list
        $mstOffices = $this->mstOfficeRepository->getOfficeForMst040();
        $mstOfficeList = [];
        foreach ($mstOffices as $mstOffice) {
            $mstOfficeList[$mstOffice->office_code] = $mstOffice->office_code_name;
        }
        // get mst_price list
        $query = $this->mstPriceRepository->search($paramsSearch);
        $mstPrices = $this->pagination($query, $request);

        return view('screens.mst.mst04.mst040',
            compact('mstPrices', 'mstOfficeList', 'paramsSearch')
        );
    }

    /**
     * handle MST-040
     */
    public function handleMst040(Request $request) {
        $params = $request->except(['_token']);
        session()->forget('mst040');
        session()->put('mst040', $params);
        return redirect()->route('master.mst-040');
    }
}