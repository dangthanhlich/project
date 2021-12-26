<?php

namespace App\Http\Controllers\Pal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Pal\Pal010Request;
use App\Services\Pal\Pal01Service;
use Illuminate\Support\Arr;
use App\Libs\{
    ValueUtil,
    ConfigUtil
};

class Pal01Controller extends Controller
{
    private $pal01Service;

    public function __construct(
        Pal01Service $pal01Service
    )
    {
        $this->pal01Service = $pal01Service;
    }
    /**
     *【PAL-010】 [SP] パレット・ケース紐付登録
     */
    public function pal010() {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'dismantling', 'SY'
        ]));
        $paramsSearch = [];
        if (session()->has('pal010')) {
            $paramsSearch = session()->get('pal010');
            $paramsSearch['officeCodeUser'] = auth()->user()->office_code;
        }
        // get data display to screen
        $dataDisplay = $this->pal01Service->getDataToDisplayOnScreen($paramsSearch);
        return view('screens.pal.pal01.pal010',
            compact('paramsSearch', 'dataDisplay')
        );
    }

    /**
     * handle PAL-010
     */
    public function handlePal010(Pal010Request $request) {
        $requestData = $request->except(['_token']);
        session()->forget('pal010');
        session()->put('pal010', $requestData);
        return redirect()->route('palette.pal-010');
    }

    /**
     * Ajax insert/update pallet_case
     */
    public function processPalletCase(Request $request) {
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY',
        ]));
        $data = $this->pal01Service->processPalletCase($request);
        if ($data) {
            if ($request->session()->has('pal010')) {
                $request->session()->forget('pal010');
            }
        }
        return response()->json($data);
    }
}
