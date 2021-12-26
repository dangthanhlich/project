@extends('layouts.app')

@section('title', 'ケース一覧')

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
                    <div class="row">
                        <div class="col-md-3">
                            <x-forms.text 
                                label="管理番号"
                                name="management_code"
                                :value="$managementNo"
                                :isLabel="true"
                            />
                        </div>
                        <div class="col-md-3">
                            <x-forms.text 
                                label="解体業者事業所名"
                                name="mstScrapper_office_code"
                                value="{{ !empty($mstScrapperManagementNo->office_code_name) ? $mstScrapperManagementNo->office_code_name : '' }}"
                                :isLabel="true"
                            />
                        </div>
                        <div class="col-md-3">
                            <x-forms.text 
                                label="運搬NW業者事業所名"
                                name="mstOffice_office_code"
                                value="{{ !empty($mstOfficeManagementNo->office_code_name) ? $mstOfficeManagementNo->office_code_name : '' }}"
                                :isLabel="true"
                            />
                        </div>
                        <div class="col-md-3">
                            <x-forms.date 
                                label="集荷日"
                                name="collect_complete_time"
                                :value="$cases->max('collect_complete_time')"
                                :isLabel="true"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('case.handleCas031') }}">
                @csrf
                @php
                    $isDisplay = true;
                    if (
                        isset($mstScrapperOfficeCode->transport_type) && 
                        $mstScrapperOfficeCode->transport_type == getConstValue('MstScrapper.transportType.ADVANCE_PAYMENT') 
                    ) {
                        $isDisplay = false;
                    }
                @endphp
                <div class="card card-user">
                    <div class="card-header">
                        <div class="row">
                            <h5 class="card-title col-md-6">集荷情報</h5>
                            <div class="text-right col-md-6">
                                @if ($isDisplay)
                                    <x-button label="契約書出力" type="submit" class="btn-success" id="btn-download" />
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive table-hover">
                            <table id="cas031-table" class="table text-nowrap custom-data-table">
                                <thead>
                                    <tr>
                                        @if ($isDisplay)
                                            <th width="40px">
                                                <input type="checkbox" id="select-all">
                                            </th>
                                        @endif
                                        <th width="180px">荷姿ID</th>
                                        <th width="100px">ケース番号</th>
                                        <th width="100px">集荷依頼日</th>
                                        <th>ステータス</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cases as $case)
                                        <tr>
                                            @if ($isDisplay)
                                                <td>
                                                    <input 
                                                        type="checkbox"
                                                        class="list"
                                                        name="contract_pdf[]"
                                                        value="{{ $case->contract_pdf }}"
                                                        {{ 
                                                            !empty($case->deliver_report_time) && !empty($case->contract_pdf) 
                                                                ? '' : 'disabled' 
                                                        }}
                                                    >
                                                </td>
                                            @endif
                                            <td>
                                                {{ $case->case_id }}
                                            </td>
                                            <td>
                                                {{ $case->case_no }}
                                            </td>
                                            <td>
                                                {{ $case->collect_request_time }}
                                            </td>
                                            <td>
                                                @if (empty($case->deliver_report_time))
                                                    {{ getMessage('m-004')}}
                                                @elseif (!empty($case->deliver_report_time) && empty($case->contract_pdf))
                                                    {{ getMessage('m-005')}}
                                                @elseif (!empty($case->deliver_report_time) && !empty($case->contract_pdf))
                                                    {{ getMessage('m-006')}}
                                                @endif
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
@endsection

@push('scripts')
<script src="{{ mix('js/screens/cas/cas03/cas031.js') }}"></script>
@endpush