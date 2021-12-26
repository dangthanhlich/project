@extends('layouts.app')

@section('title', '個数再確認詳細')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header"></div>
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
                            <x-forms.text 
                                label="検品日時"
                                name="inspect_complete_time"
                                :value="$case->inspect_complete_time"
                                :isLabel="true"
                            />
                        </div>
                        <div class="col-md-4">
                            <x-forms.text 
                                label="検品ユーザー"
                                name="inspect_user_id"
                                :value="$case->inspect_user_id"
                                :isLabel="true"
                            />
                        </div>
                    </div>

                    <hr>
                    <h5>検品写真</h5>
                    <div id="car-list" class="row">
                        @foreach ($case->car as $car)
                            <div class="car col-md-4">
                                <div class="form-group" style="min-height: 280px;">
                                    <label>{{ $car->car_no }}</label>
                                    <br>
                                    <label>回収個数: {{ $car->qty }}</label>
                                    <button type="button" class="btn btn-info btn-round btn-mini rowCheck">OK</button>
                                    <img src="{{ getS3FileUrl($car->car_picture) }}">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-right">
                        <form action="{{ route('case.handleCas091', ['id' => $case->case_id]) }}" method="POST">
                            @csrf    
                            <x-button id="btn-confirmed" label="確認完了" type="submit" class="btn-info" />
                            <x-button label="戻る" href="{{ route('case.cas-090') }}" class="btn-warning" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/cas/cas09/cas091.js') }}"></script>
@endpush
