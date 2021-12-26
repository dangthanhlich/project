@extends('layouts.app')

@section('title', 'ケース集荷予定一覧')

@section('content')
<div class="row">
    <div class="col-12">
        <form method="POST" action="{{ route('case.handleCas010') }}" id="cas010-form">
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
                            <x-forms.radio
                                label="集荷予定"
                                name="schedule_picked_up"
                                :options="getList('PlanCase.schedulePickedUp')"
                                :keySelected="isset($paramsSearch['schedule_picked_up']) ? $paramsSearch['schedule_picked_up'] : array_key_first(getList('PlanCase.schedulePickedUp'))"
                                :dataDefault="array_key_first(getList('PlanCase.schedulePickedUp'))"
                            />
                        </div>
                        <div class="col-md-8">
                            @if (
                                isset($paramsSearch['schedule_picked_up']) &&
                                $paramsSearch['schedule_picked_up'] != getValue('PlanCase.schedulePickedUp.UNREGISTERED_ONLY')
                            )
                                <div id="row-collect-plan-date" class="row">
                                    <div class="col-md-5">
                                        <x-forms.date
                                            label="集荷予定日"
                                            name="collect_plan_date_from"
                                            :value="isset($paramsSearch['collect_plan_date_from']) ? $paramsSearch['collect_plan_date_from'] : ''"
                                            :dataDefault="$defaultSysDate"
                                        />
                                    </div>
                                    <span class="col-1 text-center" style="padding: 35px 0;">〜</span>
                                    <div class="col-md-5">
                                        <x-forms.date
                                            label="集荷予定日"
                                            name="collect_plan_date_to"
                                            classLabel="invisible"
                                            :value="isset($paramsSearch['collect_plan_date_to']) ? $paramsSearch['collect_plan_date_to'] : ''"
                                        />
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-forms.select 
                                label="解体業者事業所名" 
                                name="office_code"
                                :isSearch="true"
                                :options="$lstOffice"
                                :keySelected="isset($paramsSearch['office_code']) ? $paramsSearch['office_code'] : ''" 
                            />
                        </div>
                        <div class="col-md-4">
                            <x-forms.text 
                                label="解体業者住所" 
                                name="office_address_search"
                                :value="isset($paramsSearch['office_address_search']) ? $paramsSearch['office_address_search'] : ''" 
                            />
                        </div>
                    </div>

                    <div class="text-right col-12">
                        <x-button label="検索" type="submit" class="btn-info" id="btn-search" />
                        <x-button.clear screen="cas010" />
                    </div>
                </div>
            </div>

            <div class="card card-user">
                <div class="card-header">
                    <div class="row">
                        <h5 class="card-title col-md-6">検索結果</h5>
                        <div class="text-right col-md-6">
                            <x-button label="CSV出力" name="btn_export_csv" type="submit" class="btn-success" />
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div>
                        <span>集荷予定合計 ケース数: {{ number_format($sumCaseQty) }}</span>
                        <span>空ケース数: {{ number_format($sumEmptyCaseQty) }}</span>
                        <span>空袋数: {{ number_format($sumBagQty) }}</span>
                    </div>
                    <div class="table-responsive table-hover">
                        <table id="cas010-table" class="table text-nowrap custom-data-table">
                            <thead>
                                <tr>
                                    <th width="200px">解体業者事業所名</th>
                                    <th width="80px">注意事項</th>
                                    <th width="350px">住所</th>
                                    <th width="150px">電話番号</th>
                                    <th width="120px">未集荷ケース数</th>
                                    <th width="110px">集荷依頼日</th>
                                    <th width="110px">集荷予定日</th>
                                    <th width="90px">予定日調整</th>
                                    <th width="100px">集荷予定</th>
                                    <th width="120px">ケース数</th>
                                    <th width="200px">メモ</th>
                                    <th width="80px">空ケース数</th>
                                    <th width="60px">空袋数</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mstScrappers as $mstScrapper)
                                    <tr>
                                        <td>
                                            <x-html.link
                                                :to="route('master.mst-011', ['id' => $mstScrapper->id])"
                                                :label="$mstScrapper->office_name"
                                            />
                                        </td>
                                        <td>
                                            {{ 
                                                !empty($mstScrapper->memo_jarp) || !empty($mstScrapper->memo_tr) 
                                                    ? 'あり' 
                                                    : ''
                                            }}
                                        </td>
                                        <td>{{ $mstScrapper->office_address_search }}</td>
                                        <td>{{ $mstScrapper->office_tel }}</td>
                                        <td>
                                            <x-html.link
                                                :to="route('case.cas-020', ['id' => $mstScrapper->id])"
                                                label="{{ count($mstScrapper->case) }}"
                                            />
                                        </td>
                                        <td>
                                            {{ 
                                                !empty($mstScrapper->case) 
                                                    ?  $mstScrapper->case->min('collect_request_time')
                                                    : ''
                                            }}
                                        </td>
                                        <td>
                                            @if (!empty($mstScrapper->planCase_id))
                                                <x-html.link
                                                    :to="route('case.cas-012', ['id' => $mstScrapper->planCase_id])"
                                                    :label="$mstScrapper->collect_plan_date"
                                                />
                                            @endif
                                        </td>
                                        <td>
                                            <input 
                                                type="checkbox" 
                                                class="minimal-blue" 
                                                disabled
                                                {{ 
                                                    isset($mstScrapper->plan_date_adjusted_flg) &&
                                                    $mstScrapper->plan_date_adjusted_flg == getConstValue('PlanCase.planDateAdjustedFlg.ADJUSTED')
                                                        ? 'checked'
                                                        : ''
                                                }}
                                            />
                                        </td>
                                        <td>
                                            @php
                                                $sysDatetime = $now->format('Y-m-d H:i:s');
                                            @endphp
                                            @if (
                                                (empty($mstScrapper->last_deliver_report_time) || 
                                                    (
                                                        !empty($mstScrapper->last_deliver_report_time) &&
                                                        $mstScrapper->last_deliver_report_time < $sysDatetime &&
                                                        diff2Date($sysDatetime, $mstScrapper->last_deliver_report_time)->y > 1
                                                    )
                                                ) &&
                                                $mstScrapper->teach_complete_flg == getConstValue('MstScrapper.teachCompleteFlg.NOT_INSTRUCTED')
                                            )
                                                {{ getMessage('m-001') }}
                                            @endif
                                            @if (
                                                (
                                                    !empty($mstScrapper->last_deliver_report_time) && 
                                                    $mstScrapper->last_deliver_report_time < $sysDatetime &&
                                                    diff2Date($sysDatetime, $mstScrapper->last_deliver_report_time)->y <= 1
                                                ) ||
                                                $mstScrapper->teach_complete_flg == getConstValue('MstScrapper.teachCompleteFlg.INSTRUCTED')
                                            )
                                                <x-html.link
                                                    :to="route('case.cas-011', ['id' => $mstScrapper->id])"
                                                    label="登録"
                                                    :isBtn="true"
                                                    class="btn-info btn-mini"
                                                />
                                            @endif
                                        </td>
                                        <td>
                                            {{ 
                                                isset($mstScrapper->case_qty) && is_numeric($mstScrapper->case_qty)
                                                    ? number_format($mstScrapper->case_qty)
                                                    : ''
                                            }}
                                        </td>
                                        <td>
                                            {{  
                                                isset($mstScrapper->collect_plan_memo)
                                                    ? $mstScrapper->collect_plan_memo
                                                    : ''
                                            }}
                                        </td>
                                        <td>
                                            {{ 
                                                isset($mstScrapper->empty_case_qty) && is_numeric($mstScrapper->empty_case_qty)
                                                    ? number_format($mstScrapper->empty_case_qty)
                                                    : ''
                                            }}
                                        </td>
                                        <td>
                                            {{ 
                                                isset($mstScrapper->bag_qty) && is_numeric($mstScrapper->bag_qty)
                                                    ? number_format($mstScrapper->bag_qty)
                                                    : ''
                                            }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $mstScrappers->links('common.pagination') }}
            </div>
        </form>
    </div>
</div>
@endsection

<input type="hidden" id="lst-schedule-picked-up" value="{{ json_encode(getKeyValueList('PlanCase.schedulePickedUp')) }}" />

@push('scripts')
    <script src="{{ asset('js/screens/cas/cas01/cas010.js') }}"></script>
@endpush