@extends('layouts.app')

@section('title', 'パレット詳細')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header"></div>

                <div class="card-body">
                    <form>
                        <h5>パレット情報</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text label="パレット番号"
                                          name="pallet_no"
                                          :value="$pallet->pallet_no"
                                          isLabel="true"/>
                            </div>
                            <div class="col-md-4">
                                <x-forms.select label="パレットステータス"
                                          name="pallet_status"
                                          :options="getList('Pallet.palletStatus')"
                                          :keySelected="$pallet->pallet_status"
                                          isLabel="true"/>
                            </div>
                        </div>

                        <hr>
                        <h5>集荷情報</h5>
                        <div class="row">

                            <div class="col-md-4">
                                <x-forms.text label="引渡日時"
                                          name="deliver_complete_time"
                                          :value="!empty($pallet->palletTransport) ? $pallet->palletTransport->deliver_complete_time : ''"
                                          isLabel="true"/>
                            </div>
                            <div class="col-md-4">
                                <x-forms.text label="使用車両番号"
                                          name="deliver_complete_time"
                                          :value="!empty($pallet->palletTransport) ? $pallet->palletTransport->car_no : ''"
                                          isLabel="true"/>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>車両写真</label>
                                    <img src="{{ !empty($pallet->palletTransport) ? getS3FileUrl($pallet->palletTransport->car_no_picture_1) : '' }}">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5>ケース情報</h5>
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                <tr>
                                    <th width="60px">
                                        No
                                    </th>
                                    <th width="100px">
                                        ケース番号
                                    </th>
                                    <th>
                                        引取報告個数
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($pallet->cases as $key => $case)
                                <tr>
                                    <td>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        {{ $case->case_no }}
                                    </td>
                                    <td>
                                        {{ $case->actual_qty_sy }}
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <label>紐付けケース数: {{ $pallet->cases->count() }} </label>
                        </div>

                        <div class="text-right">
                            <x-button.back href="{{ route('palette.pal-020') }}" label="戻る" />  
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
