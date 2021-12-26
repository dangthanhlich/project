@extends('layouts.app')

@section('title', 'その他の業者登録')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header"></div>

                <div class="card-body">
                    <form action="{{ route('master.handleMst021') }}" method="POST" id="mst021-form" class="form-add-edit">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <x-forms.checkbox
                                    label="業者区分"
                                    name="office_flg"
                                    :options="getList('MstOffice.officeFlgScreen')"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所コード"
                                    name="office_code"
                                    isRequired="true"
                                    value="{{ old('office_code') }}"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所名"
                                    name="office_name"
                                    value="{{ old('office_name') }}"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所名（カナ）"
                                    name="office_name_kana"
                                    value="{{ old('office_name_kana') }}"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 郵便番号"
                                    name="office_address_zip"
                                    value="{{ old('office_address_zip') }}"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 都道府県"
                                    name="office_address_pref"
                                    value="{{ old('office_address_pref') }}"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 市区町村"
                                    name="office_address_city"
                                    value="{{ old('office_address_city') }}"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 町字"
                                    name="office_address_town"
                                    value="{{ old('office_address_town') }}"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 番地"
                                    name="office_address_block"
                                    value="{{ old('office_address_block') }}"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 建物名"
                                    name="office_address_building"
                                    value="{{ old('office_address_building') }}"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="電話番号"
                                    name="office_tel"
                                    value="{{ old('office_tel') }}"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="FAX番号"
                                    name="office_fax"
                                    value="{{ old('office_fax') }}"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="担当者名"
                                    name="pic_name"
                                    value="{{ old('pic_name') }}"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="担当者名（カナ）"
                                    name="pic_name_kana"
                                    value="{{ old('pic_name_kana') }}"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="担当者電話番号"
                                    name="pic_tel"
                                    value="{{ old('pic_tel') }}"
                                />
                            </div>
                        </div>

                        <div class="text-right">
                            <x-button label="保存" type="submit" class="btn-info" />
                            <x-button label="戻る" href="{{ route('master.mst-020') }}" class="btn-warning" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/mst/mst02/mst021.js') }}"></script>
@endpush
