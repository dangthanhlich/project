@extends('layouts.app')

@section('title', 'パレット・ケース紐付')

@section('content')
    <div class="content mt-70">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('palette.handlePal010') }}" method="POST" id="pal010-form">
                    @csrf
                    <div class="card card-user">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row">
                                        <label class="col-9 pr-1">
                                            <x-forms.text
                                                name="pallet_no"
                                                label="パレット番号"
                                                :value="isset($paramsSearch['pallet_no']) ? $paramsSearch['pallet_no'] : ''" 
                                            />
                                        </label>
                                        <div class="col-3 pl-1">
                                            <button type="submit" id="pallet-search" class="btn btn-info btn-round btn-mini btn-search">
                                                <i class="nc-icon nc-minimal-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <x-button id="nfc-pallet-btn" class="btn btn-info btn-round btn-mini nfc-btn" label="IC読み取り" />
                                        <x-button id="qr-pallet-btn" class="btn btn-info btn-round btn-mini qr-btn" label="QR読み取り" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (!empty($paramsSearch))
                        <div class="card card-user">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <label class="col-9 pr-1">
                                                <x-forms.text
                                                    name="case_no"
                                                    label="ケース番号"/>
                                            </label>
                                            <div class="col-3 pl-1">
                                                <button type="button" class="btn btn-info btn-round btn-mini btn-search" id="case-search">
                                                    <i class="nc-icon nc-minimal-down"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <x-button id="nfc-case-btn" class="btn btn-info btn-round btn-mini nfc-btn" label="IC読み取り" />
                                            <x-button id="qr-case-btn" class="btn btn-info btn-round btn-mini qr-btn" label="QR読み取り" />
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                            <span class="none" id="result"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pl-2 pr-2">
                                <table class="table text-nowrap case-table" id="tableSP1">
                                    <thead>
                                        <tr>
                                        <th width="150px">
                                            ケース番号
                                        </th>
                                        <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($dataDisplay['caseNo']))
                                            @foreach ($dataDisplay['caseNo'] as $data)
                                                <tr>
                                                    <td>
                                                        <span class="fontbig">
                                                            {{ $data['case_no'] }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <x-button 
                                                            data-id="{{ $data['case_id'] }}" 
                                                            class="btn-info btn-mini add-btn" 
                                                            label="紐付"/>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card card-user">
                            <div class="card-header">
                                <h5>紐付済ケース</h5>
                            </div>
                            <div class="card-body pl-2 pr-2">
                                <table class="table text-nowrap pallet-table" id="tableCheck">
                                    <thead>
                                        <tr>
                                        <th width="150px">
                                            ケース番号
                                        </th>
                                        <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($dataDisplay['palletNo']))
                                            @foreach ($dataDisplay['palletNo'] as $data)
                                                <tr>
                                                    <td>
                                                        <span class="fontbig">
                                                            {{ $data['case_no'] }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <x-button 
                                                            data-id="{{ $data['case_id'] }}" 
                                                            class="btn-danger btn-mini delete-btn" 
                                                            label="削除" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <div class="text-center">
                                    <x-button 
                                        id="btn-submit" 
                                        class="btn-info btn-long" 
                                        label="完了" />
                                </div>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="pallet-id" name="pallet_id" value="{{ !empty($dataDisplay['palletId']) ? $dataDisplay['palletId'] : null }}">
    <input type="hidden" id="total-case-constraint" name="total_case_constraint" value="{{ $dataDisplay['count'] }}">
    <div
        id="route"
        data-pal010="{{ route('palette.pal-010') }}"
        data-process-pallet-case="{{ route('palette.processPalletCase') }}"
    ></div>
    @include('screens.pal.pal01.qr-popup')
@endsection

@push('scripts')
    <script src="{{ mix('js/library/qrcode/qrcode.js') }}"></script>
    <script src="{{ mix('js/library/nfc/nfc.js') }}"></script>
    <script src="{{ mix('js/screens/pal/pal01/pal010.js') }}"></script>
@endpush

@push('style')
    <link href="{{ mix('css/screens/pal/pal01/pal010.css') }}" rel="stylesheet" />
@endpush