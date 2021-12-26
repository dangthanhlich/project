@extends('layouts.app')

@section('title', 'ケース受入予定登録')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-user">
            <div class="card-header"></div>

            <div class="card-body">
                <form novalidate class="form-add-edit" method="POST" action="{{ route('case.handleCas041') }}" id="cas041-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <x-forms.text
                                name="office_code_from"
                                isLabel="true"
                                label="解体業者事業所名"
                                :value="$mstScrapper->office_code_name"
                                :isHidden="true"
                                :valueHidden="$mstScrapper->office_code"
                            />
                        </div>

                        <div class="col-md-4">
                            <x-forms.date
                                name="receive_plan_date"
                                isRequired="true"
                                label="予定日"
                                :value="old('receive_plan_date') ? old('receive_plan_date') : ''"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-forms.text
                                name="case_qty"
                                label="ケース数"
                                :value="old('case_qty') !== null ? old('case_qty') : ''"
                            />
                        </div>

                        <div class="col-md-4">
                            <x-forms.text
                                name="receive_plan_memo"
                                label="メモ"
                                :value="old('receive_plan_memo') !== null ? old('receive_plan_memo') : ''"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-forms.text
                                name="empty_case_qty"
                                label="空ケース数"
                                :value="old('empty_case_qty') !== null ? old('empty_case_qty') : ''"
                            />
                        </div>

                        <div class="col-md-4">
                            <x-forms.text
                                name="bag_qty"
                                label="空袋数"
                                :value="old('bag_qty') !== null ? old('bag_qty') : ''"
                            />
                        </div>
                    </div>

                    <div class="text-right">
                        <x-button type="submit" class="btn-info" label="保存" />
                        <x-button.back href="{{ route('case.cas-040') }}" label="戻る" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/cas/cas04/cas041.js') }}"></script>
@endpush
