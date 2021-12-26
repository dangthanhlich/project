<?php

namespace App\Repositories;

use App\Libs\ValueUtil;
use App\Models\TempCar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TempCarRepository {
    /**
     * delete temp_car with temp_car_id
     * @param $tempCarIds
     */
    public function deleteTempCar061($tempCarIds) {
        try {
            $deletedFlg = ValueUtil::constToValue('Common.delFlg.DELETED');
            DB::beginTransaction();
            $tempCar = TempCar::whereIn('temp_car_id', $tempCarIds)
                    ->update([
                        'del_flg' => $deletedFlg,
                        'updated_by' => auth()->user()->id,
                        'updated_at' => Carbon::now(),
                        'deleted_by' => auth()->user()->id,
                        'deleted_at' => Carbon::now(),
                    ]);
            if ($tempCar) {
                DB::commit();
                return true;
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * insert new temp_car with params
     * @param $params
     * @param $tempCaseId
     */
    public function insertTempCar061($params = [], $tempCaseId) {
        try {
            DB::beginTransaction();
            $tempCar = new TempCar();
            $insertParams = [
                'temp_case_id' => $tempCaseId,
                'temp_car_no' => $params['car_no']
            ];
            $tempCar->create($insertParams);
            if ($tempCar) {
                DB::commit();
                return true;    
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return false;
        }
    }
}
