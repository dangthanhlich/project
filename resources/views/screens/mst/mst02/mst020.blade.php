@extends('layouts.app')

@section('title', 'その他の業者一覧')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-user">
            <div class="card-header">
                <div class="row">
                    <h5 class="card-title col-6">検索条件</h5>
                    <div class="text-right col-md-6">
                        <a href="{{ route('master.mst-021') }}">
                            <button type="button" class="btn btn-info btn-round">新規登録</button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('master.handleMst020') }}" method="POST" class="form-search">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <x-forms.text
                                    label="事業所コード"
                                    name="office_code"
                                    :value="isset($searchParams['office_code']) ? $searchParams['office_code'] : ''"
                                />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <x-forms.text
                                    label="事業所名"
                                    name="office_name"
                                    :value="isset($searchParams['office_name']) ? $searchParams['office_name'] : ''"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <x-forms.checkbox
                                    label="業者区分"
                                    name="office_flg"
                                    :options="getList('MstOffice.officeFlgScreen')"
                                    :valueChecked="isset($searchParams['office_flg']) ? $searchParams['office_flg'] : []"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="text-right col-12">
                        <button id="btn-search" class="btn btn-info btn-round">検索</button>
                        <x-button.clear screen="mst020" />
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-user">
            <div class="card-header">
                <h5 class="card-title">検索結果</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="mst020-table" class="table text-nowrap custom-data-table" style="min-width: 1200px;">
                        <thead>
                            <tr>
                                <th width="30px">ID</th>
                                <th width="60px">事業所コード</th>
                                <th width="100px">事業所名</th>
                                <th width="90px">事業所電話番号</th>
                                <th width="90px">担当者名</th>
                                <th width="40px">運搬NW</th>
                                <th width="60px">指定引取場所</th>
                                <th width="40px">二次運搬</th>
                                <th width="60px">再資源化施設</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mstOffices as $mstOffice)
                                <tr>
                                    <td>
                                        <a href="{{ route('master.mst-022', ['id' => $mstOffice->id]) }}">
                                            {{ $mstOffice->id }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $mstOffice->office_code }}
                                    </td>
                                    <td>
                                        {{ $mstOffice->office_name }}
                                    </td>
                                    <td>
                                        {{ $mstOffice->office_tel }}
                                    </td>
                                    <td>
                                        {{ $mstOffice->pic_name }}
                                    </td>

                                    <td>
                                        <label>
                                            <input
                                                type="checkbox"
                                                class="minimal-blue"
                                                disabled
                                                {{ $mstOffice->is_tr_office_flg === 1 ? 'checked' : '' }}
                                            >
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input
                                                type="checkbox"
                                                class="minimal-blue"
                                                disabled
                                                {{ $mstOffice->is_sy_office_flg === 1 ? 'checked' : '' }}
                                            >
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input
                                                type="checkbox"
                                                class="minimal-blue"
                                                disabled
                                                {{ $mstOffice->{"is_2nd_tr_office_flg"} === 1 ? 'checked' : '' }}
                                            >
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input
                                                type="checkbox"
                                                class="minimal-blue"
                                                disabled
                                                {{ $mstOffice->is_rp_office_flg === 1 ? 'checked' : '' }}
                                            >
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $mstOffices->links('common.pagination') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ mix('js/screens/mst/mst02/mst020.js') }}"></script>
@endpush
