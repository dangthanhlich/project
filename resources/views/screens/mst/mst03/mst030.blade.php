@extends('layouts.app')

@section('title', 'ユーザー一覧')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header">
                    <div class="row">
                        <h5 class="card-title col-md-6">検索条件</h5>
                        @if (
                            auth()->user()->user_type === getConstValue('MstUser.userType.SYSTEM_ADMIN') ||
                            auth()->user()->user_type === getConstValue('MstUser.userType.SELF_RECONCILIATION') ||
                            (auth()->user()->user_type === getConstValue('MstUser.userType.OFFICE') &&
                            auth()->user()->office_admin_flg === getConstValue('MstUser.officeAdminFlg.WITH_AUTHORITY'))
                        )
                        <div class="text-right col-md-6">
                            <x-button label="新規登録" class="btn-info" href="{{ route('master.mst-031') }}" />
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('master.handleMst030') }}" method="post" class="search-by-post" id="mst030">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.select
                                    label="権限"
                                    :options="$userTypeList"
                                    name="user_type"
                                    :keySelected="isset($paramsSearch['user_type']) ? $paramsSearch['user_type'] : ''"
                                />
                            </div>

                            <div class="col-md-4">
                                <x-forms.text
                                    name="office_code"
                                    label="事業所コード"
                                    :value="isset($paramsSearch['office_code']) ? $paramsSearch['office_code'] : ''"
                                />
                            </div>

                            <div class="col-md-4">
                                <x-forms.text
                                    name="office_name"
                                    label="事業所名"
                                    :value="isset($paramsSearch['office_name']) ? $paramsSearch['office_name'] : ''"
                                />
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-4">
                                <x-forms.text
                                    name="user_name"
                                    label="ユーザー名"
                                    :value="isset($paramsSearch['user_name']) ? $paramsSearch['user_name'] : ''"
                                />
                            </div>

                            <div class="col-md-8">
                                @php
                                    $valueList = getList('MstUser.traderAuthority');
                                @endphp
                                <x-forms.checkbox
                                    label="業者権限"
                                    :options="$valueList"
                                    name="trader_authority"
                                    :valueChecked="isset($paramsSearch['trader_authority']) ? $paramsSearch['trader_authority'] : []"
                                />
                            </div>

                        </div>
                        <div class="text-right col-12">
                            <x-button label="検索" type="submit" class="btn-info" />
                            <x-button.clear screen="mst030" />
                        </div>
                    </form>
                </div>
            </div>

            <div class="card card-user">
                <div class="card-header">
                    <h5 class="card-title">検索結果</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive table-hover">
                        <table id="mst030-table" class="table text-nowrap custom-data-table">
                            <thead>
                            <tr>
                                <th width="74px">ID</th>
                                <th width="114px">ユーザー名</th>
                                <th width="114px">事業所コード</th>
                                <th width="214px">事業所名</th>
                                <th width="114px">権限</th>
                                <th width="114px">事業所管理者</th>
                                <th width="194px">最終ログイン日時</th>
                                <th width="74px">運搬NW</th>
                                <th width="114px">指定引取場所</th>
                                <th width="74px">二次運搬</th>
                                <th width="114px">再資源化施設</th>
                                <th width="74px">ステータス</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($mstUsers as $mstUser)
                                <tr>
                                    <td>
                                        <x-html.link
                                            :to="route('master.mst-032', ['id' => $mstUser->id ])"
                                            :label="$mstUser->id"
                                        />
                                    </td>
                                    <td>{{ $mstUser->user_name }}</td>
                                    <td>{{ $mstUser->office_code }}</td>
                                    <td>{{ $mstUser->office_name }}</td>
                                    <td>
                                        {{ valueToText($mstUser->user_type, 'MstUser.userType') }}
                                    </td>
                                    <td>
                                        <label>
                                            <input type="checkbox" {{ $mstUser->office_admin_flg === getConstValue('MstUser.officeAdminFlg.WITH_AUTHORITY') ? 'checked' : '' }} class="minimal-blue" disabled>
                                        </label>
                                    </td>
                                    <td>
                                        {{ $mstUser->last_login }}
                                    </td>
                                    <td>
                                        <label>
                                            <input type="checkbox" {{ $mstUser->tr_office_flg === getConstValue('MstUser.trOfficeFlg.WITH_AUTHORITY') ? 'checked' : '' }} class="minimal-blue" disabled>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="checkbox" {{ $mstUser->sy_office_flg === getConstValue('MstUser.syOfficeFlg.WITH_AUTHORITY') ? 'checked' : '' }} class="minimal-blue" disabled>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="checkbox" {{ $mstUser['2nd_tr_office_flg'] === getConstValue('MstUser.2ndTrOfficeFlg.WITH_AUTHORITY') ? 'checked' : '' }} class="minimal-blue" disabled>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="checkbox" {{ $mstUser->rp_office_flg === getConstValue('MstUser.rpOfficeFlg.WITH_AUTHORITY') ? 'checked' : '' }} class="minimal-blue" disabled>
                                        </label>
                                    </td>
                                    <td>
                                        @if ($mstUser->invalid_flg === getConstValue('MstUser.invalidFlg.INVALID'))
                                            <x-button
                                                class="btn-warning btn-mini toggle active-user"
                                                label="有効"
                                                data-id="{{ $mstUser->id }}"
                                            />
                                        @endif

                                        @if ($mstUser->invalid_flg === getConstValue('MstUser.invalidFlg.VALID'))
                                            <x-button
                                                class="btn-danger btn-mini toggle disable-user"
                                                label="無効"
                                                data-id="{{ $mstUser->id }}"
                                            />
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{ $mstUsers->links('common.pagination') }}
            </div>
        </div>
    </div>

    <input type="hidden" id="setUserStatusUrl" value="{{ route('master.setUserStatusUrl') }}">
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/mst/mst03/mst030.js') }}"></script>
@endpush
