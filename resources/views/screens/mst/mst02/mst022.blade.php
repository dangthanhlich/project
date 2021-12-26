@extends('layouts.app')

@section('title', 'その他の業者詳細')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header"></div>

                <div class="card-body">
                    <form action="{{ route('master.handleMst022', ['id' => $mstOffice->id]) }}" method="POST" id="mst022-form" class="form-add-edit">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="ID"
                                    name="id"
                                    :value="$mstOffice->id"
                                    :isLabel="true"
                                    :isHidden="true"
                                />
                            </div>
                            <div class="col-md-8">
                                <x-forms.checkbox
                                    label="業者区分"
                                    name="office_flg"
                                    :options="getList('MstOffice.officeFlgScreen')"
                                    :valueChecked="$officeFlg"
                                    :isLabel="$isBatch"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所コード"
                                    name="office_code"
                                    :value="$mstOffice->office_code"
                                    isRequired="true"
                                    :isLabel="$isBatch"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所名"
                                    name="office_name"
                                    :value="$mstOffice->office_name"
                                    :isLabel="$isBatch"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所名（カナ）"
                                    name="office_name_kana"
                                    :value="$mstOffice->office_name_kana"
                                    :isLabel="$isBatch"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 郵便番号"
                                    name="office_address_zip"
                                    :value="$mstOffice->office_address_zip"
                                    :isLabel="$isBatch"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 都道府県"
                                    name="office_address_pref"
                                    :value="$mstOffice->office_address_pref"
                                    :isLabel="$isBatch"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 市区町村"
                                    name="office_address_city"
                                    :value="$mstOffice->office_address_city"
                                    :isLabel="$isBatch"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 町字"
                                    name="office_address_town"
                                    :value="$mstOffice->office_address_town"
                                    :isLabel="$isBatch"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 番地"
                                    name="office_address_block"
                                    :value="$mstOffice->office_address_block"
                                    :isLabel="$isBatch"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="事業所住所 建物名"
                                    name="office_address_building"
                                    :value="$mstOffice->office_address_building"
                                    :isLabel="$isBatch"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="電話番号"
                                    name="office_tel"
                                    :value="$mstOffice->office_tel"
                                    :isLabel="$isBatch"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="FAX番号"
                                    name="office_fax"
                                    :value="$mstOffice->office_fax"
                                    :isLabel="$isBatch"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="担当者名"
                                    name="pic_name"
                                    :value="$mstOffice->pic_name"
                                    :isLabel="$isBatch"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="担当者名（カナ）"
                                    name="pic_name_kana"
                                    :value="$mstOffice->pic_name_kana"
                                    :isLabel="$isBatch"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    label="担当者電話番号"
                                    name="pic_tel"
                                    :value="$mstOffice->pic_tel"
                                    :isLabel="$isBatch"
                                />
                            </div>
                        </div>

                        <div class="text-right">
                            @if (!$isBatch)
                                <x-button label="保存" type="submit" class="btn-info" />
                            @endif
                            <x-button label="戻る" href="{{ route('master.mst-020') }}" class="btn-warning" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/mst/mst02/mst022.js') }}"></script>
@endpush
