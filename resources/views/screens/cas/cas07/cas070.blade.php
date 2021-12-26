@extends('layouts.app')

@section('title', '検品ケース選択')

@section('content')
  <div class="content mt-70 cas-07">
    <div class="row">
      <div class="col-12">
        <div class="card card-user form-search">
          <div class="card-header">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>ケース番号</label>
                  <div class="row">
                    <label class="col-9 pr-1">
                      <input type="tel" class="form-control" id="case-no-value" >
                      <span class="case-no-invalid red"></span>
                    </label>
                    <div class="col-3 pl-1">
                      <button type="button" class="btn btn-info btn-round btn-mini" id="search-case-no">
                        <i class="nc-icon nc-minimal-down"></i>
                      </button>
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="button" id="nfc-btn" class="btn btn-info btn-round btn-mini">
                      IC読み取り
                    </button>
                    <button type="button" id="scan-qrcode" class="btn btn-info btn-round btn-mini">
                      QR読み取り
                    </button>
                  </div>

                  <div class="row">
                    <div class="col-12">
                      <span class="none" id="result"><span class="total-found-case-no"></span> 件中 <span class="total-case-no"></span> 件目</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card card-user">
          <div class="card-header">
            <h5>未検品</h5>
          </div>

          <div class="card-body pl-2 pr-2">
            <table class="table text-nowrap" id="tableSP1">
              <thead>
                <tr>
                  @if ($casesNotVerify->count() > 0)
                    <th width="170px">
                      ケース番号
                    </th>
                    <th width="80px">
                      検品
                    </th>
                  @else
                  
                    <th width="170px"></th>
                    <th width="80px"></th>
                  @endif
                </tr>
              </thead>
              <tbody>
                1233456
                @foreach ($casesNotVerify as $caseNotVerify)
                  <tr>
                    <td>
                      <span class="fontbig case-no-number">{{ $caseNotVerify->case_no }}</span>
                      @if($caseNotVerify->case_status == 4) <i class="nc-icon nc-alert-circle-i"></i> @endif
                      @if($caseNotVerify->return_time != null)<i class="nc-icon nc-simple-remove red"></i>@endif
                    </td>
                    <td>
                      <a href="{{ route('case.cas-071', ['caseNo' => $caseNotVerify->case_no]) }}">
                        <button type="button" class="btn btn-info btn-round btn-mini @if ($caseNotVerify->inspect_stop_flg == 1) none @endif">開始</button>
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <div class="card card-user">
          <div class="card-header">
            <h5>引渡報告不一致</h5>
          </div>
          
          <div class="card-body pl-2 pr-2">
            <table class="table text-nowrap" id="tableSP2">
              <thead>
                <tr>
                  @if($casesDeliveryReportMismatch->count() > 0)
                    <th width="150px">
                      ケース番号
                    </th>
                    <th>
                      検品
                    </th>
                  @else
                    <th width="150px"></th>
                    <th></th>
                  @endif
                </tr>
              </thead>
              
              <tbody>
                @foreach ($casesDeliveryReportMismatch as $caseDeliveryReportMismatch)
                  <tr>
                    <td>
                      <span class="fontbig case-no-number">{{ $caseDeliveryReportMismatch->case_no }}</span>
                      @if($caseDeliveryReportMismatch->case_status == 4) <i class="nc-icon nc-alert-circle-i"></i> @endif
                      @if($caseDeliveryReportMismatch->return_time != null)<i class="nc-icon nc-simple-remove red"></i>@endif
                    </td>
                    <td>
                      <a href="{{ route('case.cas-071', ['caseNo' => $caseDeliveryReportMismatch->case_no]) }}">
                        <button type="button" class="btn btn-info btn-round btn-mini @if ($caseDeliveryReportMismatch->inspect_stop_flg == 1 || !$caseDeliveryReportMismatch->diff_resolve_time) none @endif">開始</button>
                      </a>
                    </td>
                  </tr>
                @endforeach

                @foreach ($tempCases as $tempCase)
                  <tr>
                    <td>
                      <span class="fontbig case-no-number">{{ $tempCase->temp_case_no }}</span>
                    </td>
                    <td></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <div class="card card-user">
          <div class="card-header">
            <h5>個数再確認待</h5>
          </div>

          <div class="card-body pl-2 pr-2">
            <table class="table text-nowrap" id="tableSP3">
              <thead>
                <tr>
                  @if($casesWaitingConfirmQuantity->count() > 0)
                    <th width="170px">
                      ケース番号
                    </th>
                    <th width="80px"></th>
                  @else
                    <th width="170px"></th>
                    <th width="80px"></th>
                  @endif
                </tr>
              </thead>
              
              <tbody>

                @foreach ($casesWaitingConfirmQuantity as $caseWaitingConfirmQuantity)
                  <tr>
                    <td>
                    12231223
                      <span class="fontbig case-no-number">
                        {{ $caseWaitingConfirmQuantity->case_no}}
                      </span>
                    </td>
                    <td>
                      <a href="{{ route('case.cas-074', ['caseNo' => $caseWaitingConfirmQuantity->case_no]) }}">
                        <button type="button" class="btn btn-info btn-round btn-mini">編集</button>
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="qr-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 100% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" style="text-align: center;">
                        <canvas id="canvas" style="position: absolute;"></canvas>
                        <video id="video" style="width: 100%;" autoplay></video>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ mix('js/library/qrcode/qrcode.js') }}"></script>
  <script src="{{ mix('js/library/nfc/nfc.js') }}"></script>
  <script src="{{ mix('js/screens/cas/cas07/cas070.js') }}"></script>
@endpush

@push('style')
  <link href="{{ mix('css/screens/cas/cas07/cas070.css') }}" rel="stylesheet" />
@endpush