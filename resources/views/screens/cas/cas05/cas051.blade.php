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
                            @foreach ($cases as $case)
                                <tr>
                                    <td>
                                    <span class="fontbig">
                                        {{ $case->case_no }}
                                    </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr>
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="sign derivery">
                                <label class="beforeContent">サイン（運搬NW）</label>
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
                            <button id="buttonNext" type="button" class="btn btn-info btn-round beforeContent buttonNext" data-inline="true" disabled="disabled">
                                次へ
                            </button>
                            <button id="buttonSubmit" type="button" class="btn btn-info btn-round noneContent none" disabled="disabled">
                                完了
                            </button>
                            <form id="cas051-form" action="{{ route('case.handleCas051', ['officeCode' => $officeCode]) }}" method="POST">
                                @csrf
                                <input
                                    id="sign_tr_2"
                                    type="hidden" 
                                    name="sign_tr_2"
                                    value=""
                                />
                                <input
                                    id="sign_sy"
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
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/cas/cas05/cas051.js') }}"></script>
@endpush
