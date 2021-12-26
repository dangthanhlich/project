@extends('layouts.app')

@section('title', '引取報告差分確認')

@section('content')
<div class="content">
        <div class="row">
          <div class="col-12">
            <div class="card card-user">
              <div class="card-header"></div>

              <div class="card-body">
                <form method="POST" id="form-com022" action="{{ route('common.handleCom022') }}">
                  @csrf
                  <div class="row">
                    <div class="col-md-4">
                    	<x-forms.text label="荷姿ID"
                                          name="caseId"
                                          :value="$diffReceiveReport->case_id"
                                          isLabel="true"/>
                    </div>
                    <div class="col-md-4">
                    	<x-forms.select label="ステータス"
                                          name="case_status"
                                          :options="getList('Case.caseStatus')"
                                          :keySelected="$diffReceiveReport->case_status"
                                          isLabel="true"/>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                    	<x-forms.text label="ケース番号"
                                          name="case_no"
                                          :value="$diffReceiveReport->case_no"/>
                    </div>
                    <div class="col-md-4">
                    	<x-forms.text label="引取報告ケース番号"
                                          name="case_no_manifest"
                                          :value="$diffReceiveReport->case_no_manifest"
                                          isLabel="true"/>
                    </div>
                    <div class="col-md-4">
                    	<x-forms.checkbox label="手修正" name="case_no_change_flg"
                                                      :options="[$isHandCorrection => '']"
                                                      :valueChecked="$diffReceiveReport->case_no_change_flg == $isHandCorrection ? [$isHandCorrection] : []"
                                                      isLabel="true"/>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>解体業者</label>
                         <div class="row">
                              <label class="col-10 pr-1">
                                <input type="text" class="form-control" value="{{ !empty($diffReceiveReport->mst_scrapper) ? $diffReceiveReport->mst_scrapper->office_code . ' ' . $diffReceiveReport->mst_scrapper->office_name : ''}}" disabled>
                              </label>
                              <div class="col-1 pl-1">
                                 @if (!empty($diffReceiveReport->mst_scrapper))
                                    <a href="{{ route('master.mst-011', ['id' => $diffReceiveReport->mst_scrapper->id]) }}">
                                  <i class="nc-icon nc-alert-circle-i mt-2"></i>
                                </a>
                                @endif
                              </div>
                            </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                    	<x-forms.text label="集荷日時"
                                          name="collect_complete_time"
                                          :value="$diffReceiveReport->collect_complete_time"
                                          isLabel="true"/>
                    </div>
                    <div class="col-md-4">
                      <x-forms.text label="引渡報告日時"
                                          name="deliver_report_time"
                                          :value="$diffReceiveReport->deliver_report_time"
                                          isLabel="true"/>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <x-forms.text label="受入日時"
                                              name="receive_complete_time"
                                              :value="$diffReceiveReport->receive_complete_time"
                                              isLabel="true"/>
                    </div>
                  </div>

                  <hr>
                  <h5>集荷/受入時荷姿写真</h5>
                  <div class="row">
                    <div class="col-md-4">
                      <img src="{{ getS3FileUrl($diffReceiveReport->case_picture_1) }}">
                    </div>
                    <div class="col-md-4">
                      <img src="{{ getS3FileUrl($diffReceiveReport->case_picture_2) }}">
                    </div>
                    <div class="col-md-4">
                      <img src="{{ getS3FileUrl($diffReceiveReport->case_picture_3) }}">
                    </div>
                  </div>

                  <hr>
                  <h5>検品写真</h5>
                  <div class="row">
                   <div class="col-md-4">
                      <img src="{{ getS3FileUrl($diffReceiveReport->case_picture_1) }}">
                    </div>
                  </div>

                  <hr>
                  <h5>検品時車台情報</h5>
                  
                  <div class="table-responsive">
                    <table class="table text-nowrap" id="table-com022">
                      <thead>
                        <tr>
                          <th width="180px">
                            車台番号
                          </th>
                          <th width="80px">
                            回収個数
                          </th>
                          <th width="80px">
                            超過個数
                          </th>
                          <th width="80px">
                            合計個数
                          </th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                      	@foreach ($diffReceiveReport->car as $car)
                        <tr data-car_id="{{ $car->car_id }}">
                          <td>
                            <input type="text" class="form-control" value="{{ $car->car_no }}" name="car_nos[{{ $car->car_id }}]">
                          </td>
                          <td>
                            <input type="text" class="form-control" value="{{ $car->qty }}" name="car_qtys[{{ $car->car_id }}]">
                          </td>
                          <td>{{ $car->exceed_qty }}</td>
                          <td>
                              {{ (int)$car->qty + (int)$car->exceed_qty }}
                          </td>
                          <td>
                            <button type="button" class="btn btn-danger btn-round btn-mini btn-del-car">削除</button>
                          </td>
                        </tr>
                      	@endforeach
                      	<tr id="add-car-row" class="none">
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td>
                            <button type="button" class="btn btn-danger btn-round btn-mini btn-del-car">削除</button>
                          </td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td>
                            <button id="btn-add-car" type="button" class="btn btn-info btn-round btn-mini">追加</button>
                          </td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>

                  <h5>引取報告車台情報</h5>
                  <div class="table-responsive">
                    <table class="table text-nowrap">
                      <thead>
                        <tr>
                          <th width="180px">
                            車台番号
                          </th>
                          <th>
                            報告個数
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                      	@php
                      		$carNoManifest = explode(',', $diffReceiveReport->car_no_manifest);
                      		$carQtyManifest = explode(',', $diffReceiveReport->car_qty_manifest);
                      	@endphp

                      	@foreach ($carNoManifest as $key => $carNo)
                      	<tr>
                          <td>
                            {{ $carNo }}
                          </td>
                          <td>
                            {{ !empty($carQtyManifest[$key]) ? $carQtyManifest[$key] : '' }}
                          </td>
                        </tr>
                      	@endforeach
                      </tbody>
                    </table>
                  </div>

                  <hr>
                  <h5>未合致報告</h5>

                  
                  <div class="row">
                    @foreach ($misMatchTypes as $type => $label)
                        <div class="col-md-4">
                            <x-forms.text label="{{ $label }}"
                                          name="{{ 'mismatch_type_' . $type }}"
                                          :value="array_key_exists($type, $misMatchArr) ? $misMatchArr[$type] : ''"
                                          isLabel="true"/>
                         </div>
                        @endforeach
                  </div>

                  <div class="text-right">
                  	<input type="hidden" name="id" value="{{ $diffReceiveReport->id }}">
                    <button type="submit" class="btn btn-info btn-round" type="button">保存</button>
                    <x-button.back href="{{ route('common.com-020_32') }}" label="戻る" />
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/screens/com/com02/com022.js') }}"></script>
@endpush