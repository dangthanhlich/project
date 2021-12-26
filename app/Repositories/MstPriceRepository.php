<?php

namespace App\Repositories;

use App\Libs\ValueUtil;
use App\Models\MstPrice;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MstPriceRepository {

    /**
     * search
     */
    public function search($params) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $officeCode = isset($params['office_code']) ? $params['office_code'] : null;
        $priceType = isset($params['price_type']) ? $params['price_type'] : null;
        $regionCode = isset($params['area_code']) ? $params['area_code'] : null;
        $query = MstPrice::query();
        $query->where('del_flg', '<>', $flgDeleted);
        if (!empty($officeCode)) {
            $query->where('sy_office_code', $officeCode);
        }
        if (!empty($priceType)) {
            $query->whereIn('price_type', $priceType);
        }
        if (!empty($regionCode)) {
            $query->where('region_code', $regionCode);
        }
        $query->orderBy('effective_start_date');
        $query->orderBy('id');

        return $query;
    }

    /**
     * Get transport_fee with params
     * @param array $params
     * 
     * @return int
     */
    public function getDataWithConditions($params = []) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $sysDate = Carbon::now()->format('Y-m-d');
        $query = MstPrice::where([
                    ['mst_price.del_flg', '<>', $flgDeleted],
                    ['mst_price.price_type', $params['transport_type']],
                    ['mst_price.sy_office_code', $params['sy_office_code']],
                    [DB::raw('DATE(mst_price.effective_start_date)'), '<=', $sysDate],
                    [DB::raw('DATE(mst_price.effective_end_date)'), '>=', $sysDate]
                ])->first();
        return $query;
    }
}