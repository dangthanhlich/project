@extends('layouts.app')

@section('title', '再資源化情報一覧')

@section('content')
<div class="row">
    <div class="col-12">
        <form id="rep010-form" method="POST" action="{{ route('recycle.handleRep010') }}" enctype="multipart/form-data">
            @csrf
            <div class="card card-user">
                <div class="card-header">
                    <div class="row">
                        <h5 class="card-title col-12">検索条件</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>年月</label>
                                <div class="row">
                                    <label class="col-md-5">
                                        <input
                                            type="month"
                                            name="report_month_from"
                                            class="form-control"
                                            value="{{ isset($searchParams['report_month_from']) ? $searchParams['report_month_from'] : '' }}"
                                        >
                                    </label>
                                    <label class="col-1 pt-2 text-center">〜</label>
                                    <label class="col-md-5">
                                        <input
                                            type="month"
                                            name="report_month_to"
                                            class="form-control"
                                            value="{{ isset($searchParams['report_month_to']) ? $searchParams['report_month_to'] : '' }}"
                                        >
                                    </label>
                                </div>
                            </div>
                        </div>
                        @if (!Gate::check('is_RP'))
                            <div class="col-md-4">
                                <x-forms.select 
                                    label="再資源化施設事業所名" 
                                    name="rp_office_code"
                                    :isSearch="true"
                                    :options="$lstOffice"
                                    :keySelected="isset($searchParams['rp_office_code']) ? $searchParams['rp_office_code'] : ''" 
                                />
                            </div>
                        @endif
                    </div>

                    <div class="text-right col-12">
                        <x-button label="検索" type="submit" class="btn-info" id="btn-search" />
                        <x-button.clear screen="rep010" />
                    </div>
                </div>
            </div>

            <div class="card card-user">
                <div class="card-header">
                    <div class="row">
                        <h5 class="card-title col-md-8">検索結果</h5>
                        <div class="text-right col-md-4">
                            <x-button label="CSV出力" name="btn_export_csv" type="submit" class="btn-success" />
                            <x-button label="データインポート" name="btn_import_excel" type="submit" class="btn-info" />
                            <input type="file" name="excel_import" class="none">
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive table-hover">
                        <table id="rep010-table" class="table text-nowrap custom-data-table">
                            <thead>
                                <tr>
                                    <th width="300px">再資源化施設事業所名</th>
                                    <th width="80px">年月</th>
                                    <th width="140px">再資源化処理前重量</th>
                                    <th width="140px">再資源化処理後重量</th>
                                    <th width="100px">再資源化率</th>
                                    <th width="120px">合計処理個数</th>
                                    <th width="100px">最大処理個数</th>
                                    <th width="100px">稼働率</th>
                                    <th width="120px">個当り重量</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recycleReports as $recycleReport)
                                    <tr>
                                        <td>{{ $recycleReport->office_name }}</td>
                                        <td>
                                            {{
                                                isset($recycleReport->report_month)
                                                    ? substr_replace($recycleReport->report_month, '/', 4, 0)
                                                    : ''
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                isset($recycleReport->weight_before)
                                                    ? number_format($recycleReport->weight_before, 2). ' kg'
                                                    : ''
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                isset($recycleReport->weight_after)
                                                    ? number_format($recycleReport->weight_after, 2). ' kg'
                                                    : ''
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                isset($recycleReport->recycle_rate)
                                                    ? number_format($recycleReport->recycle_rate, 2). ' %'
                                                    : ''
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                isset($recycleReport->total_process_qty)
                                                    ? number_format($recycleReport->total_process_qty)
                                                    : ''
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                isset($recycleReport->max_process_qty)
                                                    ? number_format($recycleReport->max_process_qty)
                                                    : ''
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                isset($recycleReport->operation_rate)
                                                    ? number_format($recycleReport->operation_rate, 2). ' %'
                                                    : ''
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                isset($recycleReport->weight_per_piece)
                                                    ? number_format($recycleReport->weight_per_piece, 2). ' g'
                                                    : ''
                                            }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $recycleReports->links('common.pagination') }}
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/rep/rep01/rep010.js') }}"></script>
@endpush