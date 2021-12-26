@extends('layouts.app')

@section('title', '認定車両一覧')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header">
                    <div class="row">
                        <h5 class="card-title col-12">検索条件</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('master.handleMst050') }}" method="POST" class="form-search">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.select
                                    label="二次運搬業者事業者名"
                                    name="office_code"
                                    :isSearch="true"
                                    :options="$lstOffice"
                                    :keySelected="isset($paramsSearch['office_code']) ? $paramsSearch['office_code'] : ''"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    label="車両番号"
                                    name="car_no"
                                    :value="isset($paramsSearch['car_no']) ? $paramsSearch['car_no'] : ''"
                                />
                            </div>
                        </div>

                        <div class="text-right col-12">
                            <x-button label="検索" type="submit" class="btn-info" id="btn-search" />
                            <x-button.clear screen="mst010" />
                        </div>
                    </form>
                </div>
            </div>

            <div class="card card-user">
                <div class="card-header">
                    <h5 class="card-title">検索結果</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive table-hover">
                        <table id="mst050-table" class="table text-nowrap custom-data-table">
                            <thead>
                                <tr>
                                    <th width="100px">管理番号</th>
                                    <th width="120px">事業所コード</th>
                                    <th width="300px">事業所名</th>
                                    <th width="120px">車両番号</th>
                                    <th>型式</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mstCars as $mstCar)
                                    <tr>
                                        <td>
                                            {{ $mstCar->management_no }}
                                        </td>
                                        <td>
                                            {{ $mstCar->company_code_2nd_tr }}
                                        </td>
                                        <td>
                                            {{ $mstCar->office_name }}
                                        </td>
                                        <td>
                                            {{ $mstCar->car_no }}
                                        </td>
                                        <td>
                                            {{ valueToText($mstCar->car_type, 'MstCar.carType') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $mstCars->links('common.pagination') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/mst/mst05/mst050.js') }}"></script>
@endpush
