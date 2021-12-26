<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\MstOfficeRepository;
use Illuminate\Http\Request;

class CommonController extends Controller {

    public function getOfficeName(Request $request) {
        try {
            $officeCode = $request->query('officeCode');
            $mstOfficeRepository = new MstOfficeRepository();
            $mstOffice = $mstOfficeRepository->getOfficeName($officeCode);

            return response()->json([
                'hasError' => false,
                'office_name' => $mstOffice[0]->office_name,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'hasError' => true,
            ]);
        }
    }

    public function resetSearch(Request $request) {
        try {
            $screenSession = $request->screen;
            if ($request->session()->has($screenSession)) {
                $request->session()->forget($screenSession);
            }
            return response()->json([
                'hasError' => false,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'hasError' => true,
            ]);
        }
    }

    public function index() {
        return view('screens.mst.test');
    }

    public function signature() {
        return view('screens.mst.test1');
    }

}
