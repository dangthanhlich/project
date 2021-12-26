<?php

namespace App\Providers;

use App\Libs\ValueUtil;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // ①解体 (not need login)

        // ②運N (NW)
        // user_type = 3 && tr_office_flg = 1
        Gate::define('is_NW', function ($user) {
            return $user->user_type === ValueUtil::constToValue('MstUser.userType.OFFICE') &&
                    $user->tr_office_flg === ValueUtil::constToValue('Common.authority.ON');
        });

        // ③SY
        // user_type = 3 && sy_office_flg = 1
        Gate::define('is_SY', function ($user) {
            return $user->user_type === ValueUtil::constToValue('MstUser.userType.OFFICE') &&
                    $user->sy_office_flg === ValueUtil::constToValue('Common.authority.ON');
        });

        // ④二次 (SD)
        // user_type = 3 && 2nd_tr_office_flg = 1
        Gate::define('is_SD', function ($user) {
            return $user->user_type === ValueUtil::constToValue('MstUser.userType.OFFICE') &&
                $user['2nd_tr_office_flg'] === ValueUtil::constToValue('Common.authority.ON');
        });

        // ⑤RP
        // user_type = 3 && rp_office_flg = 1
        Gate::define('is_RP', function ($user) {
            return $user->user_type === ValueUtil::constToValue('MstUser.userType.OFFICE') &&
                $user->rp_office_flg === ValueUtil::constToValue('Common.authority.ON');
        });

        // ⑥自再協 (JA) 施設管理
        // user_type = 2 && jarp_type = 1
        Gate::define('is_JA1', function ($user) {
            return $user->user_type === ValueUtil::constToValue('MstUser.userType.SELF_RECONCILIATION') &&
                $user->jarp_type === ValueUtil::constToValue('MstUser.jarpType.FACILITY_MANAGEMENT');
        });

        // ⑥自再協 (JA) 渉外
        // user_type = 2 && jarp_type = 2
        Gate::define('is_JA2', function ($user) {
            return $user->user_type === ValueUtil::constToValue('MstUser.userType.SELF_RECONCILIATION') &&
                $user->jarp_type === ValueUtil::constToValue('MstUser.jarpType.PUBLIC_RELATION');
        });

        // ⑦システム管理者 (Admin)
        // user_type = 1
        Gate::define('is_admin', function ($user) {
            return $user->user_type === ValueUtil::constToValue('MstUser.userType.SYSTEM_ADMIN');
        });
    }
}
