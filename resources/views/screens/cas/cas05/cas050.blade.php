@extends('layouts.app')

@section('title', '受入ケース選択')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header"></div>
                <div class="card-body">
                    <form id="form-select-mst-office">
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.select
                                    label="運搬NW業者事業所名"
                                    name="office_code"
                                    :isSearch="true"
                                    :options="$lstOfficeAssociatedScrapper"
                                    :keySelected="isset($paramsSearch['office_code']) ? $paramsSearch['office_code'] : ''"
                                />
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div id="search-card" class="card card-user none">
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

                <div class="card-body pl-2 pr-2">
                    <table class="table text-nowrap" id="tableData" style="table-layout:fixed;">
                        <thead>
                            <tr>
                                <th style="width: 40%">ケース番号</th>
                                <th style="width: 60%">確認</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <br>
                    <label id="count-case-status"></label>
                    <div class="text-center">
                        <x-button id="case050-submit" label="サイン" class="btn-info btn-long" />
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
    <div
        id="route"
        class="none"
        data-search-by-office-code="{{ route('case.cas050SearchOfficeCode') }}"
        data-update-case-status="{{ route('case.cas050UpdateCaseStatus' )}}"
        data-case051="{{ route('case.cas-051', ['officeCode' => '00000']) }}"
        data-com030="{{ route('common.com-030') }}"
    ></div>
    <div
        id="data-parser"
        class="none"
        data-user-company-code="{{ isset($userCompanyCode) ? $userCompanyCode : '' }}"
        data-invalid-case-no-message="{{ getMessage('e-011') }}"
    ></div>
@endsection

@push('scripts')
    <script src="{{ mix('js/library/qrcode/qrcode.js') }}"></script>
    <script src="{{ mix('js/library/nfc/nfc.js') }}"></script>
    <script src="{{ mix('js/screens/cas/cas05/cas050.js') }}"></script>
@endpush

@push('style')
    <link href="{{ mix('css/screens/cas/cas05/cas050.css') }}" rel="stylesheet" />
@endpush
