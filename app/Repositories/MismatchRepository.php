<?php

namespace App\Repositories;

use App\Models\Mismatch;
use App\Libs\{
    ValueUtil
};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MismatchRepository
{
    public function save(array $input, $id = null)
    {
        $data = [
            'office_type' => $input['office_type'],
            'case_id' => $input['case_id'],
            'mismatch_type' => $input['mismatch_type'],
            'mismatch_qty' => $input['mismatch_qty'],
            'del_flg' => $input['del_flg'] ?? 0,
            'del_flg' => $input['del_flg'] ?? 0,
            'deleted_at' => $input['deleted_at'] ?? null,
        ];

        if (!$id) {
            $data['created_by'] = auth()->user()->id;
            $data['created_at'] = now();
        }

        if ($id) {
            $data['updated_by'] = auth()->user()->id;
            $data['updated_at'] = now();
        }

        return Mismatch::updateOrCreate(['id' => $id], $data);
    }

    public function getByCaseId($caseId)
    {
        return Mismatch::where('case_id', $caseId)->get();
    }

    public function deleteByCaseId($caseId)
    {
        Mismatch::where('case_id', $caseId)->delete();
    }

    /**
     * Get mismatch type
     * @param array $type
     * @return array
     */
    public function getMismatchType($type = []) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = Mismatch::where('mismatch.del_flg', '<>', $flgDeleted);
        if (empty($type)) {
            // get default by mismatch_type = 1,4,5
            $query->whereIn('mismatch.mismatch_type', [
                ValueUtil::constToValue('Mismatch.misMatchType.SHORT_CIRCUIT_DEFECTIVE_QTY'),
                ValueUtil::constToValue('Mismatch.misMatchType.M_TYPE_UNLOCKED_QTY'),
                ValueUtil::constToValue('Mismatch.misMatchType.M_TYPE_UNSTORED_QTY'),
            ]);
        } else {
            $query->whereIn('mismatch.mismatch_type', $type);
        }
        $query = $query->get();
        return $query;
    }

    /**
     * Update data
     * 
     * @param int $id
     * @param array $params
     * @return object|mixed|boolean
     */
    public function updateMismatch($id, $params) {
        try {
            $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
            $now = Carbon::now();
            $query = Mismatch::where([
                ['del_flg', '<>', $flgDeleted],
                ['id', $id]
            ]);
            $params['updated_at'] = $now;
            $params['updated_by'] = auth()->user()->id;
            DB::beginTransaction();
            $result = $query->update($params);
            if ($result) {
                DB::commit();
            } else {
                DB::rollBack();
            }
            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }
}
