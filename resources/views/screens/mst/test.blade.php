@extends('layouts.app')

@section('title', 'test')

@section('content')
<div>
    <p id="code"></p>
</div>

<div style="text-align: center;">
    <canvas id="canvas" width="640" height="480" style="position: absolute; border: 4px solid #444444"></canvas>
    <video id="video" width="640" height="480" autoplay></video>
</div>


<div id="template" class="card-header">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>事業所コード</label>
                <br>
                <span class="fontbig">107535800104</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>管理番号</label>
                <br>
                <span class="fontbig">202105151403125</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>ケース数</label>
                <br>
                <span class="fontbig">80</span>
            </div>
        </div>
        <div class="col-12 text-center">
            <img src="/image/qrcode.png"
                    width="100px" height="100px">
            <br>
            <span class="fontbig">http://jarp.org/xxx</span>
        </div>
    </div>
</div>

<div class="card-body pl-2 pr-2">
    <div class="row">
        <div class="col-12 text-center">
            <button onclick="printPDF()" type="button" class="btn btn-info btn-round btn-long">印刷
            </button>
            <br>
            <a href="./APP-020.html">
                <button type="button" class="btn btn-secondary btn-round btn-long">完了</button>
            </a>
        </div>
    </div>
</div>


<h2>NFC</h2>
<button id="scanButton">Scan</button>
<button id="writeButton">Write</button>
@endsection

@push('scripts')
    <script src="{{ mix('js/library/qrcode/qrcode.js') }}"></script>

    <script src="{{ asset('js/library/html2pdf.bundle.min.js') }}"></script>
    <script src="{{ mix('js/library/printer/printer.js') }}"></script>

    <script src={{ mix('js/library/nfc/nfc.js') }}></script>

    {{-- <script src="{{ mix('js/library/signature_pad/signature_pad.min.js') }}"></script> --}}

    <script src="{{ mix('js/screens/mst/mst03/test.js') }}"></script>
@endpush
