<?php

namespace App\Http\Controllers\Pal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Libs\ValueUtil;
use App\Repositories\{
    PalletRepository,
    MstOfficeRepository
};
use Illuminate\Support\Carbon;

class Pal08Controller extends Controller
{
    private PalletRepository $palletRepository;
    private MstOfficeRepository $mstOfficeRepository;
    public function __construct(
        PalletRepository $palletRepository,
        MstOfficeRepository $mstOfficeRepository
    ) {
        $this->palletRepository = $palletRepository;
        $this->mstOfficeRepository = $mstOfficeRepository;
    }
    // 【PAL-080】 パレット一覧（⑤RP）
    public function pal080(Request $request)
    {

        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'RP',
        ]));

        //get current date
        $defaultSystem = Carbon::now()->format('Y/m/d');
        $params = [
            'receive_complete_time_from' => $defaultSystem
        ];
        if (session()->has('pal080')) {
            $params = session()->get('pal080');
        }
        $officeCode = auth()->user()->office_code;
        $query = $this->palletRepository->queryPal080($params);
        $pallets = $this->pagination($query, $request);
        $mstOffice = $this->mstOfficeRepository;
        // get office list
        $mstOffices = $mstOffice->getOfficeForPal080($officeCode);
        $mstOfficeList = [];
        foreach ($mstOffices as $mstO) {
            $mstOfficeList[$mstO->office_name] = $mstO->office_code_name;
        }
        return view('screens.pal.pal08.pal080', compact('params', 'pallets','mstOfficeList'));
    }

    public function handlePal080(Request $request)
    {
        $requestData = $request->except(['_token']);
        $requestData['sort'] = true;
        session()->forget('pal080');
        session()->put('pal080', $requestData);
        return redirect()->route('palette.pal-080');
    }

    //【PAL-081】 パレット詳細（⑤RP）
    public function pal081($id){
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'RP',
        ]));
        $pallet = $this->palletRepository->queryPal081($id);
        return view('screens.pal.pal08.pal081',compact('pallet'));

    }
}
