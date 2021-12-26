<?php

namespace App\Http\Controllers\Com;

use App\Http\Controllers\Controller;
use App\Libs\ValueUtil;
use Illuminate\Support\Arr;

class Com03Controller extends Controller
{

    /**
     * COM-030_業務選択
     */
    public function com030() {
        // check permission
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), [
            'SY', 'RP',
        ]));
        return view('screens.com.com03.com030');
    }

}
