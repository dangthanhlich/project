<?php

namespace App\Services\Cas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Repositories\{
    MismatchRepository,
    CarRepository,
    CaseRepository
};
use App\Libs\{
    ValueUtil,
    ConfigUtil,
    FileUtil
};
use App\Models\Cases;

class Cas07Service
{
    public function getByNotVerify(Builder $query)
    {
        return $query->doesntHave('diffCollectRequests')
            ->whereNull('case.temp_case_id')
            ->whereNotNull('case.deliver_report_time')
            ->where('case.case_no_change_flg', 0)
            ->get();
    }

    public function getDeliveryReportMismatch(Builder $query)
    {
        return $query->leftJoin('diff_collect_request', 'diff_collect_request.case_id', 'case.case_id')
            ->whereNotNull('case.deliver_report_time')
            ->where(function($query) {
                $query->orWhereNotNull('case.temp_case_id')
                    ->orWhere('case.case_no_change_flg', 1)
                    ->orWhereNotNull('diff_collect_request.case_id');
            })
            ->addSelect('diff_collect_request.diff_resolve_time')
            ->get();
    }

    public function saveMismatch(array $mismatchs, $caseId)
    {
        $mismatchRepository = app(MismatchRepository::class);

        foreach($mismatchs as $mismatch) {
            $mismatchRepository->save([
                'office_type' => ValueUtil::constToValue('Mismatch.officeType.SY'),
                'case_id' => $caseId,
                'mismatch_type' => $mismatch['mismatch_type_number'],
                'mismatch_qty' => $mismatch['mismatch_qty'],
            ], $mismatch['id']);
        }
    }

    public function updateCar($cars)
    {
        $carRepository = app(CarRepository::class);

        foreach($cars as $car) {
            if (!empty($car['need_upload'])) {
                $base64File = preg_replace('#^data:image/\w+;base64,#i', '', $car['picture']);

                if (base64_encode(base64_decode($base64File)) === $base64File){
                    $casePicture2Folder = 'picture-car-' . $car['id'];
                    $carPicture = $this->uploadS3Cas07($car['id'], $casePicture2Folder, $car['picture']);
                }
            }
            
            $data = ['qty' => $car['qty']];

            if (!empty($carPicture)) {
                $data['car_picture'] = $casePicture2Folder. "/" . $carPicture;
            }

            $carRepository->update($data, $car['id']);
        }
    }

    public function handleCas073(array $input, $caseId)
    {
        try {
            DB::beginTransaction();
            $caseRepository = app(CaseRepository::class);
            $carRepository = app(CarRepository::class);
            $case = $caseRepository->findById($caseId);
            $data = [
                'case_status' => Cases::STATUS['checking'],
                'case_no' => $input['case_no'],
                'inspect_stop_flg' => ValueUtil::constToValue('Case.inspectStopFlg.INSPECTION_NOT_POSSIBLE'),
                'case_no_change_time' => now()
            ];

            if ($case->case_no != $input['case_no']) {
                $data['case_no_old'] = $case->case_no;
            }

            $caseRepository->save($data, $caseId);

            foreach ($input['cars'] as $car) {
                $carRepository->save([
                    'case_id' => $caseId,
                    'car_no_change_flg' => ValueUtil::constToValue('Car.carNoChangeFlg.WITH_HAND_CORRECTION'),
                    'car_no' => $car['car_no']
                ], $car['id']);
            }

            DB::commit();

            return true;
        } catch(\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function uploadS3Cas07($carId, $folder, $base64File = null) {
        $fileName = 'car_id_' . $carId. '_'. ValueUtil::textToValue($folder, 'File.fileTypeToS3Folder'). '_'. now()->format('YmdHis'). '.jpg';
        $uploadResult = FileUtil::uploadBase64ToS3($base64File, $fileName, $folder);

        return $uploadResult ? $fileName : null;
    }
}
