<?php

namespace App\Repositories;

use App\Libs\{
    EncryptUtil,
    ValueUtil,
};
use App\Models\MstUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class MstUserRepository {

    private $userType2;
    private $userType3;
    private $trOffice;
    private $syOffice;
    private $secondTrOffice;
    private $rpOffice;

    public function __construct() {
        $this->userType2 = ValueUtil::constToValue('MstUser.userType.SELF_RECONCILIATION');
        $this->userType3 = ValueUtil::constToValue('MstUser.userType.OFFICE');
        $this->trOffice = ValueUtil::constToValue('MstUser.traderAuthority.TR');
        $this->syOffice = ValueUtil::constToValue('MstUser.traderAuthority.SY');
        $this->secondTrOffice = ValueUtil::constToValue('MstUser.traderAuthority.2TR');
        $this->rpOffice = ValueUtil::constToValue('MstUser.traderAuthority.RP');
    }

    /**
     * create new mst_user
     *
     * @param $data
     * @return bool
     */
    public function create($data) {
        $officeAdminFlg = $jarpType = $officeCode = $trOfficeFlg = $syOfficeFlg = $secondTrOfficeFlg = $rpOfficeFlg = NULL;
        if ($data['user_type'] == $this->userType2) {
            $jarpType = $data['jarp_type'];
        }
        if ($data['user_type'] == $this->userType3) {
            $officeAdminFlg = empty($data['office_manager']) ? 0 : ValueUtil::constToValue('Common.authority.ON');
            $officeCode = $data['office_code'];
            if (!empty($data['trader_authority'])) {
                $trOfficeFlg = in_array($this->trOffice, $data['trader_authority']) ? ValueUtil::constToValue('Common.authority.ON') : 0;
                $syOfficeFlg = in_array($this->syOffice, $data['trader_authority']) ? ValueUtil::constToValue('Common.authority.ON') : 0;
                $secondTrOfficeFlg = in_array($this->secondTrOffice, $data['trader_authority']) ? ValueUtil::constToValue('Common.authority.ON') : 0;
                $rpOfficeFlg = in_array($this->rpOffice, $data['trader_authority']) ? ValueUtil::constToValue('Common.authority.ON') : 0;
            } else {
                $trOfficeFlg = 0;
                $syOfficeFlg = 0;
                $secondTrOfficeFlg = 0;
                $rpOfficeFlg = 0;
            }
        }
        try {
            $query = new MstUser();
            $query->login_id = EncryptUtil::encryptAes256($data['id-login']);
            $query->user_name = EncryptUtil::encryptAes256($data['id-user']);
            $query->password = EncryptUtil::encryptSha256($data['pass']);
            $query->user_type = $data['user_type'];
            $query->jarp_type = $jarpType;
            $query->office_code = $officeCode;
            $query->tr_office_flg = $trOfficeFlg;
            $query->sy_office_flg = $syOfficeFlg;
            $query['2nd_tr_office_flg'] = $secondTrOfficeFlg;
            $query->rp_office_flg = $rpOfficeFlg;
            $query->office_admin_flg = $officeAdminFlg;
            $query->email = EncryptUtil::encryptAes256($data['email']);
            $query->last_login = NULL;
            $query->invalid_flg = ValueUtil::constToValue('MstUser.invalidFlg.VALID');
            $query->access_token = NULL;
            $query->access_token_expire = NULL;
            $query->refresh_token = NULL;
            $query->refresh_token_expire = NULL;
            $query->del_flg = ValueUtil::constToValue('Common.delFlg.NOT_DELETE');
            if ($query->save()) {
                return true;
            }
            return false;
        } catch (\Exception $error) {
            return false;
        }
    }

    /**
     * update last_login
     *
     * @param $id
     * @return false|void
     */
    public function updateLastLogin($id) {
        try {
            $user = MstUser::find($id);
            $user->last_login = Carbon::now();
            $user->save();
        } catch (\Exception $error) {
            return false;
        }
    }

    /**
     * get user
     */
    public function getUser($id, $data = []) {
        // $mstUser = MstUser::find($id);
        $mstUser = MstUser::query();
        $mstUser->where('mst_user.id', '=', $id);
        $mstUser->where('mst_user.del_flg', '<>', ValueUtil::constToValue('Common.delFlg.DELETED'));
        if ($data) {
            // add another condition
        }
        return $mstUser->first();
    }

    /**
     * search user
     */
    public function search(Array $data = []) {
        try {
            // get constant
            $unDeleted = ValueUtil::constToValue('Common.delFlg.NOT_DELETE');
            $admin = ValueUtil::constToValue('MstUser.userType.SYSTEM_ADMIN');
            $selfReconciliation = ValueUtil::constToValue('MstUser.userType.SELF_RECONCILIATION');
            $office = ValueUtil::constToValue('MstUser.userType.OFFICE');
            // get params
            $userType = isset($data['user_type']) ? $data['user_type'] : null;
            $officeCode = isset($data['office_code']) ? $data['office_code'] : null;
            $officeName = isset($data['office_name']) ? $data['office_name'] : null;
            $userName = isset($data['user_name']) ? $data['user_name'] : null;
            $traderAuthority = !empty($data['trader_authority']) ? $data['trader_authority'] : null;
            // get query
            DB::statement("SET block_encryption_mode = 'aes-256-cbc'");
            $query = MstUser::query();
            // add condition to search
            $query->where('mst_user.del_flg', $unDeleted);
            if (!empty($userType)) {
                $query->where('mst_user.user_type', $userType);
            } else {
                $loginUserType = auth()->user()->user_type;
                if ($loginUserType === $selfReconciliation) {
                    $query->where('mst_user.user_type', '<>', $admin);
                }
                if ($loginUserType === $office) {
                    $query->where('mst_user.user_type', '<>', $admin);
                    $query->where('mst_user.user_type', '<>', $selfReconciliation);
                }
            }
            if (strlen($officeCode) > 0) {
                $query->where('mst_user.office_code', 'like', "%$officeCode%");
            }
            if (strlen($officeName) > 0) {
                $query->where(function ($q) use ($officeName) {
                    $q->orWhere('mst_office.office_name', 'like', "%$officeName%");
                    $q->orWhere('mst_scrapper.office_name', 'like', "%$officeName%");
                });
            }

            if (strlen($userName) > 0) {
                $query->where(DB::raw("CONVERT(AES_DECRYPT(FROM_BASE64(`user_name`), UNHEX(SHA2(('kwNfBv0Dqr'),256)), FROM_BASE64(('VeXuN6Gssgr55DG4NZzTSA=='))) using utf8) collate utf8_unicode_ci"), 'like', "%$userName%");
            }

            if (!empty($traderAuthority)) {
                $query->where(function ($q) use ($traderAuthority) {
                    $trOffice = ValueUtil::constToValue('MstUser.traderAuthority.TR');
                    $syOffice = ValueUtil::constToValue('MstUser.traderAuthority.SY');
                    $secondTrOffice = ValueUtil::constToValue('MstUser.traderAuthority.2TR');
                    $rpOffice = ValueUtil::constToValue('MstUser.traderAuthority.RP');
                    $hasAuthority = ValueUtil::constToValue('Common.authority.ON');
                    // 1.運搬NW (tr_office_flg)
                    if (in_array($trOffice, $traderAuthority)) {
                        $q->orWhere('mst_user.tr_office_flg', $hasAuthority);
                    }
                    // 2.指定引取場所 (sy_office_flg)
                    if (in_array($syOffice, $traderAuthority)) {
                        $q->orWhere('mst_user.sy_office_flg', $hasAuthority);
                    }
                    // 3.二次運搬 (2nd_tr_office_flg)
                    if (in_array($secondTrOffice, $traderAuthority)) {
                        $q->orWhere('mst_user.2nd_tr_office_flg', $hasAuthority);
                    }
                    // 4.再資源化施設 (rp_office_flg)
                    if (in_array($rpOffice, $traderAuthority)) {
                        $q->orWhere('mst_user.rp_office_flg', $hasAuthority);
                    }
                });
            }

            // login user_type = 3
            if (Gate::check(ValueUtil::get('MstUser.permission')['NW']) ||
                Gate::check(ValueUtil::get('MstUser.permission')['SY']) ||
                Gate::check(ValueUtil::get('MstUser.permission')['SD']) ||
                Gate::check(ValueUtil::get('MstUser.permission')['RP']))
            {
                $query->where('mst_user.office_code', auth()->user()->office_code);
            }

            $query = $query
                ->select([
                    'mst_user.id',
                    'mst_user.user_name',
                    'mst_user.user_type',
                    'mst_user.invalid_flg',
                    'mst_user.tr_office_flg',
                    'mst_user.sy_office_flg',
                    'mst_user.2nd_tr_office_flg',
                    'mst_user.rp_office_flg',
                    'mst_user.office_admin_flg',
                    'mst_user.last_login',
                    'mst_user.office_code',
                    'mst_office.office_name',
                ])
                ->leftJoin('mst_office', 'mst_user.office_code', '=', 'mst_office.office_code')
                ->leftJoin('mst_scrapper', 'mst_user.office_code', '=', 'mst_scrapper.office_code')
                ->distinct(true)
                ;
            ;
            return $query;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * update user info
     */
    public function update($id, $data) {
        $mstUser = MstUser::find($id);
        $mstUser->user_name = EncryptUtil::encryptAes256($data['user_name']);;
        if (!empty($data['password'])) {
            $mstUser->password = EncryptUtil::encryptSha256($data['password']);
        }
        $mstUser->email = EncryptUtil::encryptAes256($data['email']);
        // 事業所
        if (!empty($mstUser->user_type)) {
            if ($mstUser->user_type === $this->userType2) {
                $mstUser->jarp_type = $data['jarp_type'];
            }
        }
        if ($mstUser->save()) {
            return true;
        }
        return false;
    }

    /**
     * set invalid_flg for user
     */
    public function updateUserInvalidFlg(int $id, int $status) {
        try {
            $mstUser = MstUser::find($id);
            if ($status === ValueUtil::constToValue('MstUser.invalidFlg.VALID')) {
                // 有効 -> 無効
                $mstUser->invalid_flg = ValueUtil::constToValue('MstUser.invalidFlg.VALID');
            } else if ($status === ValueUtil::constToValue('MstUser.invalidFlg.INVALID')) {
                // 無効 -> 有効
                $mstUser->invalid_flg = ValueUtil::constToValue('MstUser.invalidFlg.INVALID');
            } else {
                return false;
            }
            $mstUser->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * check unique for value
     */
    public function checkUnique($attribute, $value, array $data = []) {
        $query = MstUser::query();
        if ($attribute === 'id-login') {
            $query->where('login_id', EncryptUtil::encryptAes256($value));
        }
        if ($attribute === 'id-user') {
            $query->where('user_name', EncryptUtil::encryptAes256($value));
        }
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if ($key === 'id') {
                    $query->where('id', '<>', $value);
                }
            }
        }
        $mstUser = $query->get();
        return $mstUser;
    }

    /**
     * get login user
     */
    public function getLoginUser($data) {
        $query = MstUser::query();
        $query->select('id', 'password');
        $query->where('login_id', EncryptUtil::encryptAes256($data['login_id']));
        $query->where('invalid_flg', ValueUtil::constToValue('MstUser.invalidFlg.VALID'));
        $query->where('del_flg', ValueUtil::constToValue('Common.delFlg.NOT_DELETE'));
        $query->where('password', EncryptUtil::encryptSha256($data['password']));
        $result = $query->first();
        return $result;
    }

    /**
     * check unique data
     */
    public function checkUniqueData($type, $data, $id) {
        $query = MstUser::query();
        if ($type === 'loginId') {
            $query->where('login_id', EncryptUtil::encryptAes256($data));
        }
        if ($type === 'userName') {
            $query->where('user_name', EncryptUtil::encryptAes256($data));
        }
        if (!empty($id)) {
            $query->where('id', '<>', $id);
        }
        $result = $query->count();
        return $result;
    }

}
