@extends('layouts.app')

@section('title', '解体業者一覧')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header">
                    <div class="row">
                        <h5 class="card-title col-12">検索条件</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('master.handleMst010') }}" method="POST" class="form-search">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業者/事業所コード"
                                    name="company_office_code"
                                    :value="isset($paramsSearch['company_office_code']) ? $paramsSearch['company_office_code'] : ''"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業者/事業所名"
                                    name="office_name"
                                    :value="isset($paramsSearch['office_name']) ? $paramsSearch['office_name'] : ''"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.checkbox
                                    label="運搬方法"
                                    name="transport_type"
                                    :options="getList('MstScrapper.transportType')"
                                    :valueChecked="isset($paramsSearch['transport_type']) ? $paramsSearch['transport_type'] : []"
                                />
                            </div>
                        </div>

                        @if (!Gate::check(getValue('MstUser.permission')['SY']))
                            <div class="row">
                                <div class="col-md-4">
                                    <x-forms.select
                                        label="指定引取場所事業所名"
                                        name="office_code"
                                        :isSearch="true"
                                        :options="$lstOfficeLocation"
                                        :keySelected="isset($paramsSearch['office_code']) ? $paramsSearch['office_code'] : ''"
                                    />
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所"
                                    name="office_address_search"
                                    :value="isset($paramsSearch['office_address_search']) ? $paramsSearch['office_address_search'] : ''"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所電話番号"
                                    name="office_tel"
                                    :value="isset($paramsSearch['office_tel']) ? $paramsSearch['office_tel'] : ''"
                                />
                            </div>
                        </div>

                        <div class="text-right col-12">
                            <x-button label="検索" type="submit" class="btn-info" id="btn-search" />
                            <x-button.clear screen="mst010" />
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
                        <table id="mst010-table" class="table text-nowrap custom-data-table">
                            <thead>
                                <tr>
                                    <th width="60px">ID</th>
                                    <th width="160px">解体業者事業所コード</th>
                                    <th width="200px">解体業者事業所名</th>
                                    <th width="160px">事業所電話番号</th>
                                    <th width="160px">担当者名</th>
                                    <th width="80px">運搬方法</th>
                                    <th width="180px">運搬NW業者事業所コード</th>
                                    <th width="200px">運搬NW業者事業所名</th>
                                    <th width="180px">指定引取場所事業所コード</th>
                                    <th width="200px">指定引取場所事業所名</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mstScrappers as $mstScrapper)
                                    <tr>
                                        <td>
                                            <x-html.link
                                                :to="route('master.mst-011', ['id' => $mstScrapper->id])"
                                                :label="$mstScrapper->id"
                                            />
                                        </td>
                                        <td>
                                            {{ $mstScrapper->office_code }}
                                        </td>
                                        <td>
                                            {{ $mstScrapper->office_name }}
                                        </td>
                                        <td>
                                            {{ $mstScrapper->office_tel }}
                                        </td>
                                        <td>
                                            {{ $mstScrapper->pic_name }}
                                        </td>
                                        <td>
                                            {{ valueToText($mstScrapper->transport_type, 'MstScrapper.transportType') }}
                                        </td>
                                        <td>
                                            @if (Gate::check(getValue('MstUser.permission')['NW']))
                                            {{ isset($mstScrapper['mstOfficeTrWithUser_office_code']) ? $mstScrapper['mstOfficeTrWithUser_office_code'] : '' }}
                                            @else
                                            {{ isset($mstScrapper['mstOfficeTr_office_code']) ? $mstScrapper['mstOfficeTr_office_code'] : '' }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ isset($mstScrapper['mstOfficeTr_office_name']) ? $mstScrapper['mstOfficeTr_office_name'] : '' }}
                                        </td>
                                        <td>
                                            @if (Gate::check(getValue('MstUser.permission')['SY']))
                                            {{ isset($mstScrapper['mstOfficeSyWithUser_office_code']) ? $mstScrapper['mstOfficeSyWithUser_office_code'] : '' }}
                                            @elseif (Gate::check(getValue('MstUser.permission')['RP']))
                                            {{ isset($mstScrapper['mstOfficeSyWithUserRp_office_code']) ? $mstScrapper['mstOfficeSyWithUserRp_office_code'] : '' }}
                                            @else
                                            {{ isset($mstScrapper['mstOfficeSy_office_code']) ? $mstScrapper['mstOfficeSy_office_code'] : '' }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ isset($mstScrapper['mstOfficeSy_office_name']) ? $mstScrapper['mstOfficeSy_office_name'] : '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $mstScrappers->links('common.pagination') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/mst/mst01/mst010.js') }}"></script>
@endpush
