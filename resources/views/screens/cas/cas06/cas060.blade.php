@extends('layouts.app')

@section('title', '受入ケース選択')

@section('content')
    <div class="content mt-70">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('case.handleCas060') }}" method="POST" id="cas060-form">
                    @csrf
                    <div class="card card-user">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                <div class="form-group">
                                    <x-forms.select 
                                        label="解体業者事業所名" 
                                        name="office_code"
                                        :isSearch="true"
                                        :options="$lstOffice"
                                        :keySelected="isset($paramsSearch['office_code']) ? $paramsSearch['office_code'] : ''"
                                        onChange="this.form.submit()"/>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (!is_null($mstScrapperObj))
                        <div class="card card-user">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="col-9 pr-1">
                                                    <x-forms.text
                                                        name="case_no"
                                                        label="ケース番号"/>
                                                </label>
                                                <div class="col-3 pl-1">
                                                    <button type="button" class="btn btn-info btn-round btn-mini btn-search-060" id="search060">
                                                        <i class="nc-icon nc-minimal-down"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <x-button id="nfc-btn" class="btn btn-info btn-round btn-mini" label="IC読み取り" />
                                                <x-button id="qr-btn" class="btn btn-info btn-round btn-mini" label="QR読み取り" />
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                <span class="none" id="result"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body pl-2 pr-2">
                                <table class="table text-nowrap case-table" id="tableSP1">
                                    <thead>
                                        <tr>
                                        <th width="170px">
                                            ケース番号
                                        </th>
                                        <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($dataSearch))
                                            @foreach ($dataSearch as $data)
                                                <tr>
                                                    <td>
                                                        <span class="fontbig">
                                                            {{ $data['no'] }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <x-button 
                                                            label="確認" 
                                                            class="btn btn-info btn-round btn-mini" 
                                                            href="{{ route('case.cas-061', [$data['id'], $data['flag']]) }}" 
                                                        />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <br>
                                <div class="text-center">
                                    <x-button 
                                        label="サイン" 
                                        class="btn btn-info btn-round btn-long" 
                                        :disabled="$disabled" 
                                        href="{{ route('case.cas-062', ['officeCode' => $mstScrapperObj->office_code]) }}"
                                    />
                                    <button 
                                        type="button"
                                        class="btn btn-info btn-round btn-long"
                                        data-id="{{ $mstScrapperObj->id }}"
                                        id="addTempCase"
                                        data-toggle="modal" 
                                        data-backdrop="static"
                                        data-target="#modaladdcase">
                                        ケース追加
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div id="qr-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div id="modal-content-qr" class="modal-content">
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
    @include('screens.cas.cas06.popup')
@endsection

@push('scripts')
    <script src="{{ mix('js/library/qrcode/qrcode.js') }}"></script>
    <script src="{{ mix('js/library/nfc/nfc.js') }}"></script>
    <script src="{{ mix('js/screens/cas/cas06/cas060.js') }}"></script>
@endpush

@push('style')
    <link href="{{ mix('css/screens/cas/cas06/cas060.css') }}" rel="stylesheet" />
@endpush