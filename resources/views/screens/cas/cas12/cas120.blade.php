@extends('layouts.app')

@section('title', 'RP検品ケース選択')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>ケース番号</label>
                                <div class="row">
                                    <label class="col-9 pr-1">
                                        <form id="form-search-case-no">
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="search_case_no"
                                                data-label="ケース番号"
                                            >
                                        </form>
                                    </label>
                                    <div class="col-3 pl-1">
                                        <button type="button" class="btn btn-info btn-round btn-mini" id="search-row">
                                            <i class="nc-icon nc-minimal-down"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button id="nfc-btn" type="button" class="btn btn-info btn-round btn-mini">
                                        IC読み取り
                                    </button>
                                    <button id="qr-btn" type="button" class="btn btn-info btn-round btn-mini">
                                        QR読み取り
                                    </button>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <span class="none" id="result">1 件中 1 件目</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-user">
                <div class="card-body pl-2 pr-2">
                    <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row my-2">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6 right">
                                <div class="dataTables_info">{{ count($cases) }} 件表示</div>
                            </div>
                        </div>
                        <div style="position: relative; overflow-x: hidden; width: 100%; max-height: 320px;">
                            <table class="table text-nowrap custom-data-table" id="tableData" style="table-layout:fixed;">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">ケース番号</th>
                                        <th style="width: 60%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cases as $case)
                                        <tr class="trow" data-case-no="{{ $case->case_no }}">
                                            <td class="fontbig">
                                                {{ $case->case_no }}
                                            </td>
                                            <td>
                                                <x-html.link
                                                    :to="route('case.cas-121', ['id' => $case->case_id])"
                                                    label="検品開始"
                                                    :isBtn="true"
                                                    class="btn-info btn-mini"
                                                />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="qr-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 100% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" style="text-align: center;">
                            <canvas id="canvas" style="position: absolute;"></canvas>
                            <video id="video" style="width: 100%;" autoplay></video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/library/qrcode/qrcode.js') }}"></script>
    <script src="{{ mix('js/library/nfc/nfc.js') }}"></script>
    <script src="{{ mix('js/screens/cas/cas12/cas120.js') }}"></script>
@endpush

@push('style')
    <link href="{{ mix('css/screens/cas/cas12/cas120.css') }}" rel="stylesheet" />
@endpush