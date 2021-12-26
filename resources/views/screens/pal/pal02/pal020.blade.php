@extends('layouts.app')

@section('title', 'パレット一覧')

@section('content')

    <div class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-user">
                    <div class="card-header">
                        <div class="row">
                            <h5 class="card-title col-12">検索条件</h5>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('palette.handlePal020') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <x-forms.checkbox label="ステータス" name="pallet_status"
                                                      :options="getList('Pallet.palletStatus')"
                                                      :valueChecked="isset($params['pallet_status']) ? $params['pallet_status'] : []"/>
                                </div>
                                <div class="col-md-4">
                                    <x-forms.text
                                        name="pallet_no"
                                        label="パレット番号"
                                        :value="isset($params['pallet_no']) ? $params['pallet_no'] : ''"/>
                                </div>
                                <div class="col-md-4">
                                    <x-forms.text
                                        name="case_no"
                                        label="ケース番号"
                                        :value="isset($params['case_no']) ? $params['case_no'] : ''"/>
                                </div>
                            </div>

                            <div class="text-right col-12">
                                <x-button type="submit" class="btn-info" id="btn-search" label="検索"/>
                                <x-button.clear screen="pal020"/>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card card-user">
                    <div class="card-header">
                        <div class="row">
                            <h5 class="card-title col-12">検索結果</h5>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive table-hover">
                            <table class="table text-nowrap custom-data-table" id="pal020-table">
                                <thead>
                                <tr>
                                    <th width="100px">
                                        パレット番号
                                    </th>
                                    <th width="100px">
                                        ステータス
                                    </th>
                                    <th width="120px">
                                        引渡日
                                    </th>
                                    <th>
                                        使用車両番号
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($pallets as $pallet)
                                    <tr>
                                        <td>
                                            <x-html.link
                                                :to="route('palette.pal-021', ['palletNo' => $pallet->pallet_no])"
                                                :label="$pallet->pallet_no"/>
                                        </td>
                                        <td>
                                            {{ !empty($pallet->pallet_status) ? valueToText($pallet->pallet_status, 'Pallet.palletStatus') : '' }}
                                        </td>
                                        <td>
                                            {{ $pallet->deliver_complete_time }}
                                        </td>
                                        <td>
                                            {{ $pallet->car_no }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{ $pallets->links('common.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection
