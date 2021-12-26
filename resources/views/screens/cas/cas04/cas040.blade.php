@extends('layouts.app')

@section('title', 'ケース受入予定一覧')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('case.handleCas040') }}" method="POST" id="case040-form">
                    @csrf
                    <div class="card card-user">
                        <div class="card-header">
                            <div class="row">
                                <h5 class="card-title col-12">検索条件</h5>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <x-forms.radio label="受入予定" name="schedule_picked_up"
                                                   :options="getList('PlanCase.schedulePickedUpCas040')"
                                                   :keySelected="isset($paramsSearch['schedule_picked_up']) ? $paramsSearch['schedule_picked_up'] : array_key_first(getList('PlanCase.schedulePickedUpCas040'))"
                                                   :dataDefault="array_key_first(getList('PlanCase.schedulePickedUpCas040'))"/>
                                </div>
                                <div class="col-md-8">
                                    @if (isset($paramsSearch['schedule_picked_up']) && $paramsSearch['schedule_picked_up'] != getValue('PlanCase.schedulePickedUpCas040.UNREGISTERED_ONLY'))
                                        <div id="row-receive-plan-date" class="row">
                                            <div class="col-md-5">
                                                <x-forms.date label="受入予定日" name="receive_plan_date_from"
                                                              :value="isset($paramsSearch['receive_plan_date_from']) ? $paramsSearch['receive_plan_date_from'] : ''"
                                                              :dataDefault="$defaultSysDate"/>
                                            </div>
                                            <span class="col-1 text-center" style="padding: 35px 0;">〜</span>
                                            <div class="col-md-5">
                                                <x-forms.date label="受入予定日" name="receive_plan_date_to"
                                                              classLabel="invisible"
                                                              :value="isset($paramsSearch['receive_plan_date_to']) ? $paramsSearch['receive_plan_date_to'] : ''"/>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-forms.radio
                                                label="運搬方法"
                                                name="transport_type"
                                                :options="getList('PlanCase.transportTypeCas040')"
                                                :keySelected="isset($paramsSearch['transport_type']) ?
                                                            $paramsSearch['transport_type']
                                                            : array_key_first(getList('PlanCase.transportTypeCas040'))"
                                                :dataDefault="array_key_first(getList('PlanCase.transportTypeCas040'))"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-forms.select label="解体業者事業所名" name="scrapper_office_code" :isSearch="true"
                                                        :options="$lstOffice"
                                                        :keySelected="isset($paramsSearch['scrapper_office_code']) ? $paramsSearch['scrapper_office_code'] : ''"/>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right col-12">
                                <x-button type="submit" class="btn-info" id="btn-search" label="検索"/>
                                <x-button.clear screen="cas040"/>
                            </div>
                        </div>
                    </div>

                    <div class="card card-user">
                        <div class="card-header">
                            <div class="row">
                                <h5 class="card-title col-md-6">検索結果</h5>
                                <div class="text-right col-md-6">
                                    <x-button label="CSV出力" name="btn_export_csv" type="submit" class="btn-success"/>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">

                            <div>
                                <span>合計受入予定ケース数 {{ number_format($sumCaseQty) }}</span>
                                <span>空ケース数 {{ number_format($sumEmptyCaseQty) }}</span>
                                <span>空袋数 {{ number_format($sumBagQty) }}</span>
                            </div>

                            <div class="table-responsive table-hover">
                                {{-- table text-nowrap custom-data-table --}}
                                 <table id="cas040-table" class="table text-nowrap custom-data-table">
{{--                                <table class="table text-nowrap " id="table1">--}}
                                    <thead>
                                    <tr>
                                        <th width="150px">
                                            業者事業所名
                                        </th>
                                        <th width="90px">
                                            注意事項
                                        </th>
                                        <th width="130px">
                                            運搬方法
                                        </th>
                                        <th width="130px">
                                            未受入ケース数
                                        </th>
                                        <th width="130px">
                                            集荷依頼日
                                        </th>
                                        <th width="130px">
                                            集荷予定日
                                        </th>
                                        <th width="130px">
                                            受入予定日
                                        </th>
                                        <th width="150px">
                                            受入予定
                                        </th>
                                        <th width="150px">
                                            受入予定ケース数
                                        </th>
                                        <th width="80px">
                                            メモ
                                        </th>
                                        <th width="100px">
                                            空ケース数
                                        </th>
                                        <th width="80px">
                                            空袋数
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($planCases as $pCase)
                                        <tr>
                                        {{-- SCRAPPER --}}
                                        @if ($pCase['plan_case_type'] == getConstValue('PlanCase.planCaseTypeCas040.SCRAPPER_PLAN_CASE'))
                                            <td>
                                                <x-html.link
                                                :to="route('master.mst-011', ['id' => $pCase->scrapper_id])"
                                                :label="$pCase->office_name"/>
                                            </td>
                                            @if (!empty($pCase->id))
                                            <td>
                                                {{ !empty($pCase->memo_jarp) || !empty($pCase->memo_tr) ? 'あり' : '' }}
                                            </td>
                                            <td>持込</td>
                                            <td>
                                                <x-html.link :to="route('case.redirectCas080', ['case_status[0]' => 1, 'mst_scrapper_office_code' => $pCase->office_code])"
                                                    label="{{ !empty($caseOfScrapper[$pCase->office_code]) ? $caseOfScrapper[$pCase->office_code]['total'] : 0 }}"
                                                    />

                                            </td>
                                            <td>
                                                {{ !empty($caseOfScrapper[$pCase->office_code]) ? formatDate($caseOfScrapper[$pCase->office_code]['collect_request_time'], 'Y/m/d') : '' }}
                                            </td>
                                            <td>
                                                {{ !empty($pCase->collect_plan_date) ? formatDate($pCase->collect_plan_date, 'Y/m/d') : '' }}
                                            </td>
                                            <td>
                                                <x-html.link :to="route('case.cas-042', ['id' => (int)$pCase->id])"
                                                            :label="formatDate($pCase['receive_plan_date'], 'Y/m/d')"/>
                                            </td>
                                            <td>
                                            @if(
                                                (
                                                    empty($pCase->last_deliver_report_time)
                                                    ||
                                                    diff2Date(formatDate($pCase->last_deliver_report_time,'Y/m/d'), date('Y/m/d'))->y > 0
                                                )
                                                &&
                                                $pCase->teach_complete_flg == getConstValue('MstScrapper.teachCompleteFlg.NOT_INSTRUCTED')
                                            )

                                                {{ getMessage('m-001') }}
                                            @else
                                                <x-html.link
                                                    :to="route('case.cas-041', ['id' => (int)$pCase->scrapper_id])"
                                                    label="登録"
                                                    :isBtn="true"
                                                    class="btn-info btn-mini"/>
                                            @endif
                                            </td>
                                            <td>{{ number_format($pCase->case_qty) }}</td>
                                            <td>{{ $pCase->receive_plan_memo }}</td>
                                            <td>{{ number_format($pCase->empty_case_qty) }}</td>
                                            <td>{{ number_format($pCase->bag_qty) }}</td>
                                            @else
                                            <td colspan="11"></td>
                                            @endif
                                        @else
                                            <td>{{ $pCase->office_name }}</td>
                                            <td></td>
                                            <td>運搬NW利用</td>
                                            <td>
                                                {{ !empty($caseOfOffice[$pCase->office_code]) ? $caseOfOffice[$pCase->office_code]['total'] : '' }}
                                            </td>
                                            <td>
                                                {{ !empty($caseOfOffice[$pCase->office_code]) ? formatDate($caseOfOffice[$pCase->office_code]['collect_request_time'], 'Y/m/d') : '' }}
                                            </td>
                                            <td>
                                                {{ !empty($pCase->collect_plan_date) ? formatDate($pCase->collect_plan_date, 'Y/m/d') : '' }}
                                            </td>
                                            <td>
                                                {{ !empty($pCase->receive_plan_date) ? formatDate($pCase->receive_plan_date, 'Y/m/d') : '' }}
                                            </td>
                                            <td>
                                                <button data-id="{{ $pCase->id }}" type="button"
                                                    class="btn btn-success btn-round btn-mini"
                                                    id="updatePlan"
                                                    data-toggle="modal" data-target="#modalreceivedate">
                                                    予定日変更
                                                </button>
                                            </td>
                                            <td>{{ $pCase->case_qty }}</td>
                                            <td>{{ $pCase->receive_plan_memo }}</td>
                                            <td>{{ $pCase->empty_case_qty }}</td>
                                            <td>{{ $pCase->bag_qty }}</td>
                                        @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                         {{ $planCases->links('common.pagination') }}
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('screens.cas.cas04.popup')

@endsection
<input type="hidden" id="lst-schedule-picked-up"
       value="{{ json_encode(getKeyValueList('PlanCase.schedulePickedUpCas040')) }}"/>

@push('scripts')
    <script src="{{ mix('js/screens/cas/cas04/cas040.js') }}"></script>
@endpush

@push('style')
    <link href="{{ mix('css/screens/cas/cas04/popup040.css') }}" rel="stylesheet" />
@endpush
