<?php

namespace App\Repositories;

use App\Libs\ValueUtil;
use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CarRepository
{

    public function save(array $input, $carId = null)
    {
        return Car::updateOrCreate(['car_id' => $carId], $input);
    }

    public function existsByCarNo($carNo)
    {
        return Car::where('car_no', $carNo)
            ->where('del_flg', ValueUtil::constToValue('Common.delFlg.DELETED'))
            ->exists();
    }

    /**
     * delete car with car_id
     * @param $carIds
     */
    public function deleteCar061($carIds = []) {
        try {
            $deletedFlg = ValueUtil::constToValue('Common.delFlg.DELETED');
            DB::beginTransaction();
            $car = Car::whereIn('car_id', $carIds)
                    ->update([
                        'del_flg' => $deletedFlg,
                        'updated_by' => auth()->user()->id,
                        'updated_at' => Carbon::now(),
                        'deleted_by' => auth()->user()->id,
                        'deleted_at' => Carbon::now(),
                    ]);
            if ($car) {
                DB::commit();
                return true;
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return false;
        }
    }

    public function deleteByCaseId($caseId) {
        try {
            $deletedFlg = ValueUtil::constToValue('Common.delFlg.DELETED');
            DB::beginTransaction();
            $car = Car::where('case_id', $caseId)
                    ->update([
                        'del_flg' => $deletedFlg,
                        'updated_by' => auth()->user()->id,
                        'updated_at' => Carbon::now(),
                        'deleted_by' => auth()->user()->id,
                        'deleted_at' => Carbon::now(),
                    ]);

            if ($car) {
                DB::commit();
                return true;
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * insert new car with params
     * @param $params
     * @param $caseId
     */
    public function insertCar061($params = [], $caseId) {
        try {
            DB::beginTransaction();
            $car = new Car();
            $insertParams = [
                'case_id' => $caseId,
                'car_no' => $params['car_no'],
                'car_no_change_flg' => ValueUtil::constToValue('Car.carNoChangeFlg.WITH_HAND_CORRECTION'),
                'car_no_change_time' => Carbon::now()
            ];
            $car->create($insertParams);
            if ($car) {
                DB::commit();
                return true;
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return false;
        }
    }

    public function update(array $input, $carId)
    {
        Car::where('car_id', $carId)
            ->where('car.del_flg', '!=', ValueUtil::constToValue('Common.delFlg.DELETED'))
            ->update($input);
    }
}
