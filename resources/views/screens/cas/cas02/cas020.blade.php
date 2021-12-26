@extends('layouts.app')

@section('title', 'ケース一覧')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-user">
                    <div class="card-header">
                        <div class="row">
                            <h5 class="card-title col-12">検索条件</h5>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('case.handleCas020') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-forms.checkbox label="荷姿ステータス" name="case_status"
                                                          :options="getList('Case.cas020CaseStatus')"
                                                          :valueChecked="isset($params['case_status']) ? $params['case_status'] : []"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <x-forms.select
                                        label="解体業者事業者名"
                                        name="mst_scrapper_office_code"
                                        :isSearch="true"
                                        :options="$lstMstScrapper"
                                        :keySelected="isset($params['mst_scrapper_office_code']) ? $params['mst_scrapper_office_code'] : ''"/>
                                </div>
                                <div class="col-md-4">
                                    <x-forms.text
                                        name="case_no"
                                        label="ケース番号"
                                        :value="isset($params['case_no']) ? $params['case_no'] : ''"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <x-forms.text
                                        name="case_id"
                                        label="荷姿ID"
                                        :value="isset($params['case_id']) ? $params['case_id'] : ''"/>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <label class="col-md-5">
                                            <x-forms.date label="集荷依頼日"
                                                          name="collect_request_time_from"
                                                          :value="isset($params['collect_request_time_from']) ? $params['collect_request_time_from'] : ''"/>
                                        </label>
                                        <label class="col-1 pt-2 text-center" style="margin: 30px 0;">〜</label>
                                        <label class="col-md-5">
                                            <x-forms.date label="集荷依頼日"
                                                          name="collect_request_time_to"
                                                          classLabel="invisible"
                                                          :value="isset($params['collect_request_time_to']) ? $params['collect_request_time_to'] : ''"/>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <x-forms.text
                                        name="management_no"
                                        label="管理番号"
                                        :value="isset($params['management_no']) ? $params['management_no'] : ''"/>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <label class="col-md-5">
                                            <x-forms.date label="集荷日"
                                                          name="collect_complete_time_from"
                                                          :value="isset($params['collect_complete_time_from']) ? $params['collect_complete_time_from'] : ''"/>
                                        </label>
                                        <label class="col-1 pt-2 text-center" style="margin: 30px 0;">〜</label>
                                        <label class="col-md-5">
                                            <x-forms.date label="集荷日"
                                                          name="collect_complete_time_to"
                                                          classLabel="invisible"
                                                          :value="isset($params['collect_complete_time_to']) ? $params['collect_complete_time_to'] : ''"/>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>未集荷経過日数</label>
                                        <div class="row">
                                            <label class="col-md-5">
                                                <input type="text" class="form-control" name="num_of_request_days_from"
                                                       value="{{ !empty($params['num_of_request_days_from']) ? $params['num_of_request_days_from'] : ''}}">
                                            </label>
                                            <label class="col-1 pt-2 text-center">〜</label>
                                            <label class="col-md-5">
                                                <input type="text" class="form-control" name="num_of_request_days_to"
                                                       value="{{ !empty($params['num_of_request_days_to']) ? $params['num_of_request_days_to'] : ''}}">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right col-12">
                                <x-button type="submit" class="btn-info" id="btn-search" label="検索"/>
                                <x-button.clear screen="cas020"/>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card card-user">
                    <form action="{{ route('case.downloadFileCas020') }}" method="POST">
                        @csrf
                        <div class="card-header">
                            <div class="row">
                                <h5 class="card-title col-md-6">検索結果</h5>
                                <div class="text-right col-md-6">
                                    <button type="submit" class="btn btn-success btn-round" id="btn-download" disabled>
                                        契約書出力
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive table-hover">
                                <table class="table text-nowrap custom-data-table" id="table-cas02">
                                    <thead>
                                    <tr>
                                        <th width="40px">
                                            <input type="checkbox" id="all">
                                        </th>
                                        <th width="180px">
                                            荷姿ID
                                        </th>
                                        <th width="300px">
                                            解体業者事業所名
                                        </th>
                                        <th width="100px">
                                            ケース番号
                                        </th>
                                        <th width="150px">
                                            ステータス
                                        </th>
                                        <th width="120px">
                                            集荷依頼日
                                        </th>
                                        <th width="120px">
                                            集荷予定日
                                        </th>
                                        <th width="120px">
                                            経過日数
                                        </th>
                                        <th width="300px">
                                            指定引取場所事業者名
                                        </th>
                                        <th width="120px">
                                            管理番号
                                        </th>
                                        <th width="120px">
                                            集荷日
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forEach ($cases as $case)
                                        <tr>
                                            <td>
                                                @if (!empty($case->contract_pdf))
                                                    <input type="checkbox" class="list" name="contract_pdfs[]"
                                                           value="{{ $case->contract_pdf }}"/>
                                                @else
                                                    <input type="checkbox" disabled>
                                                @endif
                                            </td>
                                            <td>
                                                <x-html.link
                                                    :to="route('case.cas-021', ['id' => $case->case_id])"
                                                    :label="$case->case_id"/>
                                            </td>
                                            <td>
                                                {{ $case->mst_scrapper_office_name }}
                                            </td>
                                            <td>
                                                {{ $case->case_no }}
                                            </td>
                                            <td>
                                                {{ !empty($case['case_status']) ? valueToText($case['case_status'], 'Case.cas020CaseStatus') : '' }}
                                            </td>
                                            <td>
                                                {{ $case->collect_request_time }}
                                            </td>
                                            <td>
                                                {{ $case->collect_plan_date }}
                                            </td>
                                            <td>
                                                {{ $case->num_of_request_days }}
                                            </td>
                                            <td>
                                                {{ $case->mst_office_office_name }}
                                            </td>
                                            <td>
                                                {{ $case->management_no }}
                                            </td>
                                            <td>
                                                {{ $case->collect_complete_time }}
                                            </td>
                                        </tr>
                                    @endforEach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                    {{ $cases->links('common.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/cas/cas02/cas020.js') }}"></script>
@endpush
