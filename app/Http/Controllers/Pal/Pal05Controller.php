<?php

namespace App\Http\Controllers\Pal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\{
    Carbon,
    Arr
};
use App\Libs\ValueUtil;
use App\Repositories\PalTransportRepository;
use App\Services\Pal\Pal05Service;

class Pal05Controller extends Controller
{
    private PalTransportRepository $palTransportRepository;

    public function __construct(PalTransportRepository $palTransportRepository)
    {
        $this->palTransportRepository = $palTransportRepository;
    }

    // 【PAL-050】 運搬記録（④二次）
    public function pal050(Request $request)
    {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SD',
        ]));
        // first day of month
        $defaultFirst = Carbon::now()->subMonth()->firstOfMonth()->format('Y/m/d');
        // last day of month
        $defaultEnd = Carbon::now()->subMonth()->lastOfMonth()->format('Y/m/d');
        $params = [
            'deliver_complete_time_from' => $defaultFirst,
            'deliver_complete_time_to' => $defaultEnd
        ];
        if (session()->has('pal050')) {
            $params = session()->get('pal050');
        }
        $officeCode = auth()->user()->office_code;

        $query = $this->palTransportRepository->queryPal050($officeCode, $params);
        $palTransport = $this->palTransportRepository;
        $items = $this->pagination($query, $request);
        return view('screens.pal.pal05.pal050', compact('items', 'params', 'palTransport'));
    }

    public function handlePal050(Request $request)
    {
        $pal05Service = new Pal05Service();

        $requestData = $request->except(['_token']);
        if ($request->has('btn_export_csv')) {
            $pal05Service->handleExportCsv($requestData);
        } else {
            session()->forget('pal050');
            session()->put('pal050', $requestData);
            return redirect()->route('palette.pal-050');
        }
    }

    public function pal051($id)
    {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SD',
        ]));
        $palTransport = $this->palTransportRepository;
        $palTransItem = $this->palTransportRepository->queryPal051($id);
        return view('screens.pal.pal05.pal051', compact('palTransItem', 'palTransport'));
    }
}
