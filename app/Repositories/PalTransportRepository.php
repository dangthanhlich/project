<?php

namespace App\Repositories;

use App\Libs\ValueUtil;
use App\Models\{
	MstOffice,
	PalletTransport,
	Pallet
};
use Closure;
use Illuminate\Support\Facades\{DB, Log};

class PalTransportRepository
{

	public function queryPal050($officeCode, $params)
	{
		$flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
		$query = PalletTransport::where('pallet_transport.del_flg', '<>', $flgDeleted)->where('2nd_tr_office_code', $officeCode)
			->with(['pallets' => function ($q) use ($flgDeleted) {
				return $q->where('del_flg', '<>', $flgDeleted)
					->with(['cases' => function ($q) use ($flgDeleted) {
						return $q->where([
							['case.del_flg', '<>', $flgDeleted],
							['pallet_case.del_flg', '<>', $flgDeleted]
						]);
					}])
					->with(['palletMstOffice' => function ($q) use ($flgDeleted) {
						return $q->where([
							['del_flg', '<>', $flgDeleted]
						]);
					}]);
			}])->orderBy('deliver_complete_time', 'ASC');
		if (isset($params['deliver_complete_time_from']) && strlen($params['deliver_complete_time_from'])) {
			$time_from = date('Y-m-d 00:00', strtotime($params['deliver_complete_time_from']));
			$query->where('deliver_complete_time', '>=', $time_from);
		}
		if (isset($params['deliver_complete_time_to']) && strlen($params['deliver_complete_time_to'])) {
			$time_to = date('Y-m-d 23:59:59', strtotime($params['deliver_complete_time_to']));
			$query->where('deliver_complete_time', '<=', $time_to);
		}
		return $query;
	}

	public function getMstOfficeBySyOfficeCode($id)
	{
		$flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
		return MstOffice::where('office_code', $id)->where('del_flg', '<>', $flgDeleted)->orderBy('id', 'desc')->limit(1)->value('office_name');
	}

	public function getMstOfficeByRpOfficeCode($id)
	{
		$flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
		return MstOffice::where('office_code', $id)->where('del_flg', '<>', $flgDeleted)->orderBy('id', 'desc')->limit(1)->value('office_name');
	}

	public function getMstOfficeIdBySyOfficeCode($id)
	{
		$flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
		return MstOffice::where('office_code', $id)->where('del_flg', '<>', $flgDeleted)->orderBy('id', 'desc')->limit(1)->value('id');
	}

	public function queryPal051($id)
	{
		$flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
		return PalletTransport::where([['pallet_transport_id', $id], ['del_flg', '<>', $flgDeleted]])->with('pallets', function ($q) use ($flgDeleted) {
			$q->where('del_flg', '<>', $flgDeleted)->orderBy('pallet_id', 'desc');
		})->first();
	}
}
