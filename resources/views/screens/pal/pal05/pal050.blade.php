@extends('layouts.app')

@section('title', '運搬記録')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-12">
            <form action="{{ route('palette.handlePal050') }}" method="POST" id="case050-form">
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
                                <div id="row-receive-plan-date" class="row">
                                    <div class="col-md-5">
                                        <x-forms.date label="集荷日" name="deliver_complete_time_from" :value="$params['deliver_complete_time_from']" />
                                    </div>
                                    <span class="col-1 text-center" style="padding: 35px 0;">〜</span>
                                    <div class="col-md-5" style="margin-top:7px">
                                        <x-forms.date label="" name="deliver_complete_time_to" :value="$params['deliver_complete_time_to']"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right col-12">
                            <x-button type="submit" class="btn-info" id="btn-search" label="検索" />
                            <x-button.clear screen="cas040" />
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
                        <div class="table-responsive table-hover">
                            <table class="table text-nowrap custom-data-table">
                                <thead>
                                    <tr>
                                        <th width="80px">
                                            集荷日
                                        </th>
                                        <th width="200px">
                                            指定引取場所事業所名
                                        </th>
                                       <th width="80px">
                                            パレット数
                                        </th>
                                        <th width="60px">
                                            ケース数
                                        </th>
                                      <th width="80px">
                                            引渡日
                                        </th>
                                       <th width="120px">
                                            使用車両番号
                                        </th>
                                        <th width="200px">
                                            再資源化施設事業所名
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                    <?php $totalCase = 0; ?>
                                    <?php
                                    foreach ($item->pallets as $pallet) {
                                        $totalCase += $pallet->cases->count();
                                    } ?>
                                    <tr>
                                        <td>{{ $item->deliver_complete_time?date('Y/m/d', strtotime($item->deliver_complete_time)):'' }}</td>
                                        <td>{{ isset($item->pallets->last()->palletMstOffice)?$item->pallets->last()->palletMstOffice->office_name:'' }}</td>
                                        <td>@if(isset($item->pallets))<a href="{{route('palette.pal-051',$item->pallet_transport_id)}}">{{ $item->pallets->count() }} @endif</a></td>
                                        <td>{{ $totalCase }}</td>
                                        <td>{{ isset($item->pallets->last()->receive_complete_time)?date('Y/m/d', strtotime($item->pallets->last()->receive_complete_time)):'' }}</td>
                                        <td>{{ $item->car_no }}</td>
                                        <td>{{ $palTransport->getMstOfficeByRpOfficeCode($item->rp_office_code) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{ $items->links('common.pagination') }}
                </div>
            </form>
        </div>
    </div>
</div>


@endsection