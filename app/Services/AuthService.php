<?php

namespace App\Services;

use App\Libs\ValueUtil;
use Jenssegers\Agent\Agent;

class AuthService {

    public function loginRedirect($user) {
        $defaultRoute = 'common.com-020';
        //---- REDIRECT ----//
        // user_type = 3 &&
        // tr_office_flg / sy_office_flg / rp_office_flg !== 1:ON &&
        // 2nd_tr_office_flg = 1:ON
        if ($user->user_type === ValueUtil::constToValue('MstUser.userType.OFFICE') &&
            $user->tr_office_flg !== ValueUtil::constToValue('Common.authority.ON') &&
            $user->sy_office_flg !== ValueUtil::constToValue('Common.authority.ON') &&
            $user->rp_office_flg !== ValueUtil::constToValue('Common.authority.ON') &&
            $user['2nd_tr_office_flg'] === ValueUtil::constToValue('Common.authority.ON')
        ) {
            $defaultRoute = 'palette.pal-050';
        }

        // device login !== PC &&
        // user_type = 3:事業所 &&
        // sy_office_flg or rp_office_flg = 1:ON
        $agent = new Agent();
        if (!$agent->isDesktop() &&
            $user->user_type === ValueUtil::constToValue('MstUser.userType.OFFICE') &&
            (
                $user->sy_office_flg === ValueUtil::constToValue('Common.authority.ON') ||
                $user->rp_office_flg === ValueUtil::constToValue('Common.authority.ON')
            )
        ) {
            $defaultRoute = 'common.com-030';
        }

        return redirect()->intended(route($defaultRoute));
    }

}