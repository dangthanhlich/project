<?php

namespace App\Repositories;

use App\Libs\ValueUtil;
use App\Models\Pallet;
use Closure;
use Illuminate\Support\Facades\{DB, Log};

class PalletRepository {

	public function queryPal02($officeCode, $params) {
		$flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
		$query = Pallet::select([
						'pallet.pallet_no',
						'pallet.pallet_status',
						'pallet_transport.car_no',
						DB::raw("DATE_FORMAT(pallet_transport.deliver_complete_time, '%Y/%m/%d') as deliver_complete_time")
					])
					->leftJoin('pallet_transport', function($join) use($flgDeleted) {
		                $join
		                    ->on('pallet.pallet_transport_id', '=', 'pallet_transport.pallet_transport_id')
		                    ->where('pallet_transport.del_flg', '<>', $flgDeleted);
		            })
					->where('pallet.sy_office_code', $officeCode)
					->where('pallet.del_flg', '<>', $flgDeleted);

		if (!empty($params['pallet_status']) && is_array($params['pallet_status'])) {
			$query->whereIn('pallet.pallet_status', $params['pallet_status']);
		}

		if (!empty($params['pallet_no'])) {
            $query->where('pallet.pallet_no', 'like', "%{$params['pallet_no']}%");
		}

		if (!empty($params['case_no'])) {
			$query->join('pallet_case', 'pallet.pallet_id', '=', 'pallet_case.pallet_id')
				->join('case', 'pallet_case.case_id', '=', 'case.case_id')
				->where('case.case_no', 'like', "%{$params['case_no']}%")
				->where('pallet_case.del_flg', '<>', $flgDeleted)
				->where('case.del_flg', '<>', $flgDeleted)
                ->groupBy('pallet.pallet_id');
		}

		$query->orderBy('pallet_transport.deliver_complete_time', 'ASC')
			->orderBy('pallet.pallet_id', 'ASC');

		return $query;
	}

	public function findOneBy(array $criteria, Closure $builder = null)
    {
        $query = Pallet::where($criteria);
        if (is_callable($builder)) {
            $builder($query);
        }
        return $query->first();
    }

    public function queryPal021($palletNo) {
    	$flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
    	return $this->findOneBy([
	    		['pallet_no', $palletNo],
	    		['sy_office_code', auth()->user()->office_code],
	    		['del_flg', '<>', $flgDeleted]
	    	],
	    	function ($query) use ($flgDeleted){
	    		$query->select([
	    			'pallet_id',
	    			'pallet_no',
	    			'pallet_status',
	    			'pallet_transport_id'
	    		])
	    		->with([
	    			'palletTransport' => function($q) use ($flgDeleted) {
	    				$q->select([
    						'pallet_transport_id',
    						'car_no',
    						DB::raw("DATE_FORMAT(pallet_transport.deliver_complete_time, '%Y/%m/%d %H:%i') as deliver_complete_time"),
    						'car_no_picture_1'
    					])
    					->where('del_flg', '<>', $flgDeleted);
	    			},
	    			'cases' => function($q) use ($flgDeleted) {
	    				$q->select([
    						'case_no',
    						'actual_qty_sy'
    					])
    					->where([
    						['case.del_flg', '<>', $flgDeleted],
    						['pallet_case.del_flg', '<>', $flgDeleted]
    					])
    					->orderBy('case.case_id', 'ASC');
	    			}
	    		]);

	    	});
    }

    public function queryPal080($params)
	{
		$flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
		$query = Pallet::with(
            ['palletTransport'=> function($q) use($flgDeleted) {
                return $q->where('del_flg', '<>', $flgDeleted);
            }])
            ->with(['palletMstOffice'=> function($q) use($flgDeleted) {
                return $q->where('del_flg', '<>', $flgDeleted);
            }])
            ->where('del_flg', '<>', $flgDeleted);
		if(isset($params['sort']) && $params['sort'] === true){
			$query->orderBy('receive_complete_time', 'ASC');
		}
		if (!empty($params['pallet_status']) && is_array($params['pallet_status'])) {
			$query->whereIn('pallet.pallet_status', $params['pallet_status']);
		}
		if (isset($params['receive_complete_time_from']) && strlen($params['receive_complete_time_from'])) {
			$time_from = date('Y-m-d 00:00', strtotime($params['receive_complete_time_from']));
			$query->where('receive_complete_time', '>=', $time_from);
		}
		if (isset($params['receive_complete_time_to']) && strlen($params['receive_complete_time_to'])) {
			$time_to = date('Y-m-d 23:59:59', strtotime($params['receive_complete_time_to']));
			$query->where('receive_complete_time', '<=', $time_to);
		}
		if (isset($params['pallet_no']) && strlen($params['pallet_no'])) {
			$query->where('pallet_no', 'like', "%{$params['pallet_no']}%");
		}

		if (isset($params['office_name']) && strlen($params['office_name'])) {
			$query->whereHas('palletMstOffice', function ($q) use ($params) {
				$q->where('office_name', 'like', "%{$params['office_name']}%");
			});
		}
		return $query;
	}

	/**
     * get data display to screen PAL-010
	 * 
	 * @param $paramsSearch
     * @return array
     */
    public function getDataPal010WithRelations($paramsSearch = []) 
    {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
		$officeCode = isset($paramsSearch['officeCodeUser']) ? $paramsSearch['officeCodeUser'] : null;
		$palletNo = isset($paramsSearch['pallet_no']) ? $paramsSearch['pallet_no'] : null;
        return Pallet::select([
			'pallet_id',
			'pallet_no',
		])
		->where([
            ['del_flg', '<>', $flgDeleted],
            ['pallet_no', $palletNo]
        ])
        ->with([
            'cases' => function($q) use ($flgDeleted, $officeCode) {
				$q->select([
					'case.case_id as case_id',
					'case.case_no as case_no',
				])
				->where([
					['case.del_flg', '<>', $flgDeleted],
					['case_status', ValueUtil::constToValue('Case.caseStatus.COMPLETION_OF_TAKE_BACK_REPORT')],
					['case.sy_office_code', $officeCode],
					['pallet_case.del_flg', '<>', $flgDeleted]
				]);
			}
		])
        ->first();
    }

    public function queryPal081($id)
    {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $query = Pallet::where('pallet_id', $id)
            ->where('del_flg', '<>', $flgDeleted)
            ->with(['palletTransport' => function ($q) use ($flgDeleted) {
                return $q->where('del_flg', '<>', $flgDeleted);
            }])
            ->with(['cases' => function ($q) use ($flgDeleted) {
                return $q->where([
                    ['case.del_flg', '<>', $flgDeleted],
                    ['pallet_case.del_flg', '<>', $flgDeleted]
                ])->with(['mismatch' => function($q) use ($flgDeleted){
                   return $q->where('del_flg', '<>', $flgDeleted)->where('office_type', 2);
                }]);
            }])
            ->with(['palletMstOffice' => function ($q) use ($flgDeleted) {
                return $q->where([
                    ['del_flg', '<>', $flgDeleted]
                ]);
            }])->first();
        return $query;
    }
}
