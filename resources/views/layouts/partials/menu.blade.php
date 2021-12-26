@php
    use App\Libs\ValueUtil;
    // ②運N → NW / user_type = 3 & tr_office_flg = 1
    // ③SY → SY / user_type = 3 & sy_office_flg = 1
    // ④二次 → SD / user_type = 3 & 2nd_tr_office_flg = 1
    // ⑤RP → RP / user_type = 3 & rp_office_flg = 1
    // ⑥自再協（施設管理 / 渉外）→ JA / user_type = 2 & jarp_type = 1 or 2
    // ⑦システム管理者 → user_type = 1
    $isNW = false;
    $isSY = false;
    $isSD = false;
    $isRP = false;
    $isJA = false;
    $isAdmin = false;
    $user = auth()->user();
    $admin = ValueUtil::constToValue('MstUser.userType.SYSTEM_ADMIN');
    $isSelfReconciliation = ValueUtil::constToValue('MstUser.userType.SELF_RECONCILIATION');
    $isOffice = ValueUtil::constToValue('MstUser.userType.OFFICE');
    if ($user->user_type === $admin) {
        $isAdmin = true;
    } else if ($user->user_type === $isSelfReconciliation &&
                $user->jarp_type === ValueUtil::constToValue('MstUser.jarpType.FACILITY_MANAGEMENT') ||
                $user->jarp_type === ValueUtil::constToValue('MstUser.jarpType.PUBLIC_RELATION')
    ) {
        $isJA = true;
    } else if ($user->user_type === $isOffice &&
                $user->tr_office_flg === ValueUtil::constToValue('Common.authority.ON')
    ) {
        $isNW = true;
    } else if ($user->user_type === $isOffice &&
                $user->sy_office_flg === ValueUtil::constToValue('Common.authority.ON')
    ) {
        $isSY = true;
    } else if ($user->user_type === $isOffice &&
                $user['2nd_tr_office_flg'] === ValueUtil::constToValue('Common.authority.ON')
    ) {
        $isSD = true;
    } else if ($user->user_type === $isOffice &&
                $user->rp_office_flg === ValueUtil::constToValue('Common.authority.ON')
    ) {
        $isRP = true;
    }
@endphp

<div class="sidebar-wrapper">
    <ul class="nav">
        <li class="">
            <a href="{{ route('common.com-020') }}">
                <i class="nc-icon nc-tile-56"></i>
                <p>ダッシュボード</p>
            </a>
        </li>

        @if ($isNW)
            @include('layouts.partials.menuForNW')
        @endif

        @if ($isSY)
            @include('layouts.partials.menuForSY')
        @endif

        @if ($isSD)
            @include('layouts.partials.menuForSD')
        @endif

        @if ($isRP)
            @include('layouts.partials.menuForRP')
        @endif

        @if ($isJA)
            @include('layouts.partials.menuForJA')
        @endif

        @if ($isAdmin)
            @include('layouts.partials.menuForAdmin')
        @endif

        <li>
            <a data-toggle="collapse" class="not-tracking" href="#group1">
                <i class="nc-icon nc-laptop"></i>
                <p>マスタ管理<b class="caret"></b></p>
            </a>
            <div class="collapse" id="group1">
                <ul class="nav">
                    <li class="nav-item">
                        <a href="{{ route('master.mst-010') }}">
                            <i class="nc-icon nc-settings"></i>
                            <span class="sidebar-normal">解体業者</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('master.mst-020') }}">
                            <i class="nc-icon nc-badge"></i>
                            <span class="sidebar-normal">その他の業者</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('master.mst-030') }}">
                            <i class="nc-icon nc-circle-10"></i>
                            <span class="sidebar-normal">ユーザー</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('master.mst-040') }}">
                            <i class="nc-icon nc-money-coins"></i>
                            <span class="sidebar-normal">単価</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('master.mst-050') }}">
                            <i class="nc-icon nc-bus-front-12"></i>
                            <span class="sidebar-normal">認定車両</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

    </ul>
</div>
