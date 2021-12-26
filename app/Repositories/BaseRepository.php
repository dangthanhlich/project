<?php

namespace App\Repositories;

use App\Libs\ValueUtil;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class BaseRepository {
    private $model;

    public function __construct(string $model) {
        $this->model = DB::table($model);
    }

    /**
     * Find by id
     * @param $id
     * @param $fieldId
     * @return query
     */
    public function findById($id, $fieldId = 'id') {
        try {
            $unDeleted = ValueUtil::constToValue('Common.delFlg.NOT_DELETE');
            $query = $this->model->where([
                ['del_flg', $unDeleted],
                [$fieldId, $id],
            ]);
            return $query;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Update data
     * 
     * @param int $id
     * @param array $params: params need match all fields of model
     * @param string $fieldId 
     * @return object|mixed|boolean
     */
    public function update($id, $params, $fieldId = 'id') {
        try {
            $now = Carbon::now();
            $query = $this->findById($id, $fieldId);
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

    /**
     * Create data
     * 
     * @param array $params: params need match all fields of model
     * @return object|mixed|boolean
     */
    public function create($params) {
        try {
            $now = Carbon::now();
            $params['created_at'] = $now;
            $params['created_by'] = auth()->user()->id;
            $params['updated_at'] = $now;
            $params['updated_by'] = auth()->user()->id;
            DB::beginTransaction();
            $result = $this->model->insert($params);
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
