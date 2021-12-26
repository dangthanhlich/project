@extends('layouts.app')

@section('title', '引取報告用確認')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header"></div>
                <div class="card-body">
                    <form action="{{ route('case.handleCas100') }}" method="GET">
                        <div>
                            @foreach ($cas100OrderBy as $key => $configs)
                                <x-button 
                                    name="order_by" 
                                    value="{{ $key }}"
                                    label="{{ $configs['label'] }}" 
                                    type="submit"
                                    class="btn-info {{ isset($params['order_by']) && $key === $params['order_by'] ? 'active' : '' }}"
                                />
                            @endforeach
                    </div>
                    </form>
                    <div class="table-responsive table-hover">
                        <table id="cas100-table" class="table text-nowrap custom-data-table">
                            <thead>
                                <tr>
                                    <th width="180px">荷姿ID</th>
                                    <th width="100px">ケース番号</th>
                                    <th width="120px">受入日</th>
                                    <th width="300px">解体業者事業所名</th>
                                    <th width="80px">運搬方法</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cases as $case)
                                    @php
                                        $isLabel = true;
                                        $acceptanceDate = $case->receive_complete_time;
                                        if (
                                            $case->exceed_qty_flg == getConstValue('Case.exceedQtyFlg.THERE_IS_AN_EXCESS_NUMBER') &&
                                            !empty($case->car->toArray())
                                        ) {
                                            $isLabel = false;
                                            $acceptanceDate = '自再協作業中';
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            @if ($isLabel)
                                                <x-html.link
                                                    :to="route('case.cas-101', ['id' => $case->case_id])"
                                                    :label="$case->case_id"
                                                />
                                            @else
                                                {{ $case->case_id }}
                                            @endif
                                            
                                        </td>
                                        <td>
                                            {{ $case->case_no }}
                                        </td>
                                        <td>
                                            {{ $acceptanceDate }}
                                        </td>
                                        <td>
                                            {{ $case->office_name }}
                                        </td>
                                        <td>
                                            {{ valueToText($case->transport_type, 'Case.transportType') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $cases->links('common.pagination') }}
            </div>
        </div>
    </div>
@endsection
