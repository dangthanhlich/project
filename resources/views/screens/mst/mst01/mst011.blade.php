@extends('layouts.app')

@section('title', '解体業者詳細')

@section('content')
    @php
        $isTypeSelfReconciliation = true;
        $isTypeOffice = true;
        if (
            Gate::check(getValue('MstUser.permission')['JA1']) ||
            Gate::check(getValue('MstUser.permission')['JA2'])
        ) {
            $isTypeSelfReconciliation = false;
        }
        if (Gate::check(getValue('MstUser.permission')['NW'])) {
            $isTypeOffice = false;
        }
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header"></div>

                <div class="card-body">
                    <form action="{{ route('master.handleMst011', ['id' => $mstScrapper->id]) }}" method="POST" id="mst011-form" class="form-add-edit">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="ID"
                                    name="id"
                                    :value="$mstScrapper->id"
                                    :isLabel="true"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所コード"
                                    name="office_code"
                                    :value="$mstScrapper->office_code"
                                    :isLabel="true"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所名"
                                    name="office_name"
                                    :value="$mstScrapper->office_name"
                                    :isLabel="true"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所名（カナ）"
                                    name="office_name_kana"
                                    :value="$mstScrapper->office_name_kana"
                                    :isLabel="true"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 郵便番号"
                                    name="office_address_zip"
                                    :value="$mstScrapper->office_address_zip"
                                    :isLabel="true"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 都道府県"
                                    name="office_address_pref"
                                    :value="$mstScrapper->office_address_pref"
                                    :isLabel="true"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 市区町村"
                                    name="office_address_city"
                                    :value="$mstScrapper->office_address_city"
                                    :isLabel="true"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 町字"
                                    name="office_address_town"
                                    :value="$mstScrapper->office_address_town"
                                    :isLabel="true"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 番地"
                                    name="office_address_block"
                                    :value="$mstScrapper->office_address_block"
                                    :isLabel="true"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 建物名"
                                    name="office_address_building"
                                    :value="$mstScrapper->office_address_building"
                                    :isLabel="true"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="電話番号"
                                    name="office_tel"
                                    :value="$mstScrapper->office_tel"
                                    :isLabel="true"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="FAX番号"
                                    name="office_fax"
                                    :value="$mstScrapper->office_fax"
                                    :isLabel="true"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="担当者名"
                                    name="pic_name"
                                    :value="$mstScrapper->pic_name"
                                    :isLabel="true"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="担当者名（カナ）"
                                    name="pic_name_kana"
                                    :value="$mstScrapper->pic_name_kana"
                                    :isLabel="true"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="担当者電話番号"
                                    name="pic_tel"
                                    :value="$mstScrapper->pic_tel"
                                    :isLabel="true"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.select
                                    label="運搬方法"
                                    name="transport_type"
                                    :options="getList('MstScrapper.transportType')"
                                    :keySelected="$mstScrapper->transport_type"
                                    :isLabel="true"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-forms.label name="運搬NW業者コード" :isRequired="false" />
                                    <div class="row">
                                        <div class="col-10 pr-1">
                                            <x-forms.text
                                                :label="null"
                                                name="tr_office_code"
                                                :value="$mstScrapper->tr_office_code"
                                                :isLabel="true"
                                                class="mb-0"
                                            />
                                        </div>
                                        <div class="col-1 pl-1">
                                            <x-html.link
                                                :to="route('master.mst-022', ['id' => $mstScrapper->mstOfficeTr_id])"
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
                                    label="運搬NW業者名"
                                    name="tr_office_name"
                                    :value="$mstScrapper->mstOfficeTr_office_name"
                                    :isLabel="true"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-forms.label name="指定引取場所業者コード" :isRequired="false" />
                                    <div class="row">
                                        <div class="col-10 pr-1">
                                            <x-forms.text
                                                :label="null"
                                                name="sy_office_code"
                                                :value="$mstScrapper->sy_office_code"
                                                :isLabel="true"
                                                class="mb-0"
                                            />
                                        </div>
                                        <div class="col-1 pl-1">
                                            <x-html.link
                                                :to="route('master.mst-022', ['id' => $mstScrapper->mstOfficeSy_id])"
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
                                    label="指定引取場所業者名"
                                    name="sy_office_name"
                                    :value="$mstScrapper->mstOfficeSy_office_name"
                                    :isLabel="true"
                                />
                            </div>
                        </div>

                        <hr>
                        <h5>注意情報</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="自再協入力情報"
                                    name="memo_jarp"
                                    :value="old('memo_jarp') !== null ? old('memo_jarp') : $mstScrapper->memo_jarp"
                                    :isLabel="$isTypeSelfReconciliation"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="更新日時"
                                    name="memo_jarp_updated_at"
                                    :value="$mstScrapper->memo_jarp_updated_at"
                                    :isLabel="true"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="運搬NW業者入力情報"
                                    name="memo_tr"
                                    :value="old('memo_tr') !== null ? old('memo_tr') : $mstScrapper->memo_tr"
                                    :isLabel="$isTypeOffice"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="更新日時"
                                    name="memo_tr_updated_at"
                                    :value="$mstScrapper->memo_tr_updated_at"
                                    :isLabel="true"
                                />
                            </div>
                        </div>

                        <div class="text-right">
                            @if (!$isTypeSelfReconciliation || !$isTypeOffice)
                                <x-button label="保存" type="submit" class="btn-info" />
                            @endif
                            <x-button label="戻る" href="{{ route('master.mst-010') }}" class="btn-warning" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/mst/mst01/mst011.js') }}"></script>
@endpush
