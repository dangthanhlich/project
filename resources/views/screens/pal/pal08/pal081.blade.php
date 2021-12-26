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
                            <div class="form-group">
                                <label>パレット番号</label>
                                <input type="text" class="form-control" value="{{$pallet->pallet_no}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>パレットステータス</label>
                                <div>
                                    <select class="form-control" disabled>
                                        <option selected>{{$pallet->pallet_status?getList('Pallet.palletStatus')[$pallet->pallet_status]:''}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>指定引取場所事業所名</label>
                                <div class="row">
                                    <label class="col-10 pr-1">
                                        <input type="text" class="form-control" value="{{$pallet->palletMstOffice->office_name}}" disabled>
                                    </label>
                                    <div class="col-1 pl-1">
                                        <a href="{{ route('master.mst-022', ['id' => $pallet->palletMstOffice->id]) }}">
                                            <i class="nc-icon nc-alert-circle-i mt-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5>集荷情報</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>集荷日時</label>
                                <input type="text" class="form-control" value="{{$pallet->palletTransport->deliver_complete_time?date('Y/m/d H:i',strtotime($pallet->palletTransport->deliver_complete_time)):''}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>車両番号</label>
                                <input type="text" class="form-control" value="{{$pallet->palletTransport->car_no}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>車両写真</label>
                                <img src="{{$pallet->palletTransport->car_no_picture_1?getS3FileUrl($pallet->palletTransport->car_no_picture_1):''}}">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5>受入情報</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>受入日時</label>
                                <input type="text" class="form-control" value="{{$pallet->receive_complete_time?date('Y/m/d H:i',strtotime($pallet->receive_complete_time)):''}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>車両番号</label>
                                <input type="text" class="form-control" value="{{$pallet->palletTransport->car_no}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>車両写真</label>
                                <img src="{{$pallet->palletTransport->car_no_picture_2?getS3FileUrl($pallet->palletTransport->car_no_picture_2):''}}">
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
                                    <th width="80px">
                                        回収個数
                                    </th>
                                    <th width="100px">
                                        短格不良数量
                                    </th>
                                    <th width="120px">
                                        M式未ロック数量
                                    </th>
                                    <th>
                                        M式容器未収納数量
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pallet->cases as $index => $case)
                                <tr>
                                    <td>
                                        {{$index +1}}
                                    </td>
                                    <td>
                                        {{$case->case_no}}
                                    </td>
                                    <td>
                                        {{$case->actual_qty_rp}}
                                    </td>
                                    <td>
                                        {{getMismatchByType($case->mismatch, 1)->mismatch_qty ?? null }}
                                    </td>
                                    <td>{{getMismatchByType($case->mismatch, 4)->mismatch_qty ?? null }}</td>
                                    <td>{{getMismatchByType($case->mismatch, 5)->mismatch_qty ?? null }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr>
                    <h5>未合致是正可能報告写真</h5>
                    <div class="row">
                        @foreach($pallet->cases as $case)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{$case->case_no}}</label>
                                <img src="{{$case->case_picture_4?getS3FileUrl($case->case_picture_4):''}}">
                            </div>
                        </div>
                        @endforeach
                    
                    </div>

                    <div class="text-right">
                        <a href="{{route('palette.pal-080')}}">
                            <button type="button" class="btn btn-warning btn-round">戻る</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        </div>
     
    </div>
</div>
@endsection