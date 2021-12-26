@extends('layouts.app')

@section('title', 'ケース集荷予定詳細')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-user">
            <div class="card-header"></div>
            <div class="card-body">
                <form method="POST" action="{{ route('case.handleCas012', ['id' => $planCase->id]) }}" id="cas012-form" class="form-add-edit">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <x-forms.text
                                name="office_code_from"
                                isLabel="true"
                                label="解体業者事業所名"
                                :value="$planCase->office_code_name"
                                :isHidden="true"
                                :valueHidden="$planCase->office_code_from"
                            />
                        </div>

                        <div class="col-md-4">
                            <x-forms.date
                                name="collect_plan_date"
                                isRequired="true"
                                label="予定日"
                                :value="old('collect_plan_date') !== null ? old('collect_plan_date') : (empty($errors->all()) ? $planCase->collect_plan_date : '')"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-forms.text
                                name="case_qty"
                                label="ケース数"
                                :value="old('case_qty') !== null ? old('case_qty') : (empty($errors->all()) ? $planCase->case_qty : '')"
                            />
                        </div>

                        <div class="col-md-4">
                            <x-forms.text
                                name="collect_plan_memo"
                                label="メモ"
                                :value="old('collect_plan_memo') !== null ? old('collect_plan_memo') : (empty($errors->all()) ? $planCase->collect_plan_memo : '')"
                            />
                        </div>

                        <div class="col-md-4">
                            <x-forms.checkbox
                                name="plan_date_adjusted_flg"
                                label="予定日調整"
                                :options="getList('PlanCase.scheduledDateAdjustment')"
                                :valueChecked="old('plan_date_adjusted_flg') ? old('plan_date_adjusted_flg') : (empty($errors->all()) ? [$planCase->plan_date_adjusted_flg] : [])"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-forms.text
                                name="empty_case_qty"
                                label="空ケース数"
                                :value="old('empty_case_qty') !== null ? old('empty_case_qty') : (empty($errors->all()) ? $planCase->empty_case_qty : '')"
                            />
                        </div>

                        <div class="col-md-4">
                            <x-forms.text
                                name="bag_qty"
                                label="空袋数"
                                :value="old('bag_qty') !== null ? old('bag_qty') : (empty($errors->all()) ? $planCase->bag_qty : '')"
                            />
                        </div>
                    </div>

                    <div class="text-right">
                        <x-button type="submit" class="btn-info" label="保存" />
                        <x-button.back href="{{ route('case.cas-010') }}" label="戻る" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/cas/cas01/cas012.js') }}"></script>
@endpush
