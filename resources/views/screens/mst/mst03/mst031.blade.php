@extends('layouts.app')

@section('title', 'ユーザー登録')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header"></div>

                <div class="card-body">
                    <form class="form-add-edit" action="{{ route('master.handleMst031') }}" method="POST" autocomplete="off" id="mst031-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    name="id-login"
                                    label="ログインID"
                                    isRequired="true"
                                    :value="old('id-login') !== null ? old('id-login') : ''"
                                />
                            </div>

                            <div class="col-md-4">
                                <x-forms.text
                                    name="id-user"
                                    label="ユーザー名"
                                    isRequired="true"
                                    :value="old('id-user') !== null ? old('id-user') : ''"
                                />
                                <input
                                    style="display: none"
                                    data-label="ログインID"
                                    name="loginId"
                                    type="text"
                                    class="form-control"
                                    autocomplete="new-login-id"
                                    value="">
                            </div>

                            <div class="col-md-4">
                                <x-forms.text
                                    type="password"
                                    name="pass"
                                    label="パスワード"
                                    isRequired="true"
                                />
                                <input
                                    style="display: none"
                                    data-label="パスワード"
                                    type="password"
                                    class="form-control"
                                    autocomplete="new-password"
                                    name="password"
                                >
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.select
                                    label="権限"
                                    isRequired="true"
                                    :options="$userTypeList"
                                    name="user_type"
                                    :keySelected="old('user_type') !== null ? old('user_type') : ''"
                                />
                            </div>

                            <div class="col-md-4">
                                <x-forms.text
                                    name="email"
                                    label="メールアドレス"
                                    :value="old('email') !== null ? old('email') : ''"
                                />
                            </div>
                        </div>

                        <div class="row jarpContent" style="display: none">
                            <div class="col-md-4">
                                @php
                                    $valueList = getList('MstUser.jarpType');
                                @endphp
                                <x-forms.select
                                    label="自再協権限"
                                    isRequired="true"
                                    :options="$valueList"
                                    name="jarp_type"
                                    :keySelected="old('jarp_type') !== null ? old('jarp_type') : ''"
                                />
                            </div>
                        </div>

                        <div class="row officeContent" style="display: none">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>所属事業所コード<span class="red">*</span></label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input
                                                data-label="所属事業所コード"
                                                name="office_code"
                                                type="text"
                                                class="form-control"
                                                id="office-code"
                                                value="{{ old('office_code') !== null ? old('office_code') : '' }}"
                                            >
                                        </div>
                                        <div class="col-md-8">
                                            <label id="office_name" class="mt-2"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row officeContent" style="display: none">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>事業所管理者</label>
                                    <div class="check">
                                        <label>
                                            <input
                                                name="office_manager"
                                                data-label="事業所管理者"
                                                type="checkbox"
                                                class="minimal-blue"
                                                value="1"
                                                {{ old('office_manager') !== null ? 'checked' : '' }}
                                            ><span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    @php
                                        $valueList = getList('MstUser.traderAuthority');
                                    @endphp
                                    <x-forms.checkbox
                                        isRequired="true"
                                        label="業者権限"
                                        :options="$valueList"
                                        name="trader_authority"
                                        :valueChecked="is_array(old('trader_authority')) ? old('trader_authority') : []"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <x-button type="submit" class="btn-info" label="保存" />
                            <x-button.back href="{{ route('master.mst-030') }}" label="戻る" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="getNameOfficeUrl" value="{{ route('public.getNameOffice') }}">
    <input type="hidden" id="checkUniqueData" value="{{ route('master.checkUniqueData') }}">
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/mst/mst03/mst031.js') }}"></script>
@endpush
