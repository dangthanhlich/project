<?php

namespace App\Repositories;

use App\Libs\ValueUtil;
use App\Models\Contract;
use Carbon\Carbon;

class ContractRepository {
    /**
     * Get contract management
     * @param string $officeCode
     * @param string $managementNo
     * @return object
     */
    public function getContractManagement($officeCode, $managementNo) {
        $flgDeleted = ValueUtil::constToValue('Common.delFlg.DELETED');
        $defaultOfficeNumber = ValueUtil::get('MstScrapper.defaultOfficeNumber');
        $officeCodeCondition = $officeCode . $defaultOfficeNumber;
        $managementNoCondition = $officeCode . $defaultOfficeNumber . $managementNo;
        $query = Contract::where([
            ['contract.del_flg', '<>', $flgDeleted],
            ['contract.management_no', $managementNoCondition],
        ])
            ->join('case', function($join) use($flgDeleted, $officeCodeCondition) {
                $join
                    ->on('case.case_id', '=', 'contract.case_id')
                    ->where([
                        ['case.del_flg', '<>', $flgDeleted],
                        ['case.scrapper_office_code', $officeCodeCondition],
                    ]);
            })
            ->first();
        return $query;
    }

    /**
     * create contract
     *
     * @param array $data
     */
    public function createContractCas060($data = []) {
        try {
            $contract = new Contract();
            $contract->temp_case_id = $data['temp_case_id'];
            $contract->management_no = $data['management_no'];
            $contract->contract_office_name_1 = $data['contract_office_name_1'];
            $contract->contract_office_address_1 = $data['contract_office_address_1'];
            $contract->contract_office_name_3 = $data['contract_office_name_3'];
            $contract->contract_office_address_3 = $data['contract_office_address_3'];
            $contract->contract_type = $data['contract_type'];
            $contract->contract_price = $data['contract_price'];
            $contract->contract_scope = $data['contract_scope'];
            $contract->contract_period = $data['contract_period'];
            $contract->contract_case_no = $data['contract_case_no'];
            return $contract->save();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update many contract by case_id
     *
     * @param array $caseIdArr
     * @param array $data
     * @param string|int|null $userId
     * @return bool
     */
    public function updateManyByCaseId($caseIdArr, $data, $userId = null) {
        try {
            $data['updated_at'] = Carbon::now();
            $data['updated_by'] = $userId;
            $result = Contract::whereIn('case_id', $caseIdArr)
                ->where('del_flg', '<>', ValueUtil::constToValue('Common.delFlg.DELETED'))
                ->update($data);
            return $result;
        } catch (\Exception $error) {
            Log::error($error);
            return false;
        }
    }

    public function findByCaseId($caseId)
    {
        return Contract::where('case_id', $caseId)
            ->where('del_flg', '<>', ValueUtil::constToValue('Common.delFlg.DELETED'))
            ->first();
    }
}
