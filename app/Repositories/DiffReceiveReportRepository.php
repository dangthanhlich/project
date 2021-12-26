<?php
namespace App\Repositories;

use App\Libs\ValueUtil;
use App\Models\{
    Car,
    DiffReceiveReport,
    ImportCollectRequest,
    MstScrapper,
    Mismatch,
    ImportReceiveCar
};
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\{DB, Log};

class DiffReceiveReportRepository
{
    public function findOneBy($condition, $closure = null)
    {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = DiffReceiveReport::where('diff_receive_report.del_flg', '<>', $flgDeleted)
            ->where($condition);

        if (is_callable($closure)) {
            $closure($query);

        }

        return $query->first();
    }

    public function findByIdForCom022($id) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $diffReceiveReport =  $this->findOneBy(
            ['id' => $id],
            function($query) use ($flgDeleted) {
                $query->join('case',function($join) use ($flgDeleted) {
                    $join->on('case.case_id', '=', 'diff_receive_report.case_id')
                        ->where([
                            ['case.sy_office_code', auth()->user()->office_code],
                            ['case.del_flg', '<>', $flgDeleted]
                        ]);
                })
                ->select([
                    'diff_receive_report.id',
                    'diff_receive_report.case_no_manifest',
                    'diff_receive_report.car_no_manifest',
                    'diff_receive_report.car_qty_manifest',
                    'case.case_id',
                    'case.scrapper_office_code',
                    'case.case_status',
                    'case.case_no',
                    'case.case_no_change_flg',
                    'case.case_picture_1',
                    'case.case_picture_2',
                    'case.case_picture_3',
                    DB::raw("
                        DATE_FORMAT(case.collect_complete_time, '%Y/%m/%d %H:%i') as collect_complete_time,
                        DATE_FORMAT(case.deliver_report_time, '%Y/%m/%d %H:%i') as deliver_report_time,
                        DATE_FORMAT(case.receive_complete_time, '%Y/%m/%d %H:%i') as receive_complete_time
                    ")
                ]);
            }
        );

        if (!$diffReceiveReport) {
            return null;
        }

        $diffReceiveReport->mst_scrapper =
        MstScrapper::select(['id', 'office_code', 'office_name'])
            ->where([
                ['office_code', $diffReceiveReport->scrapper_office_code],
                ['del_flg', '<>', $flgDeleted]
            ])
            ->first();

        $diffReceiveReport->car =
            Car::select(['car_id', 'case_id', 'car_no', 'qty', 'exceed_qty'])
                ->where([
                    ['case_id', $diffReceiveReport->case_id],
                    ['del_flg', '<>', $flgDeleted]
                ])
                ->orderBy('car_no', 'ASC')
                ->get();

        $diffReceiveReport->mismatch =
            Mismatch::select(['id', 'case_id', 'mismatch_qty', 'mismatch_type'])
                ->where([
                    ['case_id', $diffReceiveReport->case_id],
                    ['office_type', ValueUtil::constToValue('Mismatch.officeType.SY')],
                    ['del_flg', '<>', $flgDeleted]
                ])
                ->get();

        return $diffReceiveReport;
    }

    public function handleCom022($id, $params) {
        $diffReceiveReport =  $this->findOneBy(['id' => $id]);

        if (empty($diffReceiveReport)) {
            return false;
        }

        DB::beginTransaction();
        try {
            $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
            $userId = auth()->user()->id;
            $now = Carbon::now();

            if (!empty($params['del_car_ids'])) {
                Car::whereIn('car_id', $params['del_car_ids'])
                    ->update([
                        'del_flg' => ValueUtil::constToValue('Common.delFlg.DELETED'),
                        'updated_by' => $userId,
                        'updated_at' => $now,
                        'deleted_by' => $userId,
                        'deleted_at' => $now,
                    ]);
            }

            $carNoArs = $carQtyArs = [];
            $carDatas = $params['car_nos'] ?? [];
            $carQtys = $params['car_qtys'] ?? [];
            $newCarDatas = $params['new_car_nos'] ?? [];
            $newCarQtys = $params['new_car_qtys'] ?? [];

            // foreach($carDatas as $carId => $carNo) {
            //     $car = Car::where([
            //         ['car_id', $carId],
            //         ['case_id', $diffReceiveReport->case_id],
            //         ['del_flg', '<>', $flgDeleted]
            //     ])->first();

            //     if ($car) {
            //         $car->car_no = $carNo;
            //         $car->qty = $carQtys[$carId] ?? null;
            //         $car->save();
            //     }
            // }

            $diffReceiveReport->case_no_ars = $params['case_no'] ?? '';
            $carNoArs = array_merge($carDatas, $newCarDatas);
            $diffReceiveReport->car_no_ars = implode(',', $carNoArs);
            $diffReceiveReport->car_qty_ars = implode(',', array_merge($carQtys, $newCarQtys));

            if ($diffReceiveReport->diff_type == ValueUtil::constToValue('DiffReceiveReport.diffType.CASE') && $diffReceiveReport->case_no_ars == $diffReceiveReport->case_no_manifest) {
                $diffReceiveReport->diff_resolve_time = $now;
            } elseif ($diffReceiveReport->diff_type == ValueUtil::constToValue('DiffReceiveReport.diffType.CAR_PLATFORM')) {
                $carNoManifest = explode(',', $diffReceiveReport->car_no_manifest);
                if (count($carNoArs) == count($carNoManifest)) {
                    $isMatch = true;
                    foreach($carNoArs as $carNo) {
                        if (!in_array($carNo, $carNoManifest)) {
                            $isMatch = false;
                            break;
                        }
                    }

                    if ($isMatch) {
                        $importReceiveCars = ImportReceiveCar::where([
                            ['diff_receive_report_id', $diffReceiveReport->id],
                            ['del_flg', '<>', $flgDeleted]
                        ])->get();

                        foreach($importReceiveCars as $car) {
                            Car::create([
                                'case_id' => $car->case_id,
                                'car_no' => $car->car_no,
                                'qty' => $car->actual_collect_qty
                            ])->save();
                        }
                    }
                }
            }

            $diffReceiveReport->update();

            DB::commit();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return false;
        }

        return true;
    }
}
