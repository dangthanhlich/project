@extends('layouts.app')

@section('title', 'ケース検品')

@section('content')
    <div class="content mt-70">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger validate-message none" role="alert"></div>                
                <div class="card card-user">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4">
                            <div class="form-group">
                                <label>ケース番号</label>
                                <br>
                                <span class="fontbig">{{ $case->case_no }}</span>
                                <input type='hidden' id="case-id-value" value="{{ $case->case_id }}" />
                            </div>
                            </div>
                            <div class="col-md-4">
                            <div class="form-group">
                                <label style="color:#ef8157;">超過個数: <span class="exceed-qty-flg-label">{{ $case->exceed_qty_flg_label }}</span></label>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pl-2 pr-2">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th width="70px">車台番号</th>
                                    <th width="40px"></th>
                                    <th width="60px">回収個数</th>
                                    <th>写真</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cars as $keyOfCar => $car)
                                    <tr>
                                        <td style="text-overflow: unset;">
                                            <span class="fontbig">{{ $car->car_no_sub }}</span>
                                        </td>
                                        <td class="text-center" style="text-overflow: unset;">
                                            <span style="font-size: 11px;">機械式</span><br>
                                            @if ($car->mechanical_type) <img class="iconmini mb-1" src="{{ asset('images/icon/exclamation.svg') }}"> @endif
                                        </td>
                                        <td>
                                            <input type="text" class="form-control photo-number" value="{{ $car->qty }}" data-id="{{ $car->car_id }}" data-qtykey="{{ $keyOfCar }}" />
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-round btn-mini btn-upload-photo-car" data-id="{{ $car->car_id }}" data-keyofcar="{{ $keyOfCar }}">
                                                <i class="nc-icon nc-camera-compact"></i>
                                            </button>
                                            <span class="remove-picture {{ $car->car_picture ? '' : 'none' }}">
                                                <img height="40" id="car-picture-id-{{ $car->car_id }}" class="" src="{{ $car->car_picture ? getS3FileUrl($car->car_picture) : null }}" needupload="{{ $car->car_picture ? 1 : null }}" />
                                                {{-- <i class="fa fa-times remove-car-picture" data-id="{{ $car->car_id }}" aria-hidden="true"></i> --}}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="display: none; padding: 0px; border: none;">
                                            <div class="error car-qty-error car-qty-{{ $keyOfCar }}-error" style="display: none;"></div>
                                            <div class="error car-photo-error car-photo-{{ $keyOfCar }}-error" style="display: none;"></div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <hr>

                        <div class="row">
                            <div class="col-md-4">
                            <div class="form-group">
                                <label>合計個数</label>
                                <br>
                                <span class="fontbig"><span class="total-photo-of-cars"></span> 個</span>
                            </div>
                            </div>
                            <div class="col-md-4">
                            <div class="form-group">
                                <label>未合致</label>
                                <div class="check">
                                    <label>
                                        <input type="radio" class="minimal-blue is-mismatch" name="r1" id="radio1" value="1" {{ $mismatchs->count() > 0 ? 'checked' : '' }} /><span>あり</span>
                                    </label>
                                    <label>
                                        <input type="radio" class="minimal-blue is-mismatch" name="r1" id="radio2" value="2" /><span>なし</span>
                                    </label>
                                </div>
                                <span class="error is_mismatch_error"></span>
                            </div>
                            </div>
                        </div>

                        <div class="row content1 {{ $mismatchs->count() === 0 ? 'none' : '' }}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>短絡不良数量</label>
                                    <input type="text" class="form-control mismatch-type-input mismatch-type-number-0" id="mismatch_type_1" data-number='0' data-id="{{ getMismatchByType($mismatchs, 1)->id ?? null }}" value="{{ getMismatchByType($mismatchs, 1)->mismatch_qty ?? null }}" />
                                    <span class="error error-type-0"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>過分解数量</label>
                                    <input type="text" class="form-control mismatch-type-input mismatch-type-number-1" id="mismatch_type_2" data-number='1' data-id="{{ getMismatchByType($mismatchs, 2)->id ?? null }}" value="{{ getMismatchByType($mismatchs, 2)->mismatch_qty ?? null }}" />
                                    <span class="error error-type-1"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>付属品数量</label>
                                    <input type="text" class="form-control mismatch-type-input mismatch-type-number-2" id="mismatch_type_3" data-number='2' data-id="{{ getMismatchByType($mismatchs, 3)->id ?? null }}" value="{{ getMismatchByType($mismatchs, 3)->mismatch_qty ?? null }}" />
                                    <span class="error error-type-2"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>M式未ロック数量</label>
                                    <input type="text" class="form-control mismatch-type-input mismatch-type-number-3" id="mismatch_type_4" data-number='3' data-id="{{ getMismatchByType($mismatchs, 4)->id ?? null }}" value="{{ getMismatchByType($mismatchs, 4)->mismatch_qty ?? null }}" />
                                    <span class="error error-type-3"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>M式未収納数量</label>
                                    <input type="text" class="form-control mismatch-type-input mismatch-type-number-4" id="mismatch_type_5" data-number='4' data-id="{{ getMismatchByType($mismatchs, 5)->id ?? null }}" value="{{ getMismatchByType($mismatchs, 5)->mismatch_qty ?? null }}" />
                                    <span class="error error-type-4"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>その他数量</label>
                                    <input type="text" class="form-control mismatch-type-input mismatch-type-number-5" id="mismatch_type_6" data-number='5' data-id="{{ getMismatchByType($mismatchs, 6)->id ?? null }}" value="{{ getMismatchByType($mismatchs, 6)->mismatch_qty ?? null }}" />
                                    <span class="error error-type-5"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-center">
                            <a href="#" id="btn-save-on-071" >
                                <button type="button" class="btn btn-info btn-round btn-long">確認</button>
                            </a>
                            <br>
                            <a href="{{ route('case.cas-073', ['caseNo' => $case->case_no]) }}">
                                <button type="button" class="btn btn-info btn-round btn-long">情報修正</button>
                            </a>
                            <br>
                            <a href="#">
                                <button type="button" class="btn btn-warning btn-round btn-long btn-set-case-status">問い合わせ</button>
                            </a>
                            <br>
                            <button type="button" class="btn btn-danger btn-round btn-long" data-toggle="modal" data-target="#modalreturn">返品</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('screens.cas.cas07.popup')
@endsection

@push('scripts')
  <script src="{{ mix('js/library/qrcode/qrcode.js') }}"></script>
  <script src="{{ mix('js/library/nfc/nfc.js') }}"></script>
  <script src="{{ mix('js/screens/cas/cas07/cas071.js') }}"></script>
@endpush

@push('style')
  <link href="{{ mix('css/screens/cas/cas07/cas071.css') }}" rel="stylesheet" />
@endpush