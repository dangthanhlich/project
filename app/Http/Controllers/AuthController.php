<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Libs\{
    ConfigUtil,
    ValueUtil,
};
use App\Repositories\MstUserRepository;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Session,
    Auth,
};

class AuthController extends Controller {

    /**
     * 【COM-010】 ログイン
     */
    public function login() {
        return view('screens.auth.login');
    }

    /**
     * handle login event
     */
    public function handleLogin(Request $request) {
        $mstUser = new MstUserRepository();
        $credentials = $request->only('loginId', 'password');
        $auth = [
            'login_id'    => $credentials['loginId'],
            'password'    => $credentials['password'],
        ];
        $user = $mstUser->getLoginUser($auth);
        if (empty($user)) {
            return redirect('login')->withErrors(ConfigUtil::getMessage('e-001', ['ログインID', 'パスワード']));
        }
        if (Auth::loginUsingId($user->id)) {
            // get login user info
            $user = Auth::user();

            // Auth.AdminUser
            if ($user->user_type === ValueUtil::constToValue('MstUser.userType.SYSTEM_ADMIN')) {
                $request->session()->put('AdminUser', [
                    'id'        => $user->id,
                    'user_name' => $user->user_name,
                    'user_type'      => $user->user_type
                ]);
            }

            // Auth.JarpUser
            if ($user->user_type === ValueUtil::constToValue('MstUser.userType.SELF_RECONCILIATION')) {
                $request->session()->put('JarpUser', [
                    'id'        => $user->id,
                    'user_name' => $user->user_name,
                    'user_type'      => $user->user_type,
                    'jarp_type' => $user->jarp_type,
                ]);
            }

            // Auth.OfficeUser
            if ($user->user_type === ValueUtil::constToValue('MstUser.userType.OFFICE')) {
                $request->session()->put('OfficeUser', [
                    'id'                => $user['id'],
                    'user_name'         => $user['user_name'],
                    'user_type'         => $user['user_type'],
                    'office_admin_flg'  => $user['office_admin_flg'],
                    'office_code'       => $user['office_code'],
                    'tr_office_flg'     => $user['tr_office_flg'],
                    'sy_office_flg'     => $user['sy_office_flg'],
                    '2nd_tr_office_flg' => $user['2nd_tr_office_flg'],
                    'rp_office_flg'     => $user['rp_office_flg']
                ]);
            }

            // update last_login
            $mstUser->updateLastLogin($user->id);

            $authService = new AuthService();
            // login success and redirect to another page
            return $authService->loginRedirect($user);
        }

        return redirect('login')->withErrors(ConfigUtil::getMessage('e-001', ['ログインID', 'パスワード']));
    }

    public function logout() {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }

}
