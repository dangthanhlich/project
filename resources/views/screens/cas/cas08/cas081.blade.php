@extends('layouts.app')

@section('title', 'ケース詳細')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-12">
            <div class="card card-user">
                <div class="card-header"></div>
                <div class="card-body">
                    <form>
                    <div class="row">
                        <div class="col-md-4">
                            <x-forms.text label="荷姿ID"
                                          name="case_id"
                                          :value="$case->case_id"
                                          isLabel="true"/>
                        </div>
                        <div class="col-md-4">
                            <x-forms.select label="荷姿ステータス"
                                          name="case_status"
                                          :options="getList('Case.caseStatus')"
                                          :keySelected="$case->case_status"
                                          isLabel="true"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-forms.text label="ケース番号"
                                          name="case_no"
                                          :value="$case->case_no"
                                          isLabel="true"/>
                        </div>
                        <div class="col-md-4">
                            <x-forms.checkbox label="手修正" name="case_no_change_flg"
                                                      :options="[$isHandCorrection => '']"
                                                      :valueChecked="$case->case_no_change_flg == $isHandCorrection ? [$isHandCorrection] : []"
                                                      isLabel="true"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                        <div class="form-group">
                            <label>解体業者</label>
                            <div class="row">
                              <label class="col-10 pr-1">
                                <input type="text" class="form-control" value="{{ !empty($case->mst_scrapper) ? $case->mst_scrapper->office_code . ' ' . $case->mst_scrapper->office_name : ''}}" disabled>
                              </label>
                              <div class="col-1 pl-1">
                                 @if (!empty($case->mst_scrapper))
                                    <a href="{{ route('master.mst-011', ['id' => $case->mst_scrapper->id]) }}">
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
                                          :value="$case->collect_complete_time"
                                          isLabel="true"/>
                    </div>
                        <div class="col-md-4">
                        <x-forms.text label="引渡報告日時"
                                          name="deliver_report_time"
                                          :value="$case->deliver_report_time"
                                          isLabel="true"/>
                    </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-forms.text label="受入日時"
                                              name="receive_complete_time"
                                              :value="$case->receive_complete_time"
                                              isLabel="true"/>
                        </div>
                        <div class="col-md-4">
                            <x-forms.text label="検品日時"
                                              name="inspect_complete_time"
                                              :value="$case->inspect_complete_time"
                                              isLabel="true"/>
                        </div>
                        <div class="col-md-4">
                        <x-forms.text label="検品ユーザー"
                                          name="inspect_user_id"
                                          :value="!empty($inspectUser) ? $inspectUser->user_name : ''"
                                          isLabel="true"/>
                    </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>管理番号</label>
                            <div class="row">
                              <label class="col-10 pr-1">
                                <input type="text" class="form-control" value="{{ !empty($case->contract) ? $case->contract->management_no : '' }}" disabled>
                              </label>
                              <div class="col-1 pl-1">
                                 @if (!empty($case->contract))
                                    <a href="{{  route('case.cas-031', ['managementNo' => $case->contract->management_no]) }}">
                                  <i class="nc-icon nc-alert-circle-i mt-2"></i>
                                </a>
                                @endif
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                        <x-forms.text label="管理番号印刷日付"
                                          name="management_no_print_time"
                                          :value="!empty($case->contract) ? $case->contract->management_no_print_time : ''"
                                          isLabel="true"/>
                    </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-forms.text label="返品日時"
                                              name="return_time"
                                              :value="$case->return_time"
                                              isLabel="true"/>
                        </div>
                        <div class="col-md-4">
                        <x-forms.text label="返品理由"
                                          name="return_reason"
                                          :value="$case->return_reason"
                                          isLabel="true"/>
                    </div>
                    </div>

                    <hr>
                    <h5>集荷/受入時荷姿写真</h5>
                    <div class="row">
                        <div class="col-md-4">
                          <img src="{{ getS3FileUrl($case->case_picture_1) }}">
                        </div>
                        <div class="col-md-4">
                          <img src="{{ getS3FileUrl($case->case_picture_2) }}">
                        </div>
                        <div class="col-md-4">
                      <img src="{{ getS3FileUrl($case->case_picture_3) }}">
                    </div>
                    </div>

                    <hr>
                    <h5>検品写真</h5>
                    <div class="row">
                        @foreach ($case->car as $car)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ $car->car_no }}</label>
                                    <img src="{{ getS3FileUrl($car->car_picture) }}">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr>
                    <h5>車台情報</h5>
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th width="200px">
                                        車台番号
                                    </th>
                                    <th width="100px">
                                        装備個数
                                    </th>
                                    <th width="100px">
                                        回収個数
                                    </th>
                                    <th width="100px">
                                        超過個数
                                    </th>
                                    <th width="100px">
                                        合計個数
                                    </th>
                                    <th>
                                        引取報告個数
                                    </th>
                                </tr>
                              </thead>
                            <tbody>
                                @foreach ($case->car as $car)
                                    <tr>
                                        <td>{{ $car->car_no }}</td>
                                    @if ($car->exceed_qty_disable_flg == getConstValue('Car.exceedQtyDisableFlg.PROCESSED'))
                                        <td colspan="5">{{ getMessage('m-003') }}</td>
                                    @else
                                        <td>{{ $car->equipment_qty }}</td>
                                        <td>{{ $car->qty }}</td>
                                        <td>{{ $car->exceed_qty }}</td>
                                        <td>{{ $car->qty + $car->exceed_qty }}</td>
                                        <td>{{ $car->actual_qty }}</td>
                                    @endif
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
                        <x-button.back href="{{ route('case.cas-080') }}" label="戻る" />
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
