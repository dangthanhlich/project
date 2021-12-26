@extends('layouts.app')

@section('title', 'ケース一覧')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-12">
            <form action="{{ route('case.handleCas080') }}" method="POST">
                @csrf
                <div class="card card-user">
                    <div class="card-header">
                        <div class="row">
                            <h5 class="card-title col-12">検索条件</h5>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <x-forms.checkbox label="ステータス" name="case_status"
                                                  :options="getList('Case.caseStatus')"
                                                  :valueChecked="isset($params['case_status']) ? $params['case_status'] : []"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <x-forms.checkbox label="パレット" name="pallet_case_search"
                                                  :options="getList('PlanCase.linkking')"
                                                  :valueChecked="isset($params['pallet_case_search']) ? $params['pallet_case_search'] : []"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.select
                                    label="解体業者事業所名"
                                    name="mst_scrapper_office_code"
                                    :isSearch="true"
                                    :options="$lstMstScrapper"
                                    :keySelected="isset($params['mst_scrapper_office_code']) ? $params['mst_scrapper_office_code'] : ''"/>
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    name="case_id"
                                    label="荷姿ID"
                                    :value="isset($params['case_id']) ? $params['case_id'] : ''"/>
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    name="case_no"
                                    label="ケース番号"
                                    :value="isset($params['case_no']) ? $params['case_no'] : ''"/>
                            </div>
                        </div>

                        <div class="text-right col-12">
                            <x-button type="submit" class="btn-info" id="btn-search" label="検索"/>
                            <x-button.clear screen="cas080"/>
                        </div>
                    </div>
                </div>

                <div class="card card-user">
                    <div class="card-header">
                        <div class="row">
                            <h5 class="card-title col-12">検索結果</h5>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive table-hover">
                            <table class="table text-nowrap custom-data-table" id="cas080-table">
                                <thead>
                                <tr>
                                    <th width="180px">
                                        荷姿ID
                                    </th>
                                    <th width="100px">
                                        ケース番号
                                    </th>
                                    <th width="120px">
                                        受入予定日
                                    </th>
                                    <th width="300px">
                                        解体業者事業所名
                                    </th>
                                    <th width="80px">
                                        運搬方法
                                    </th>
                                    <th width="150px">
                                        ステータス
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($cases as $case)
                                <tr>
                                    <td>
                                        <x-html.link
                                            :to="route('case.cas-081', ['id' => $case->case_id])"
                                            :label="$case->case_id"/>
                                    </td>
                                    <td>
                                        {{ !empty($case['case_no']) ? $case['case_no'] : '' }}
                                    </td>
                                    <td>
                                        {{ !empty($case['receive_plan_date']) ? $case['receive_plan_date'] : '' }}
                                    </td>
                                    <td>
                                        {{ !empty($case['office_name']) ? $case['office_name'] : '' }}
                                    </td>
                                    <td>
                                        {{ !empty($case['transport_type']) ? valueToText($case['transport_type'], 'Case.transportType') : '' }}
                                    </td>
                                    <td>
                                        {{ !empty($case['case_status']) ? valueToText($case['case_status'], 'Case.caseStatus') : '' }}
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{ $cases->links('common.pagination') }}
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
