@extends('layouts.app')

@section('title', '電子サイン')

@section('content')
<div class="content mt-70">
        <div class="row">
            <div class="col-12">
                <div class="card card-user">
                    <div class="card-header"></div>
                    <div class="card-body pl-2 pr-2">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th width="140px">
                                        ケース番号
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @if (!empty($dataSort))
                                @foreach ($dataSort as $data)
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
                        <div class="row">
                            <div class="col-12 text-center">
                                <div class="sign scrapper">
                                    <label class="beforeContent">サイン（解体業者）</label>
                                    <label class="noneContent none">サイン（指定引取場所）</label>
                                    <canvas id="drawcanvas" width="250px" height="400px" style="background-color: white;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-warning btn-round clearCanvas" data-inline="true">
                                    クリア
                                </button>
                                <button type="button" class="btn btn-info btn-round buttonNext beforeContent" id="buttonNext" data-inline="true">
                                    次へ
                                </button>
                                <button id="buttonSubmit" type="button" class="btn btn-info btn-round noneContent none">
                                    完了
                                </button>
                                <form id="cas062-form" action="{{ route('case.handleCas062', ['officeCode' => $officeCode]) }}" method="POST">
                                    @csrf
                                    <input
                                        id="sign-scrapper"
                                        type="hidden" 
                                        name="sign_scrapper"
                                        value=""
                                    />
                                    <input
                                        id="sign-sy"
                                        type="hidden" 
                                        name="sign_sy"
                                        value=""
                                    />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/cas/cas06/cas062.js') }}"></script>
@endpush

@push('style')
    <link href="{{ mix('css/screens/cas/cas06/cas062.css') }}" rel="stylesheet" />
@endpush