@extends('layouts.app')

@section('title', '引取報告用確認詳細')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header"></div>
                <form action="{{ route('case.handleCas101', ['id' => $case->case_id]) }}" method="POST" class="form-add-edit">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text 
                                    label="荷姿ID"
                                    name="case_id"
                                    :value="$case->case_id"
                                    :isLabel="true"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text 
                                    label="ケース番号"
                                    name="case_no"
                                    :value="$case->case_no"
                                    :isLabel="true"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text 
                                    label="伝票番号"
                                    name="slip_no"
                                    :value="$case->slip_no"
                                    :isLabel="true"
                                />
                            </div>
                        </div>
            
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-forms.label name="解体業者事業所名" :isRequired="false" />
                                    <div class="row">
                                        <div class="col-10 pr-1">
                                            <x-forms.text 
                                                :label="null"
                                                name="mstScrapper_office_name"
                                                :value="$case->mstScrapper_office_name"
                                                :isLabel="true"
                                                class="mb-0"
                                            />
                                        </div>
                                        <div class="col-1 pl-1">
                                            <x-html.link 
                                                :to="route('master.mst-011', ['id' => $case->mstScrapper_id])"
                                            >
                                                <x-slot name="icon">
                                                    <i class="nc-icon nc-alert-circle-i mt-2"></i>
                                                </x-slot>
                                            </x-html.link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-forms.label name="運搬NW業者事業所名" :isRequired="false" />
                                    <div class="row">
                                        <div class="col-10 pr-1">
                                            <x-forms.text 
                                                :label="null"
                                                name="mstOfficeTr_office_name"
                                                :value="$case->mstOfficeTr_office_name"
                                                :isLabel="true"
                                                class="mb-0"
                                            />
                                        </div>
                                        <div class="col-1 pl-1">
                                            <x-html.link 
                                                :to="route('master.mst-022', ['id' => $case->mstOfficeTr_id])"
                                            >
                                                <x-slot name="icon">
                                                    <i class="nc-icon nc-alert-circle-i mt-2"></i>
                                                </x-slot>
                                            </x-html.link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <hr>
                        <h5>車台情報</h5>
                        <div>
                            <strong style="font-size:16px">ケース内合計個数 {{ $case->sum_total_qty > 0 ? number_format($case->sum_total_qty) : '' }}</strong>
                        </div>
                    
                        <div class="table-responsive table-hover">
                            <table id="cas100-table" class="table text-nowrap custom-data-table">
                                <thead>
                                    <tr>
                                        <th width="250px">車台番号</th>
                                        <th width="100px">回収個数</th>
                                        <th width="100px">超過個数</th>
                                        <th>合計個数</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($case->car as $car)
                                        <tr>
                                            <td>
                                                <span class="fontbig">{{ $car->car_no }}</span>
                                            </td>
                                            <td>
                                                {{ !empty($car->qty) ? number_format($car->qty) : '' }}
                                            </td>
                                            <td>
                                                {{ !empty($car->exceed_qty) ? number_format($car->exceed_qty) : '' }}
                                            </td>
                                            <td>
                                                <span class="fontbig">
                                                    {{ $car->total_qty > 0 ? number_format($car->total_qty) : '' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right">
                            <x-button label="確認完了" type="submit" class="btn-info" />
                            <x-button label="戻る" href="{{ route('case.cas-100') }}" class="btn-warning" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
