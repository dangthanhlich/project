<?php

namespace App\Repositories;

use App\Libs\ValueUtil;
use App\Models\MstOffice;
use Illuminate\Support\Facades\{Gate, DB, Log};

class MstOfficeRepository {

    /**
     * get office_name from office_code
     * @param $id
     * @return false|void
     */
    public function getOfficeName($officeCode) {
        try {
            $mstOffice = MstOffice::select('office_name')->where([
                ['office_code', $officeCode],
                ['del_flg', ValueUtil::constToValue('Common.delFlg.NOT_DELETE')]
            ])->get();

            return $mstOffice;
        } catch (\Exception $error) {
            return false;
        }
    }

    /**
     * Get list office location by role user
     */
    public function getListOfficeLocation() {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $userOfficeCode = auth()->user()->office_code;
        $query = MstOffice::where('mst_office.del_flg', '<>', $flgDeleted)
            ->orderBy('mst_office.office_code', 'ASC');
        if (Gate::check(ValueUtil::get('MstUser.permission')['NW'])) {
            // user_type = 3 & tr_office_flg = 1
            $query
                ->join('mst_scrapper', function($join) use($flgDeleted) {
                    $join
                        ->on('mst_scrapper.sy_office_code', '=', 'mst_office.office_code')
                        ->where('mst_scrapper.del_flg', '<>', $flgDeleted);
                })
                ->join('mst_user', function($join) use($flgDeleted, $userOfficeCode) {
                    $join
                        ->on('mst_user.office_code', '=', 'mst_scrapper.tr_office_code')
                        ->where([
                            ['mst_user.del_flg', '<>', $flgDeleted],
                            ['mst_user.office_code', $userOfficeCode],
                        ]);
                });
        } else if (Gate::check(ValueUtil::get('MstUser.permission')['RP'])) {
            // user_type = 3 & rp_office_flg = 1
            $query
                ->join('mst_user', function($join) use($flgDeleted, $userOfficeCode) {
                    $join
                        ->on('mst_user.office_code', '=', 'mst_office.rp_office_code')
                        ->where([
                            ['mst_user.del_flg', '<>', $flgDeleted],
                            ['mst_user.office_code', $userOfficeCode],
                        ]);
                });
        } else if (
            Gate::check(ValueUtil::get('MstUser.permission')['JA1']) ||
            Gate::check(ValueUtil::get('MstUser.permission')['JA2']) ||
            Gate::check(ValueUtil::get('MstUser.permission')['admin'])
        ) {
            // user_type = 2 or user_type = 1
            $query
                ->where('sy_office_flg', ValueUtil::constToValue('MstUser.syOfficeFlg.WITH_AUTHORITY'));
        } else {
            $query = null;
        }
        if (!empty($query)) {
            $query = $query
                ->select([
                    DB::raw("CONCAT(mst_office.office_code, ' - ', mst_office.office_name) as office_code_name"),
                    'mst_office.office_code as office_code',
                ])
                ->pluck('office_code_name', 'mst_office.office_code')
                ->toArray();
        }
        return $query;
    }

