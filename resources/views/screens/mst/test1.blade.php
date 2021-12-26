@extends('layouts.app')

@section('title', '電子サイン')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-user">
            <div class="card-header"></div>

            <div class="card-body pl-2 pr-2">
            <table class="table text-nowrap">
                <thead>
                <tr>
                    <th>
                    ケース番号
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                    <span class="fontbig">
                        182 2001
                    </span>
                    </td>
                </tr>
                <tr>
                    <td>
                    <span class="fontbig">
                        161 0012
                    </span>
                    </td>
                </tr>
                <tr>
                    <td>
                    <span class="fontbig">
                        180 0801
                    </span>
                    </td>
                </tr>
                <tr>
                    <td>
                    <span class="fontbig">
                        171 1234
                    </span>
                    </td>
                </tr>
                <tr>
                    <td>
                    <span class="fontbig">
                        200 0021
                    </span>
                    </td>
                </tr>
                <tr>
                    <td>
                    <span class="fontbig">
                        150 1234
                    </span>
                    </td>
                </tr>
                <tr>
                    <td>
                    <span class="fontbig">
                        170 0310
                    </span>
                    </td>
                </tr>
                <tr>
                    <td>
                    <span class="fontbig">
                        150 1001
                    </span>
                    </td>
                </tr>
                </tbody>
            </table>

            <hr>
            <div class="row">
                <div class="col-12 text-center">
                <div class="sign derivery">
                    <label class="beforeContent">サイン（運搬NW）</label>
                    <label class="noneContent none">サイン（指定引取場所）</label>
                    <canvas id="sign" width="250" height="400" style="background-color: white;"></canvas>
                    {{-- <canvas width="250px" height="400px" id="sign" style="background-color: white;"></canvas> --}}
                    {{-- <canvas width="250px" height="400px" id="sign2" style="background-color: white;"></canvas> --}}
                    {{-- <canvas id="drawcanvas" width="250px" height="400px" style="background-color: white;"></canvas> --}}
                </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-info" id="checkButton">データチェック</button>
                    <button type="button" class="btn btn-active" id="clearButton">クリア</button>
                    <button type="button" class="btn btn-primary" id="saveButton">保存</button>
                    {{-- <button type="button" class="btn btn-warning btn-round" id="clearCanvas" data-inline="true">
                        クリア
                    </button>
                    <button type="button" class="btn btn-info btn-round buttonNext beforeContent" id="clearCanvas2" data-inline="true">
                        次へ
                    </button>
                    <a href="../common/COM-030.html" class="noneContent none">
                        <button type="button" class="btn btn-info btn-round">
                            完了
                        </button>
                    </a> --}}
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- <script src="{{ mix('js/library/signature_pad/signature_pad.min.js') }}"></script> --}}
    {{-- <script src="{{ mix('js/library/signature_pad/signature.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>

    <script src="{{ mix('js/screens/mst/mst03/test1.js') }}"></script>
@endpush
