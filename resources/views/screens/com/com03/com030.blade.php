@extends('layouts.app')

@section('title', '業務選択')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header"></div>

                <div class="card-body">

                    @if (auth()->user()->sy_office_flg === getConstValue('MstUser.syOfficeFlg.WITH_AUTHORITY'))
                    <div class="row SYcontent">
                        <div class="col-12 text-center">
                            <x-button
                                label="運搬NW/自社ケース受入"
                                class="btn-info btn-long"
                                href="{{ route('case.cas-050') }}"
                            />

                            <x-button
                                label="解体業者持込ケース受入"
                                class="btn-info btn-long"
                                href="{{ route('case.cas-060') }}"
                            />

                            <x-button
                                label="SYケース検品"
                                class="btn-info btn-long"
                                href="{{ route('case.cas-070') }}"
                            />

                            <x-button
                                label="パレット・ケース紐付"
                                class="btn-info btn-long"
                                href="{{ route('palette.pal-010') }}"
                            />

                            <x-button
                                label="パレット引渡"
                                class="btn-info btn-long"
                                href="{{ route('palette.pal-030') }}"
                            />
                        </div>
                    </div>
                    @endif

                    @if (auth()->user()->rp_office_flg === getConstValue('MstUser.rpOfficeFlg.WITH_AUTHORITY'))
                    <div class="row RPcontent">
                        <div class="col-12 text-center">
                            <x-button
                                label="パレット受入"
                                class="btn-info btn-long"
                                href="{{ route('palette.pal-060') }}"
                            />

                            <x-button
                                label="RPケース検品"
                                class="btn-info btn-long"
                                href="{{ route('case.cas-120') }}"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-center">
                            <x-button
                                label="ケース工程確認"
                                class="btn-info btn-long"
                                href="{{ route('common.com-040') }}"
                            />
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
