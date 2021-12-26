@extends('layouts.login')

@section('title', '管理番号確認')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 col-xl-4">
        <div class="container">
            <div class="login-container">
                @if ($errors->any())
                    @php
                        $errorType = 'danger';
                        $errorMessages = $errors->all();
                    @endphp
                    <x-alert :messages="$errorMessages" :type="$errorType" />
                @endif
        
                <div class="rows">
                    <label>集荷時に印刷された管理番号をご入力ください</label>
                    <br>
                    <form action="{{ route('case.handleCas030') }}" method="POST" id="cas030-form">
                        @csrf
                        <div class="form-box form-group">
                            <x-forms.text
                                name="office_code"
                                label="事業所コード（末尾04を除く上10桁）"
                                class="form-box"
                                classLabel="d-block text-left mb-0"
                                :value="old('office_code') !== null ? old('office_code') : ''"
                            />
                            <x-forms.text
                                name="management_no"
                                label="管理番号"
                                class="form-box"
                                classLabel="d-block text-left mb-0"
                                :value="old('management_no') !== null ? old('management_no') : ''"
                            />
                        </div>
                        <x-button type="submit" class="btn-info btn-block" label="確認" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ mix('js/screens/cas/cas03/cas030.js') }}"></script>
@endpush