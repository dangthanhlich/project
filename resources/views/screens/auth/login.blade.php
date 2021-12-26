@extends('layouts.login')

@section('title', 'ログイン')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-4">
            <div class="container">
                <div class="login-container">
                    <h5>エアバッグ類<br>引取検品記録システム</h5>

                    @if ($errors->any())
                        @php
                            $errorType = 'danger';
                            $errorMessages = $errors->all();
                        @endphp
                        <x-alert :messages="$errorMessages" :type="$errorType" />
                    @endif

                    <div class="rows">
                        <form action="{{ route('login.handle') }}" method="POST" id="login-form">
                            @csrf
                            <div class="form-box form-group">
                                <div class="form-box form-group">
                                    <p class="text-left" style="margin-bottom: -0.1rem;">ログインID</p>
                                    <input
                                        data-label="ログインID"
                                        name="loginId"
                                        autocomplete="OFF"
                                        type="text"
                                        class="form-control"
                                        autocomplete="new-login-id"
                                        value="{{ !empty($formData['loginId']) ? $formData['loginId'] : '' }}">
                                </div>
                            </div>

                            <div class="form-box form-group">
                                <div class="form-box form-group">
                                    <p class="text-left" style="margin-bottom: -0.1rem;">パスワード</p>
                                    <input
                                        data-label="パスワード"
                                        type="password"
                                        class="form-control"
                                        autocomplete="new-password"
                                        name="password"
                                    >
                                </div>
                            </div>

                            <button type="submit" class="btn btn-info btn-block btn-round login btn-submit">ログイン</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection

<style>
.form-box input[type=text] {
    text-transform: none !important;
}
</style>

@push('scripts')
<script src="{{ mix('js/screens/auth/login.js') }}"></script>
@endpush
