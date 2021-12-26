<?php

namespace App\Http\Controllers\Mst;

use App\Http\Controllers\Controller;
use App\Libs\ValueUtil;
use App\Repositories\{
    MstScrapperRepository,
    MstOfficeRepository,
    BaseRepository,
};
use App\Http\Requests\Mst\Mst011Request;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class Mst01Controller extends Controller {

    public $mstScrapperRepository;
    public $mstOfficeRepository;

    public function __construct(
        MstScrapperRepository $mstScrapperRepository,
        MstOfficeRepository $mstOfficeRepository
    ) {
        $this->mstScrapperRepository = $mstScrapperRepository;
        $this->mstOfficeRepository = $mstOfficeRepository;
    }

    /**
     * MST-010_解体業者一覧
     */
    public function mst010(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'NW', 'SY', 'RP', 'JA1', 'JA2', 'admin'
        ]));
        $paramsSearch = [];
        if (session()->has('mst010')) {
            $paramsSearch = session()->get('mst010');
        }
        $query = $this->mstScrapperRepository->searchMst010($paramsSearch);
        $mstScrappers = $this->pagination($query, $request);
        $lstOfficeLocation = $this->mstOfficeRepository->getListOfficeLocation();
        return view('screens.mst.mst01.mst010',
            compact('paramsSearch', 'mstScrappers', 'lstOfficeLocation')
        );
    }

    /**
     * Handle MST-010
     */
    public function handleMst010(Request $request) {
        $requestData = $request->except(['_token']);
        session()->forget('mst010');
        session()->put('mst010', $requestData);
        return redirect()->route('master.mst-010');
    }

    /**
     * MST-011_解体業者詳細
     */
    public function mst011($id) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'NW', 'SY', 'RP', 'JA1', 'JA2', 'admin'
        ]));
        $mstScrapper = $this->mstScrapperRepository->getScrapperWithOfficeById($id);
        if (empty($mstScrapper)) {
            abort(404);
        }
        return view('screens.mst.mst01.mst011', 
            compact('mstScrapper')
        );
    }

    /**
     * Handle MST-011
     */
    public function handleMst011(Mst011Request $request, $id) {
        $data = $request->except(['_token']);
        $now = Carbon::now();
        if (Gate::check(ValueUtil::get('MstUser.permission')['NW'])) {
            $data['memo_tr_updated_at'] = $now;
        }
        if (
            Gate::check(ValueUtil::get('MstUser.permission')['JA1']) ||
            Gate::check(ValueUtil::get('MstUser.permission')['JA2'])
        ) {
            $data['memo_jarp_updated_at'] = $now;
        }
        $baseRepository = new BaseRepository('mst_scrapper');
        $result = $baseRepository->update($id, $data);
        if ($result) {
            return redirect()->route('master.mst-010');
        } else {
            abort(400);
        }
    }
}
