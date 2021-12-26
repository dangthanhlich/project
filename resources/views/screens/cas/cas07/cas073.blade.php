@extends('layouts.app')

@section('title', 'ケース情報修正')

@section('content')
    <div class="content mt-70">
        <div class="row">
        <div class="col-12">
            <div class="card card-user">
            <div class="card-header">
                <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                    <label>
                        ケース番号<span class="red">*</span>
                    </label>
                    <input type="tel" class="form-control" id="case_no" value="{{ $case->case_no }}">
                    <span class="error case-no-error"></span>
                    </div>
                </div>
                </div>
            </div>

            <div class="card-body pl-2 pr-2">
                <table class="table text-nowrap">
                <thead>
                    <tr>
                    <th width="130px">
                        車台番号
                        <div><span class="error car-no-error"></span></div>
                    </th>
                    </tr>
                </thead>
                <tbody id="list-car-no">
                    @foreach ($cars as $carKey => $car)
                    <tr>
                        <td>
                            <input type="tel" class="form-control car-id-number car-id-number-{{ $carKey }}" value="{{ $car->car_no }}" data-id="{{ $carKey }}" data-carid="{{ $car->car_id }}" />
                            <span class="error car-no-{{ $carKey }}-error"></span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
                <div class="row">
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-info btn-round add-car-no">車台追加</button>
                    <a href="#">
                    <button type="button" class="btn btn-info btn-round" id="btn-save-073" data-id='{{ $case->case_id }}'>保存</button>
                    </a>
                    <a href="#">
                    <button type="button" class="btn btn-warning btn-round back-cancel" data-url="{{ route('case.cas-071', ['caseNo' => $case->case_no]) }}">戻る</button>
                    </a>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
@endsection


@push('scripts')
  <script src="{{ mix('js/screens/cas/cas07/cas073.js') }}"></script>
@endpush

@push('style')
  <link href="{{ mix('css/screens/cas/cas07/cas071.css') }}" rel="stylesheet" />
@endpush