@extends('layouts.app')

@section('title', '個数再確認')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-user">
            <div class="card-header"></div>
            <div class="card-body">
                <form action="{{ route('case.cas-090') }}" method="GET" class="form-search">
                    <div>
                        @foreach ($cas090OrderBy as $key => $configs)
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
                    <table id="cas090-table" class="table text-nowrap custom-data-table">
                        <thead>
                            <tr>
                                <th width="160px">荷姿ID</th>
                                <th width="80px">ケース番号</th>
                                <th width="100px">受入日</th>
                                <th width="280px">解体業者事業所名</th>
                                <th width="60px">運搬方法</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cases as $case)
                                <tr>
                                    <td>
                                        <a href="{{ route('case.cas-091', ['id' => $case->case_id]) }}">
                                            {{ $case->case_id }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $case->case_no }}
                                    </td>
                                    <td>
                                        {{ $case->receive_report_time }}
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
