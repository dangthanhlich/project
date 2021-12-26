@extends('layouts.app')

@section('title', 'ユーザー詳細')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header"></div>

                <div class="card-body">
                    <form class="form-add-edit" method="POST" action="{{ route('master.handleMst032', ['id' => $mstUser->id]) }}" id="mst032-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text name="id" label="ID" isLabel="true" value="{{ $mstUser->id }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text name="login_id" label="ログインID" isLabel="true" value="{{ $mstUser->login_id }}" />
                            </div>

                            <div class="col-md-4">
                                <x-forms.text
                                    name="user_name"
                                    label="ユーザー名"
                                    isRequired="true"
                                    value="{{ old('_token') !== null ? old('user_name') : $mstUser->user_name }}"
                                />
                            </div>

                            <div class="col-md-4">
                                <x-forms.text type="password" name="password" label="パスワード" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.select
                                    label="権限"
                                    isLabel="true"
                                    keySelected="{{ $mstUser->user_type }}"
                                    isRequired="true"
                                    :options="$userTypeList"
                                    name="user_type"
                                />
                            </div>

                            <div class="col-md-4">
                                <x-forms.text
                                    name="email"
                                    label="メールアドレス"
                                    value="{{ old('_token') !== null ? old('email') : $mstUser->email }}"
                                />
                            </div>

                            <div class="col-md-4">
                                <x-forms.text
                                    name="last_login"
                                    isLabel="true"
                                    label="最終ログイン日時"
                                    value="{{ $mstUser->last_login }}"
                                />
                            </div>
                        </div>

                        @if ($mstUser->user_type === getConstValue('MstUser.userType.SELF_RECONCILIATION'))
                        <div class="row jarpContent">
                            <div class="col-md-4">
                                <x-forms.select
                                    label="自再協権限"
                                    id="jarp-type"
                                    isRequired="true"
                                    :options="getList('MstUser.jarpType')"
                                    name="jarp_type"
                                    :keySelected="old('_token') ? old('jarp_type') : $mstUser->jarp_type"
                                />
                            </div>
                        </div>
                        @endif

                        @if($mstUser->user_type === getConstValue('MstUser.userType.OFFICE'))
                        <div class="row officeContent">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>所属事業所コード</label>
                                    <div class="row">
                                        <div class="col-md-3 pr-1">
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="office_code"
                                                value="{{ $mstUser->office_code }}"
                                                disabled>
                                        </div>

                                        <div class="col-1 pl-1">
                                            @if (!empty($mstUser->mst_office))
                                            <a href="{{ route('master.mst-022', ['id' => $mstUser->mst_office->id]) }}">
                                                <i class="nc-icon nc-alert-circle-i mt-2"></i>
                                            </a>
                                            @endif
                                        </div>

                                        <div class="col-md-8">
                                            <label class="mt-2">
                                                {{ !empty($mstUser->mst_office) ? $mstUser->mst_office->office_name : '' }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row officeContent">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>事業所管理者</label>
                                    <div class="check">
                                        <label>
                                            <input
                                                name="office_manager"
                                                {{ $mstUser->office_admin_flg === getConstValue('MstUser.officeAdminFlg.WITH_AUTHORITY') ? 'checked' : '' }}
                                                type="checkbox"
                                                class="minimal-blue"
                                                disabled
                                            >
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @php
                                    $traderAuthority = [];
                                    if ($mstUser->tr_office_flg === getConstValue('MstUser.trOfficeFlg.WITH_AUTHORITY')) {
                                        $traderAuthority[] = 1;
                                    }
                                    if ($mstUser->sy_office_flg === getConstValue('MstUser.syOfficeFlg.WITH_AUTHORITY')) {
                                        $traderAuthority[] = 2;
                                    }
                                    if ($mstUser['2nd_tr_office_flg'] === getConstValue('MstUser.2ndTrOfficeFlg.WITH_AUTHORITY')) {
                                        $traderAuthority[] = 3;
                                    }
                                    if ($mstUser->rp_office_flg === getConstValue('MstUser.rpOfficeFlg.WITH_AUTHORITY')) {
                                        $traderAuthority[] = 4;
                                    }
                                @endphp
                                <x-forms.checkbox
                                    label="業者権限"
                                    :options="getList('MstUser.traderAuthority')"
                                    name="trader_authority"
                                    :valueChecked="$traderAuthority"
                                    :isLabel="true"
                                />
                            </div>
                        </div>
                        @endif

                        <div class="text-right">
                            <x-button type="submit" class="btn-info" label="保存" />
                            @if (auth()->user()->user_type === getConstValue('MstUser.userType.SELF_RECONCILIATION') &&
                                auth()->user()->jarp_type === getConstValue('MstUser.jarpType.PUBLIC_RELATION')
                            )
                                <x-button.back href="{{ route('common.com-020') }}" label="戻る" />
                            @else
                                <x-button.back href="{{ route('master.mst-030') }}" label="戻る" />
                            @endif
                        </div>
                        <input type="hidden" name="user_type" value={{ $mstUser->user_type }} readonly>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="checkUniqueData" value="{{ route('master.checkUniqueData') }}">
    <input type="hidden" id="userId" value="{{ $mstUser->id }}">
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/mst/mst03/mst032.js') }}"></script>
@endpush
