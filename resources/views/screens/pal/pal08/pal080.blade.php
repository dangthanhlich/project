@extends('layouts.app')

@section('title', 'パレット一覧')

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
                    <form action="{{ route('palette.handlePal080') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.checkbox label="ステータス" name="pallet_status" :options="getList('Pallet.palletStatus')" :valueChecked="isset($params['pallet_status']) ? $params['pallet_status'] : []" />
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-md-5">
                                            <x-forms.date label="受入日" name="receive_complete_time_from" :value="isset($params['receive_complete_time_from']) ? $params['receive_complete_time_from'] : ''" />
                                        </div>
                                        <span class="col-1 text-center" style="padding: 35px 0;">〜</span>
                                        <div class="col-md-5" style="margin-top:7px">
                                            <x-forms.date label="" name="receive_complete_time_to" :value="isset($params['receive_complete_time_to']) ? $params['receive_complete_time_to'] : ''" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text name="pallet_no" label="パレット番号" :value="isset($params['pallet_no']) ? $params['pallet_no'] : ''" />
                            </div>
                            <div class="col-md-4">
                                <x-forms.select isSearch="true" label="指定引取場所事業所名" :options="$mstOfficeList" name="office_name" :keySelected="isset($params['office_name']) ? $params['office_name'] : ''" :dataDefault="[]" />
                            </div>
                        </div>
                        <div class="text-right col-12">
                            <x-button type="submit" class="btn-info" id="btn-search" label="検索" />
                            <x-button.clear screen="pal080" />
                        </div>
                    </form>
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
                        <table class="table text-nowrap custom-data-table">
                            <thead>
                                <tr>
                                    <th width="100px">
                                        パレット番号
                                    </th>
                                    <th width="100px">
                                        ステータス
                                    </th>
                                    <th width="120px">
                                        受入日
                                    </th>
                                    <th width="120px">
                                        使用車両番号
                                    </th>
                                    <th>
                                        指定引取場所事業所名
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pallets as $pallet)
                                <tr>
                                    <td>
                                        <a href="{{route('palette.pal-081',$pallet->pallet_id)}}">{{$pallet->pallet_no}}</a>
                                    </td>
                                    <td>
                                        {{$pallet->pallet_status?getList('Pallet.palletStatus')[$pallet->pallet_status]:''}}
                                    </td>
                                    <td>
                                        {{$pallet->receive_complete_time?date('Y/m/d', strtotime($pallet->receive_complete_time)):''}}
                                    </td>
                                    <td>
                                       {{isset($pallet->palletTransport->car_no)?$pallet->palletTransport->car_no:''}}
                                    </td>
                                    <td>
                                        {{isset($pallet->palletMstOffice->office_name)?$pallet->palletMstOffice->office_name:''}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $pallets->links('common.pagination') }}
            </div>
        </div>
    </div>
</div>
@endsection