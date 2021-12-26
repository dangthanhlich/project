@extends('layouts.app')

@section('title', 'RPケース検品')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>ケース番号</label>
                                <br>
                                <span class="fontbig">{{ $case->case_no }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <form
                    action="{{ route('case.handleCas121', ['id' => $case->case_id]) }}" 
                    method="POST"
                    id="cas121-form"
                    class="form-add-edit"
                >
                    @csrf
                    <div class="card-body pl-2 pr-2">
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text 
                                    label="合計個数（非該当品を除く）"
                                    name="actual_qty_rp"
                                    :value="$case->actual_qty_rp"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.radio
                                    label="未合致是正可能品"
                                    name="allow_flg"
                                    :options="getList('Common.allowFlg')"
                                />
                            </div>
                        </div>
                        <div class="row group-content d-none">
                            <div class="col-md-4">
                                <x-forms.text 
                                    label="短絡不良数量"
                                    name="mismatch_qty_1"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text 
                                    label="M式未ロック数量"
                                    name="mismatch_qty_2"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text 
                                    label="M式未収納数量"
                                    name="mismatch_qty_3"
                                />
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>未合致品写真</label>
                                    <button type="button" id="camera-btn" class="btn btn-info btn-round btn-mini buttonNext not-tracking">
                                        <i class="nc-icon nc-cloud-upload-94"></i>
                                    </button>
                                    <input type="hidden" id="case-picture-4" name="case_picture_4" value="{{ old('case_picture_4') !== null ? old('case_picture_4') : $case->case_picture_4 }}">
                                    <br>
                                    <div id="screenshotsContainer" class="noneContent none">
                                        <canvas id="canvas-popup" class="d-none" style="position: absolute"></canvas>
                                        <img id="screenshot">
                                    </div>
                                    @if (!is_null($case['case_picture_4']))
                                        <img id="img-old" src="{{ getS3FileUrl($case['case_picture_4']) }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-center">
                                <x-button id="btn-completed" type="submit" class="btn-info btn-long" label="確認完了" />
                            </div>
                        </div>
                    </div>
                </form>
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
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/cas/cas12/cas121.js') }}"></script>
@endpush