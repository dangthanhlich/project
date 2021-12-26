<?php

namespace App\Repositories;

use App\Libs\ValueUtil;
use App\Models\MstCar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MstCarRepository {
    /**
     * Search mst050
     * @param array $params
     * @return mixed
     */
    public function search($params) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = MstCar::where('mst_car.del_flg', '<>', $flgDeleted);
        if (isset($params['office_code']) && strlen($params['office_code']) > 0) {
            $query->where('mst_office.office_code', $params['office_code']);
        }
        if (isset($params['car_no']) && strlen($params['car_no']) > 0) {
            $query->where('mst_car.car_no', 'like', "%{$params['car_no']}%");
        }
        $query = $query
            ->select([
                'mst_car.management_no',
                'mst_car.company_code_2nd_tr',
                'mst_car.car_no',
                'mst_car.car_type',
                'mst_office.office_name as office_name',
            ])
            ->leftJoin('mst_office', function($join) use($flgDeleted) {
                $join
                    ->on('mst_office.office_code', '=', 'mst_car.company_code_2nd_tr')
                    ->where('mst_office.del_flg', '<>', $flgDeleted);
            })
            ->orderBy('mst_car.company_code_2nd_tr')
            ->orderBy('mst_car.id');
        return $query;
    }
}