    /**
     * Search mst_office
     * @param array $params
     * @param string|null $officeCode
     */
    public function search($params = [], $officeCode = null) {
        $deletedFlg = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = DB::table('mst_office')->select(
                'mst_office.id', 'mst_office.office_code', 'mst_office.office_name',
                'mst_office.office_tel', 'mst_office.pic_name', 'mst_office.tr_office_flg',
                'mst_office.sy_office_flg', 'mst_office.2nd_tr_office_flg', 'mst_office.rp_office_flg'
            )
            ->where('mst_office.del_flg', '<>', $deletedFlg);

        if (isset($params['office_code']) && strlen($params['office_code']) > 0) {
            $query->where('mst_office.office_code', 'LIKE', "%{$params['office_code']}%");
        }

        if (isset($params['office_name']) && strlen($params['office_name']) > 0) {
            $query->where('mst_office.office_name', 'LIKE', "%{$params['office_name']}%");
        }

        if (isset($params['search_office_code']) && $params['search_office_code']) {
            if (empty($params['office_flg'])) {
                $params['office_flg'] = [];
            }
            $officeFlgConstList = ValueUtil::getConstList('MstOffice.officeFlgScreen');
            $officeFlgOn = ValueUtil::constToValue('MstOffice.officeFlg.WITH_AUTHORITY');
            // default office_flg conditions
            if (Gate::check('is_NW')) {
                // NW sy_office_flg
                $query->leftJoin(
                    'mst_scrapper as NW_scrapper_sy',
                    'mst_office.office_code',
                    '=',
                    'NW_scrapper_sy.sy_office_code'
                );
                // NW 2nd_tr_office_flg
                $query->leftJoin(
                    'mst_office as NW_office_2nd_tr',
                    'mst_office.2nd_tr_office_code',
                    '=',
                    'NW_office_2nd_tr.office_code'
                );
                $query->leftJoin(
                    'mst_scrapper as NW_scrapper_2nd_tr',
                    'NW_office_2nd_tr.office_code',
                    '=',
                    'NW_scrapper_2nd_tr.sy_office_code'
                );
                // NW rp_office_flg
                $query->leftJoin(
                    'mst_office as NW_office_rp',
                    'mst_office.rp_office_code',
                    '=',
                    'NW_office_rp.office_code'
                );
                $query->leftJoin(
                    'mst_scrapper as NW_scrapper_rp',
                    'NW_office_rp.office_code',
                    '=',
                    'NW_scrapper_rp.sy_office_code'
                );
                // add where conditions based on mst_office.*_office_flg
                $query->selectRaw("
                    (CASE
                        WHEN (
                            mst_office.tr_office_flg = {$officeFlgOn} AND
                            mst_office.office_code = {$officeCode}
                        )
                        THEN 1
                        ELSE 0
                    END) AS is_tr_office_flg
                ");
                $query->selectRaw("
                    (CASE
                        WHEN (
                            mst_office.sy_office_flg = {$officeFlgOn} AND
                            NW_scrapper_sy.tr_office_code = {$officeCode} AND
                            NW_scrapper_sy.del_flg <> {$deletedFlg}
                        )
                        THEN 1
                        ELSE 0
                    END) AS is_sy_office_flg
                ");
                $query->selectRaw("
                    (CASE
                        WHEN (
                            mst_office.2nd_tr_office_flg = {$officeFlgOn} AND
                            NW_scrapper_2nd_tr.tr_office_code = {$officeCode} AND
                            NW_office_2nd_tr.del_flg <> {$deletedFlg} AND
                            NW_scrapper_2nd_tr.del_flg <> {$deletedFlg}
                        )
                        THEN 1
                        ELSE 0
                    END) AS is_2nd_tr_office_flg
                ");
                $query->selectRaw("
                    (CASE
                        WHEN (
                            mst_office.rp_office_flg = {$officeFlgOn} AND
                            NW_scrapper_rp.tr_office_code = {$officeCode} AND
                            NW_office_rp.del_flg <> {$deletedFlg} AND
                            NW_scrapper_rp.del_flg <> {$deletedFlg}
                        )
                        THEN 1
                        ELSE 0
                    END) AS is_rp_office_flg
                ");
                $query->orHavingRaw("is_tr_office_flg = {$officeFlgOn}");
                $query->orHavingRaw("is_sy_office_flg = {$officeFlgOn}");
                $query->orHavingRaw("is_2nd_tr_office_flg = {$officeFlgOn}");
                $query->orHavingRaw("is_rp_office_flg = {$officeFlgOn}");
            } else if (Gate::check('is_SY')) {
                // SY tr_office_flg
                $query->leftJoin(
                    'mst_scrapper as SY_scrapper_tr',
                    'mst_office.office_code',
                    '=',
                    'SY_scrapper_tr.tr_office_code'
                );
                // SY 2nd_tr_office_flg
                $query->leftJoin(
                    'mst_office as SY_office_2nd_tr',
                    'mst_office.2nd_tr_office_code',
                    '=',
                    'SY_office_2nd_tr.office_code'
                );
                // SY rp_office_flg
                $query->leftJoin(
                    'mst_office as SY_office_rp',
                    'mst_office.rp_office_code',
                    '=',
                    'SY_office_rp.office_code'
                );
                // add where conditions based on mst_office.*_office_flg
                $query->selectRaw("
                    (CASE
                        WHEN (
                            mst_office.tr_office_flg = {$officeFlgOn} AND
                            SY_scrapper_tr.sy_office_code = {$officeCode} AND
                            SY_scrapper_tr.del_flg <> {$deletedFlg}
                        )
                        THEN 1
                        ELSE 0
                    END) AS is_tr_office_flg
                ");
                $query->selectRaw("
                    (CASE
                        WHEN (
                            mst_office.sy_office_flg = {$officeFlgOn} AND
                            mst_office.office_code = {$officeCode}
                        )
                        THEN 1
                        ELSE 0
                    END) AS is_sy_office_flg
                ");
                $query->selectRaw("
                    (CASE
                        WHEN (
                            mst_office.2nd_tr_office_flg = {$officeFlgOn} AND
                            SY_office_2nd_tr.office_code = {$officeCode} AND
                            SY_office_2nd_tr.del_flg <> {$deletedFlg}
                        )
                        THEN 1
                        ELSE 0
                    END) AS is_2nd_tr_office_flg
                ");
                $query->selectRaw("
                    (CASE
                        WHEN (
                            mst_office.rp_office_flg = {$officeFlgOn} AND
                            SY_office_rp.office_code = {$officeCode} AND
                            SY_office_rp.del_flg <> {$deletedFlg}
                        )
                        THEN 1
                        ELSE 0
                    END) AS is_rp_office_flg
                ");
                $query->orHavingRaw("is_tr_office_flg = {$officeFlgOn}");
                $query->orHavingRaw("is_sy_office_flg = {$officeFlgOn}");
                $query->orHavingRaw("is_2nd_tr_office_flg = {$officeFlgOn}");
                $query->orHavingRaw("is_rp_office_flg = {$officeFlgOn}");
            } else if (Gate::check('is_RP')) {
                // RP tr_office_flg
                $query->leftJoin(
                    'mst_scrapper as RP_scrapper_tr',
                    'mst_office.office_code',
                    '=',
                    'RP_scrapper_tr.tr_office_code'
                );
                $query->leftJoin(
                    'mst_office as RP_office_sy',
                    'RP_scrapper_tr.sy_office_code',
                    '=',
                    'RP_office_sy.office_code'
                );
                // RP 2nd_tr_office_flg
                $query->leftJoin(
                    'mst_office as RP_office_2nd_tr',
                    'mst_office.2nd_tr_office_code',
                    '=',
                    'RP_office_2nd_tr.office_code'
                );
                // add where conditions based on mst_office.*_office_flg
                $query->selectRaw("
                    (CASE
                        WHEN (
                            mst_office.tr_office_flg = {$officeFlgOn} AND
                            RP_office_sy.rp_office_code = {$officeCode} AND
                            RP_scrapper_tr.del_flg <> {$deletedFlg} AND
                            RP_office_sy.del_flg <> {$deletedFlg}
                        )
                        THEN 1
                        ELSE 0
                    END) AS is_tr_office_flg
                ");
                $query->selectRaw("
                    (CASE
                        WHEN (
                            mst_office.sy_office_flg = {$officeFlgOn} AND
                            mst_office.rp_office_code = {$officeCode}
                        )
                        THEN 1
                        ELSE 0
                    END) AS is_sy_office_flg
                ");
                $query->selectRaw("
                    (CASE
                        WHEN (
                            mst_office.2nd_tr_office_flg = {$officeFlgOn} AND
                            RP_office_2nd_tr.rp_office_code = {$officeCode} AND
                            RP_office_2nd_tr.del_flg <> {$deletedFlg}
                        )
                        THEN 1
                        ELSE 0
                    END) AS is_2nd_tr_office_flg
                ");
                $query->selectRaw("
                    (CASE
                        WHEN (
                            mst_office.rp_office_flg = {$officeFlgOn} AND
                            mst_office.office_code = {$officeCode}
                        )
                        THEN 1
                        ELSE 0
                    END) AS is_rp_office_flg
                ");
                $query->orHavingRaw("is_tr_office_flg = {$officeFlgOn}");
                $query->orHavingRaw("is_sy_office_flg = {$officeFlgOn}");
                $query->orHavingRaw("is_2nd_tr_office_flg = {$officeFlgOn}");
                $query->orHavingRaw("is_rp_office_flg = {$officeFlgOn}");
            } else {
                $query->selectRaw('mst_office.tr_office_flg AS is_tr_office_flg');
                $query->selectRaw('mst_office.sy_office_flg AS is_sy_office_flg');
                $query->selectRaw('mst_office.2nd_tr_office_flg AS is_2nd_tr_office_flg');
                $query->selectRaw('mst_office.rp_office_flg AS is_rp_office_flg');
            }
            // No.3 業者区分 search conditions
            $query->where(function($q) use($query, $params, $officeCode, $officeFlgConstList, $deletedFlg, $officeFlgOn) {
                foreach ($params['office_flg'] as $officeFlgValue) {
                    if (!isset($officeFlgConstList[$officeFlgValue])) {
                        continue;
                    }
                    $officeFlgConst = $officeFlgConstList[$officeFlgValue];
                    $officeFlgConst = strtolower($officeFlgConst). '_flg';
                    if (Gate::check('is_NW')) {
                        switch ($officeFlgConst) {
                            case 'tr_office_flg':
                                $q->orWhere(function($q) use($officeFlgConst, $officeCode, $officeFlgOn) {
                                    $q->Where([
                                        'mst_office.'. $officeFlgConst => $officeFlgOn,
                                        'mst_office.office_code' => $officeCode,
                                    ]);
                                });
                                break;
                            case 'sy_office_flg':
                                $q->orWhere(function($q) use($officeFlgConst, $officeCode, $deletedFlg, $officeFlgOn) {
                                    $q->Where([
                                        'mst_office.'. $officeFlgConst => $officeFlgOn,
                                        'NW_scrapper_sy.tr_office_code' => $officeCode,
                                    ]);
                                    $q->Where('NW_scrapper_sy.del_flg', '<>', $deletedFlg);
                                });
                                break;
                            case '2nd_tr_office_flg':
                                $q->orWhere(function($q) use($officeFlgConst, $officeCode, $deletedFlg, $officeFlgOn) {
                                    $q->Where([
                                        'mst_office.'. $officeFlgConst => $officeFlgOn,
                                        'NW_scrapper_2nd_tr.tr_office_code' => $officeCode,
                                        
                                    ]);
                                    $q->Where('NW_office_2nd_tr.del_flg', '<>', $deletedFlg);
                                    $q->Where('NW_scrapper_2nd_tr.del_flg', '<>', $deletedFlg);
                                });
                                break;
                            case 'rp_office_flg':
                                $q->orWhere(function($q) use($officeFlgConst, $officeCode, $deletedFlg, $officeFlgOn) {
                                    $q->Where([
                                        'mst_office.'. $officeFlgConst => $officeFlgOn,
                                        'NW_scrapper_rp.tr_office_code' => $officeCode,
                                    ]);
                                    $q->Where('NW_office_rp.del_flg', '<>', $deletedFlg);
                                    $q->Where('NW_scrapper_rp.del_flg', '<>', $deletedFlg);
                                });
                                break;
                        }
                    } else if (Gate::check('is_SY')) {
                        switch ($officeFlgConst) {
                            case 'tr_office_flg':
                                $q->orWhere(function($q) use($officeFlgConst, $officeCode, $deletedFlg, $officeFlgOn) {
                                    $q->Where([
                                        'mst_office.'. $officeFlgConst => $officeFlgOn,
                                        'SY_scrapper_tr.sy_office_code' => $officeCode,
                                    ]);
                                    $q->Where('SY_scrapper_tr.del_flg', '<>', $deletedFlg);
                                });
                                break;
                            case 'sy_office_flg':
                                $q->orWhere(function($q) use($officeFlgConst, $officeCode, $officeFlgOn) {
                                    $q->Where([
                                        'mst_office.'. $officeFlgConst => $officeFlgOn,
                                        'mst_office.office_code' => $officeCode,
                                    ]);
                                });
                                break;
                            case '2nd_tr_office_flg':
                                $q->orWhere(function($q) use($officeFlgConst, $officeCode, $deletedFlg, $officeFlgOn) {
                                    $q->Where([
                                        'mst_office.'. $officeFlgConst => $officeFlgOn,
                                        'SY_office_2nd_tr.office_code' => $officeCode,
                                    ]);
                                    $q->Where('SY_office_2nd_tr.del_flg', '<>', $deletedFlg);
                                });
                                break;
                            case 'rp_office_flg':
                                $q->orWhere(function($q) use($officeFlgConst, $officeCode, $deletedFlg, $officeFlgOn) {
                                    $q->Where([
                                        'mst_office.'. $officeFlgConst => $officeFlgOn,
                                        'SY_office_rp.office_code' => $officeCode,
                                    ]);
                                    $q->Where('SY_office_rp.del_flg', '<>', $deletedFlg);
                                });
                                break;
                        }
                    } else if (Gate::check('is_RP')) {
                        switch ($officeFlgConst) {
                            case 'tr_office_flg':
                                $q->orWhere(function($q) use($officeFlgConst, $officeCode, $deletedFlg, $officeFlgOn) {
                                    $q->Where([
                                        'mst_office.'. $officeFlgConst => $officeFlgOn,
                                        'RP_office_sy.rp_office_code' => $officeCode,
                                    ]);
                                    $q->Where('RP_scrapper_tr.del_flg', '<>', $deletedFlg);
                                    $q->Where('RP_office_sy.del_flg', '<>', $deletedFlg);
                                });
                                break;
                            case 'sy_office_flg':
                                $q->orWhere(function($q) use($officeFlgConst, $officeCode, $officeFlgOn) {
                                    $q->Where([
                                        'mst_office.'. $officeFlgConst => $officeFlgOn,
                                        'mst_office.rp_office_code' => $officeCode,
                                    ]);
                                });
                                break;
                            case '2nd_tr_office_flg':
                                $q->orWhere(function($q) use($officeFlgConst, $officeCode, $deletedFlg, $officeFlgOn) {
                                    $q->Where([
                                        'mst_office.'. $officeFlgConst => $officeFlgOn,
                                        'RP_office_2nd_tr.rp_office_code' => $officeCode,
                                    ]);
                                    $q->Where('RP_office_2nd_tr.del_flg', '<>', $deletedFlg);
                                });
                                break;
                            case 'rp_office_flg':
                                $q->orWhere(function($q) use($officeFlgConst, $officeCode, $officeFlgOn) {
                                    $q->Where([
                                        'mst_office.'. $officeFlgConst => $officeFlgOn,
                                        'mst_office.office_code' => $officeCode,
                                    ]);
                                });
                                break;
                        }
                    } else {
                        $q->orWhere(['mst_office.'. $officeFlgConst => $officeFlgOn]);
                    }
                }
            });
        }
        $query
            ->orderBy('mst_office.id', 'ASC');
        if (Gate::check('is_NW') ||
            Gate::check('is_SY') ||
            Gate::check('is_RP')
        ) {
            $groupQuery = DB::table(DB::raw("({$query->toSql()}) as sub"))
                ->select('sub.id')
                ->selectRaw('MAX(sub.is_tr_office_flg + sub.is_sy_office_flg + sub.is_2nd_tr_office_flg + sub.is_rp_office_flg) as maxSumFlg')
                ->groupBy('sub.id');
            $groupQuery->mergeBindings($query);
            $joinQuery = DB::table(DB::raw("({$query->toSql()}) as `mo`"))
                ->join(DB::raw("({$groupQuery->toSql()}) as `group`"), function($join) {
                    $join->on('mo.id', '=', 'group.id');
                    $join->on(DB::raw('(`mo`.`is_tr_office_flg` + `mo`.`is_sy_office_flg` + `mo`.`is_2nd_tr_office_flg` + `mo`.`is_rp_office_flg`)'), '=', DB::raw('`group`.`maxSumFlg`'));
                })
                ->distinct('mo.id');
            $joinQuery->mergeBindings($query);
            $joinQuery->mergeBindings($groupQuery);
            return $joinQuery;
        }
        return $query;
    }

    /**
     * Get list office by 2nd_tr_office_flg = 1
     * @return array
     */
    public function getListOfficeBy2ndTrOfficeFlg() {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = MstOffice::select([
                DB::raw("CONCAT(mst_office.office_code, ' - ', mst_office.office_name) as office_code_name"),
                'mst_office.office_code as office_code',
            ])
            ->where([
                ['mst_office.del_flg', '<>', $flgDeleted],
                ['mst_office.2nd_tr_office_flg', ValueUtil::constToValue('MstUser.2ndTrOfficeFlg.WITH_AUTHORITY')]
            ])
            ->orderBy('mst_office.office_code', 'ASC')
            ->pluck('office_code_name', 'mst_office.office_code')
            ->toArray();
        return $query;
    }

    /**
     * Get mst_office by id
     * 
     * @param int|string $id
     */
    public function getById($id) {
        $mstOffice = MstOffice::query();
        $mstOffice
            ->where('mst_office.id', '=', $id)
            ->where('mst_office.del_flg', '<>', ValueUtil::constToValue('Common.delFlg.DELETED'));
        return $mstOffice->first();
    }

    /**
     * Get mst_office by office_code
     * 
     * @param int|string $officeCode
     */
    public function getByOfficeCode($officeCode) {
        $mstOffice = MstOffice::query();
        $mstOffice
            ->where('mst_office.office_code', '=', $officeCode)
            ->where('mst_office.del_flg', '<>', ValueUtil::constToValue('Common.delFlg.DELETED'));
        return $mstOffice->first();
    }

    /**
     * Create new mst_office
     *
     * @param $data
     * @return bool
     */
    public function create($data) {
        try {
            $officeFlgConstList = ValueUtil::getConstList('MstOffice.officeFlgScreen');
            foreach ($officeFlgConstList as $officeFlgConst) {
                $vofficeFlgValue = ValueUtil::constToValue('MstOffice.officeFlgScreen.'.$officeFlgConst);
                $officeFlgDb = strtolower($officeFlgConst). '_flg';
                if (isset($data['office_flg']) && in_array($vofficeFlgValue, $data['office_flg'])) {
                    $data[$officeFlgDb] = ValueUtil::constToValue('MstOffice.officeFlg.WITH_AUTHORITY');
                } else {
                    $data[$officeFlgDb] = ValueUtil::constToValue('MstOffice.officeFlg.NO_AUTHORITY');
                }
            }
            $data['record_type'] = '新規';
            $entity = new MstOffice($data);
            if ($entity->save()) {
                return true;
            }
            return false;
        } catch (\Exception $error) {
            Log::error($error);
            return false;
        }
    }

    /**
     * Update mst_office
     * 
     * @param string|int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        try {
            $officeFlgConstList = ValueUtil::getConstList('MstOffice.officeFlgScreen');
            foreach ($officeFlgConstList as $officeFlgConst) {
                $vofficeFlgValue = ValueUtil::constToValue('MstOffice.officeFlgScreen.'.$officeFlgConst);
                $officeFlgDb = strtolower($officeFlgConst). '_flg';
                if (isset($data['office_flg']) && in_array($vofficeFlgValue, $data['office_flg'])) {
                    $data[$officeFlgDb] = ValueUtil::constToValue('MstOffice.officeFlg.WITH_AUTHORITY');
                } else {
                    $data[$officeFlgDb] = ValueUtil::constToValue('MstOffice.officeFlg.NO_AUTHORITY');
                }
            }
            $data['record_type'] = '変更';
            $entity = MstOffice::find($id);
            $entity->fill($data);
            if ($entity->save()) {
                return true;
            }
            return false;
        } catch (\Exception $error) {
            Log::error($error);
            return false;
        }
    }

    /**
     * Get mst_office by management_no
     * @param string $managementNo
     * @return object
     */
    public function getOfficeByManagementNo($managementNo) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = MstOffice::select([
            DB::raw("CONCAT(mst_office.office_code, ' - ', mst_office.office_name) as office_code_name"),
            'mst_office.office_code',
        ])
            ->join('case', function($join) use($flgDeleted) {
                $join
                    ->on('case.tr_office_code', '=', 'mst_office.office_code')
                    ->where('case.del_flg', '<>', $flgDeleted);
            })
            ->join('contract', function($join) use($flgDeleted, $managementNo) {
                $join
                    ->on('contract.case_id', '=', 'case.case_id')
                    ->where([
                        ['contract.del_flg', '<>', $flgDeleted],
                        ['contract.management_no', $managementNo],
                    ]);
            })
            ->where('mst_office.del_flg', '<>', $flgDeleted)
            ->first();
        return $query;
    }

    /**
     * Get mst_office by sy_office_code
     * @param string $syOfficeCode
     * @return object
     */
    public function getOfficeBySyOfficeCode($syOfficeCode) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = MstOffice::select([
                'office_address_search',
                'office_name',
                'office_code',
            ])
            ->where([
                ['del_flg', '<>', $flgDeleted],
                ['office_code', $syOfficeCode],
            ]);
        return $query->first();
    }

    /**
     * Get list mst_office associated mst_scrapper
     * 
     * @param string $syOfficeCode
     */
    public function getListOfficeAssociatedScrapper($syOfficeCode) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = MstOffice::select([
                DB::raw("CONCAT(COALESCE(mst_office.office_code,''), ' - ', COALESCE(mst_office.office_name,'')) as office_code_name"),
                DB::raw("CONCAT(COALESCE(mst_office.office_code,''), '-', COALESCE(mst_office.company_code,'')) as office_company_code"),
            ])
            ->join('mst_scrapper', function($join) use($syOfficeCode, $flgDeleted) {
                $join
                    ->on('mst_scrapper.tr_office_code', '=', 'mst_office.office_code')
                    ->where([
                        ['mst_scrapper.sy_office_code', $syOfficeCode],
                        ['mst_scrapper.del_flg', '<>', $flgDeleted],
                    ]);
            })
            ->where('mst_office.del_flg', '<>', $flgDeleted)
            ->orderBy('mst_office.office_code', 'ASC')
            ->pluck('office_code_name', 'office_company_code');
        return $query;
    }

    /**
     * Get mst_office by sy_office_code
     * @param string $syOfficeCode
     * @return object
     */
    public function getOfficeForMst040() {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $flgAuthorityOffice = ValueUtil::constToValue('MstOffice.officeFlg.WITH_AUTHORITY');
        $query = MstOffice::select([
                DB::raw("CONCAT(office_code, ' - ', office_name) as office_code_name"),
                'office_code',
            ])
            ->where([
                ['del_flg', '<>', $flgDeleted],
                ['sy_office_flg', $flgAuthorityOffice],
            ])
            ->orderBy('office_code', 'ASC');
        return $query->get();
    }

    /**
     * Get mst_office by office_code
     * @param string $officeCode
     * @return object
     */
    public function getOfficeForPal080($officeCode)
    {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $flgAuthorityOffice = ValueUtil::constToValue('MstOffice.officeFlg.WITH_AUTHORITY');
        $query = MstOffice::select([
            DB::raw("CONCAT(office_code, ' - ', office_name) as office_code_name"),
            'office_code', 'office_name'
        ])
            ->where([
                ['rp_office_code', $officeCode],
                ['del_flg', '<>', $flgDeleted],
                ['sy_office_flg', $flgAuthorityOffice],
            ])->orderBy('office_code', 'ASC');
        return $query->get();
    }

    /**
     * Get list rp mst_office
     * 
     * @return array
     */
    public function getLisRpOffice() {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = MstOffice::select([
                DB::raw("CONCAT(COALESCE(mst_office.office_code,''), ' - ', COALESCE(mst_office.office_name,'')) as office_code_name"),
                'mst_office.office_code',
            ])
            ->where([
                ['mst_office.del_flg', '<>', $flgDeleted],
                ['mst_office.rp_office_flg', ValueUtil::constToValue('MstOffice.officeFlg.WITH_AUTHORITY')],
            ])
            ->orderBy('mst_office.office_code', 'ASC')
            ->pluck('office_code_name', 'mst_office.office_code');
        return $query;
    }

    /**
     * Get office by user.office_code
     * @return object
     */
    public function getOfficeByUserOfficeCode() {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $userOfficeCode = auth()->user()->office_code;
        $query = MstOffice::where([
            ['mst_office.del_flg', '<>', $flgDeleted],
            ['mst_office.office_code', $userOfficeCode],
        ])
            ->first();
        return $query;
    }

}
