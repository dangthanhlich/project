@extends('layouts.app')

@section('title', 'ケース確認')

@section('content')
    <div class="content mt-70">
        <div class="row">
            <div class="col-12">
                <form 
                    action="{{ route('case.handleCas061', ['id' => $dataDisplay['case_id'], 'flag' => $dataDisplay['flag']]) }}" 
                    method="POST" 
                    id="cas061-form"
                    class="form-add-edit"
                >
                    @csrf
                    <div class="card card-user">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4">
                                    <x-forms.text
                                        name="case_no"
                                        label="ケース番号"
                                        :value="old('case_no') !== null ? old('case_no') : $dataDisplay['case_no']"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="card-body pl-2 pr-2">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th width="100px">
                                            車台番号
                                        </th>
                                        <th width="50px"></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($dataDisplay['car']))
                                        @foreach ($dataDisplay['car'] as $data)
                                            <tr>
                                                <td>
                                                    <span class="fontbig">
                                                        {{ $data['car_no'] }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if (!is_null($data['mechanical_type']))
                                                        <span class="fontmini">機械式</span><br>
                                                        <img class="iconmini mb-1" src="{{ asset('images/icon/exclamation.svg') }}" alt="">
                                                    @endif
                                                </td>
                                                <td>
                                                    <x-button 
                                                        data-id="{{ $data['car_id'] }}" 
                                                        class="btn btn-danger btn-round btn-mini delete-btn not-tracking" 
                                                        label="削除" />
                                                    <x-button class="btn btn-info btn-round btn-mini rowCheck ok-btn not-tracking" label="OK" />
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <input type="hidden" id="cars-delete" name="cars_delete">
                                    <input type="hidden" id="scrapper-office-code" name="scrapper_office_code" value="{{ $dataDisplay['scrapper_office_code'] }}">
                                    <tr class="noneContent2 none">
                                        <td>
                                            <input type="text" name="car_no" data-label="車台番号（追加）" class="form-control ignore" id="car-no" disabled>
                                        </td>
                                        <td class="text-center"></td>
                                        <td id="add-car-no">
                                            <x-button id="delete-add-btn" class="btn btn-danger btn-round btn-mini rowDelete not-tracking" label="削除" />
                                            <x-button id="ok-btn" class="btn btn-info btn-round btn-mini rowCheck not-tracking" label="OK" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="button" id="camera-btn" class="btn btn-info btn-long btn-round buttonNext not-tracking">
                                        荷姿写真<i class="nc-icon nc-cloud-upload-94"></i>
                                    </button>
                                    <input data-label="荷札写真" type="hidden" id="case-picture-2" name="case_picture_2" value="{{ old('case_picture_2') !== null ? old('case_picture_2') : $dataDisplay['case_picture_2'] }}">
                                    <br>
                                    <div id="screenshotsContainer" class="noneContent none">
                                        <canvas id="canvas-popup" class="is-hidden style="position: absolute"></canvas>
                                        <img id="screenshot">
                                    </div>
                                    @if (!is_null($dataDisplay['case_picture_2']))
                                        <img id="img-old" src="{{ getS3FileUrl($dataDisplay['case_picture_2']) }}">
                                        <br>
                                    @endif
                                    <x-button id="add-car-btn" class="btn btn-info btn-round btn-long buttonNext2 not-tracking" label="車台追加" />
                                    <x-button id="submit-btn" type="submit" class="btn btn-info btn-round btn-long" label="保存" />
                                    <br>
                                    <x-button 
                                        id="cancel-case"
                                        class="btn btn-danger btn-round btn-long not-tracking" 
                                        label="取消" 
                                        data-id="{{ $dataDisplay['case_id'] . '_' . $dataDisplay['flag'] }}" 
                                        :disabled="$dataDisplay['case_status'] == getConstValue('Case.caseStatus.BEFORE_INSPECTION') ? false : true" 
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="camera-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div id="modal-content-camera" class="modal-content">
                                <div class="modal-header">
                                    <button type="button" id="close-popup" class="close not-tracking" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12" style="text-align: center;">
                                            <video autoplay id="video-popup" style="width: 100%;"></video>
                                            <div class="btn-camera-action">                                    
                                                <button type="button" class="btn btn-danger btn-round btn-mini not-tracking" id="btnScreenshot">
                                                    <i class="fa fa-camera" aria-hidden="true"></i>
                                                </button>
                                                <button type="button" class="btn btn-light btn-round btn-mini not-tracking" id="btnChangeCamera">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                                                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div
        id="route"
        data-cancel-case="{{ route('case.cas061CancelCase') }}"
        data-case060="{{ route('case.cas-060') }}"
    ></div>
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/cas/cas06/cas061.js') }}"></script>
@endpush

@push('style')
    <link href="{{ mix('css/screens/cas/cas06/cas061.css') }}" rel="stylesheet" />
@endpush
