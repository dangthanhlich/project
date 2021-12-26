@extends('layouts.app')

@section('title', '単価一覧')

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
                <form method="POST" action="{{ route('master.handleMst040') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <x-forms.select
                                isSearch="true"
                                label="指定引取場所事業所名"
                                :options="$mstOfficeList"
                                name="office_code"
                                :keySelected="isset($paramsSearch['office_code']) ? $paramsSearch['office_code'] : ''"
                            />
                        </div>
                        <div class="col-md-4">
                            <x-forms.checkbox
                                label="元払着払区分"
                                :options="getList('MstPrice.priceType')"
                                name="price_type"
                                :valueChecked="isset($paramsSearch['price_type']) ? $paramsSearch['price_type'] : []"
                            />
                        </div>
                        <div class="col-md-4">
                            <x-forms.area
                                isSearch="true"
                                label="運搬地域コード"
                                :options="getList('Common.area')"
                                name="area_code"
                                showAreaCode="true"
                                :keySelected="isset($paramsSearch['area_code']) ? $paramsSearch['area_code'] : ''"
                            />
                        </div>

                    </div>
                    <div class="text-right col-12">
                        <x-button label="検索" type="submit" class="btn-info" />
                        <x-button.clear screen="mst040" />
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
                    <table class="table text-nowrap custom-data-table" id="mst040-table">
                        <thead>
                            <tr>
                                <th width="114px">事業所コード</th>
                                <th width="314px">事業所名</th>
                                <th width="114px">元払着払区分</th>
                                <th width="134px">運搬地域コード</th>
                                <th width="114px">単価</th>
                                <th width="114px">開始年月日</th>
                                <th>終了年月日</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($mstPrices as $mstPrice)
                            <tr>
                                <td>
                                    {{ $mstPrice->sy_office_code }}
                                </td>
                                <td>
                                    {{ !empty($mstPrice->mst_office) ? $mstPrice->mst_office->office_name : '' }}
                                </td>
                                <td>
                                    {{ valueToText($mstPrice->price_type, 'MstPrice.priceType') }}
                                </td>
                                <td>
                                    {{ $mstPrice->region_code }} - {{ getAreaName($mstPrice->region_code) }}
                                </td>
                                <td>
                                    {{ number_format($mstPrice->unit_price) }}
                                </td>
                                <td>
                                    {{ $mstPrice->effective_start_date }}
                                </td>
                                <td>
                                    {{ $mstPrice->effective_end_date }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $mstPrices->links('common.pagination') }}
        </div>
    </div>
</div>
@endsection