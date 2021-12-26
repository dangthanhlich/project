<?php

namespace App\Http\Controllers\Mst;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mst\{Mst031Request, Mst032Request};
use App\Libs\ValueUtil;
use App\Repositories\MstUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class Mst03Controller extends Controller {

    public $mstUserRepository;

    public function __construct(MstUserRepository $mstUserRepository) {
        $this->mstUserRepository = $mstUserRepository;
    }

    /**
     * MST-030_ユーザー一覧
     */
    public function mst030(Request $request) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'NW', 'SY', 'SD', 'RP', 'JA1', 'admin'
        ]));
        // get user_type list
        $userTypeList = $this->getUserTypeList('mst030');
        // get search params from session
        $paramsSearch = [];
        if (session()->has('mst030')) {
            $paramsSearch = session()->get('mst030');
        }
        // get mst_user list
        $query = $this->mstUserRepository->search($paramsSearch);
        $mstUsers = $this->pagination($query, $request);
        return view('screens.mst.mst03.mst030',
            compact('mstUsers', 'userTypeList', 'paramsSearch')
        );
    }

    /**
     * handle MST-030
     */
    public function handleMst030(Request $request) {
        $params = $request->except(['_token']);
        session()->forget('mst030');
        session()->put('mst030', $params);
        return redirect()->route('master.mst-030');
    }

    /**
     * MST-031_ユーザー登録
     */
    public function mst031() {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'NW', 'SY', 'SD', 'RP', 'JA1', 'admin'
        ]));
        // SＹ,NW,SD,RP and office_admin_flg <> 1: not access
        if ((Gate::check(ValueUtil::get('MstUser.permission')['NW']) ||
            Gate::check(ValueUtil::get('MstUser.permission')['SY']) ||
            Gate::check(ValueUtil::get('MstUser.permission')['SD']) ||
            Gate::check(ValueUtil::get('MstUser.permission')['RP']))
            && auth()->user()->office_admin_flg !== ValueUtil::constToValue('MstUser.officeAdminFlg.WITH_AUTHORITY')
        ) {
            abort(404);
        }
        // get user_type list
        $userTypeList = $this->getUserTypeList();
        return view('screens.mst.mst03.mst031',
            compact('userTypeList')
        );
    }

    /**
     * handle event for mst031 form submit
     */
    public function handleMst031(Mst031Request $request) {
        // insert new mst_user
        $mstUser = $this->mstUserRepository->create($request->input());
        if ($mstUser) {
            return redirect()->route('master.mst-030');
        } else {
            return abort(400);
        }
    }

    /**
     * MST-032_ユーザー詳細
     */
    public function mst032($id) {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'NW', 'SY', 'SD', 'RP', 'JA1', 'JA2', 'admin'
        ]));
        // get user
        $mstUser = $this->mstUserRepository->getUser($id);
        if (!$mstUser) {
            abort(404);
        }
        // get user_type list
        $userTypeList = $this->getUserTypeList();
        return view('screens.mst.mst03.mst032', [
            'mstUser' => $mstUser,
            'userTypeList' => $userTypeList,
        ]);
    }

    /**
     * handle event for mst032 form submit
     */
    public function handleMst032(Mst032Request $request, $id) {
        // insert new mst_user
        $mstUser = $this->mstUserRepository->update($id, $request->input());
        if ($mstUser) {
            $userLogin = auth()->user();
            if ($userLogin->user_type === ValueUtil::constToValue('MstUser.userType.SELF_RECONCILIATION') &&
                $userLogin->jarp_type === ValueUtil::constToValue('MstUser.jarpType.PUBLIC_RELATION')
            ) {
                return redirect()->route('common.com-020');
            } else {
                return redirect()->route('master.mst-030');
            }
        } else {
            return abort(400);
        }
    }

    /**
     * enable/disable user
     * 有効/無効 button
     */
    public function setUserStatusUrl(Request $request) {
        try {
            $id = $request->input('user_id');
            $status = $request->input('status');
            $mstUser = $this->mstUserRepository->updateUserInvalidFlg($id, $status);
            return response()->json([
                'hasError' => $mstUser ? false : true,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'hasError' => true,
            ]);
        }
    }

    /**
     * get user_type list for 権限 select box
     */
    private function getUserTypeList(string $screen = '') {
        // get user_type list
        $userTypeList = ValueUtil::get('MstUser.userType');
        //  login user has user_type = 2:自再協
        if (auth()->user()->user_type === ValueUtil::constToValue('MstUser.userType.SELF_RECONCILIATION')) {
            // remove admin option
            unset($userTypeList[1]);
        }
        // screen MST-030
        if ($screen === 'mst030') {
            // login user has user_type = 3:事業所
            if (auth()->user()->user_type === ValueUtil::constToValue('MstUser.userType.OFFICE')) {
                // only show office option
                unset($userTypeList[1]);
                unset($userTypeList[2]);
            }
        } else {
            // login user has user_type = 3:事業所 AND office_admin_flg = 1:権限あり
            if (auth()->user()->user_type === ValueUtil::constToValue('MstUser.userType.OFFICE') &&
                auth()->user()->office_admin_flg === ValueUtil::constToValue('Common.authority.ON')
            ) {
                // only show office option
                unset($userTypeList[1]);
                unset($userTypeList[2]);
            }
        }

        return $userTypeList;
    }

    /**
     * use for check unique column (loginId, userName)
     */
    public function checkUniqueData(Request $request) {
        try {
            $type = $request->query('type');
            $dataCheck = $request->query('dataCheck');
            $userId = '';
            if (!empty($request->query('id'))) {
                $userId = $request->query('id');
            }
            $countMstUser = $this->mstUserRepository->checkUniqueData($type, $dataCheck, $userId);

            return response()->json([
                'hasError' => $countMstUser > 0 ? TRUE : FALSE,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'hasError' => true,
            ]);
        }
    }

}