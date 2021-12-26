@extends('layouts.app')

@section('title', '検品完了前確認')

@section('content')
  <div class="content mt-70">
    <div class="row">
      <div class="col-12">
        <div class="card card-user">
          <div class="card-header">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>ケース番号</label>
                  <br>
                  <span class="fontbig">{{ $case->case_no ?? null }}</span>
                  <input type="hidden" id="case-id" value="{{ $case->case_id }}" />
                </div>
              </div>
            </div>
          </div>

          <div class="card-body pl-2 pr-2">
            <table class="table text-nowrap">
              <thead>
                <tr>
                  <th width="80px">
                    車台番号
                  </th>
                  <th width="50px"></th>
                  <th width="70px">
                    回収個数
                  </th>
                  <th>
                    確認
                  </th>
                </tr>
              </thead>
              <tbody>
                @foreach ($cars as $car)
                <tr>
                  <td>
                    @if ($car->car_picture)
                      <a href="#" class="car-item-072" data-img="{{ getS3FileUrl($car->car_picture) }}">
                        <span class="fontbig">{{ $car->car_no_sub }}</span>
                      </a>
                    @else
                      <span class="fontbig">{{ $car->car_no_sub }}</span>
                    @endif
                  </td>
                  <td class="text-center" style="text-overflow: unset;">
                    @if ($car->mechanical_type)
                      <span class="fontmini" style="font-size: 11px;">機械式</span><br>
                      <img class="iconmini mb-1" src="{{ asset('images/icon/exclamation.svg') }}">
                    @endif
                  </td>
                  <td>
                    <span class="fontbig">{{ $car->qty }}</span>
                  </td>
                  <td>
                    <button type="button" class="btn btn-info btn-round btn-mini rowCheck">OK</button>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>

            <hr>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>合計個数</label>
                  <br>
                  <span class="fontbig">{{ $cars->sum('qty') }}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>未合致</label>
                  <div class="check">
                    <label>
                      <input type="radio" class="minimal-blue is-mismatch" disabled name="r1" id="radio1" value="1" {{ $mismatchs->count() > 0 ? 'checked' : null }} /><span>あり</span>
                    </label>
                    <label>
                      <input type="radio" class="minimal-blue is-mismatch" disabled name="r1" id="radio2" value="2" {{ $mismatchs->count() === 0 ? 'checked' : null }} /><span>なし</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="row {{ $mismatchs->count() === 0 ? 'none' : null }}">
              <div class="col-md-4">
                <div class="form-group">
                  <label>短絡不良数量</label>
                  <br>
                  <span class="fontbig">{{ getMismatchByType($mismatchs, 1)->mismatch_qty ?? null }}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>過分解数量</label>
                  <br>
                  <span class="fontbig">{{ getMismatchByType($mismatchs, 2)->mismatch_qty ?? null }}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>付属品数量</label>
                  <br>
                  <span class="fontbig">{{ getMismatchByType($mismatchs, 3)->mismatch_qty ?? null }}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>M式未ロック数量</label>
                  <br>
                  <span class="fontbig">{{ getMismatchByType($mismatchs, 4)->mismatch_qty ?? null }}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>M式未収納数量</label>
                  <br>
                  <span class="fontbig">{{ getMismatchByType($mismatchs, 5)->mismatch_qty ?? null }}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>その他数量</label>
                  <br>
                  <span class="fontbig">{{ getMismatchByType($mismatchs, 6)->mismatch_qty ?? null }}</span>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12 text-center">
                <a href="#" id="cas-072-report">
                  <button type="button" class="btn btn-info btn-round btn-long">検品完了</button>
                </a>
                <br>
                <a href="{{ route('case.cas-071', ['caseNo' => $case->case_no]) }}">
                  <button type="button" class="btn btn-warning btn-round btn-long">戻る</button>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- modalphoto -->
  <div class="modal fade" id="modalphoto">
    <div class="modal-dialog row justify-content-center">
      <div class="modal-content col-12 col-sm-8 col-md-6 col-lg-5 col-xl-3">
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <form>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <img src="" id="car-picture-main" />
                    </div>
                  </div>
                </div>

                <div class="text-right">
                  <button type="button" class="btn btn-danger btn-round" data-dismiss="modal" aria-label="Close">
                    閉じる
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End modalphoto -->
@endsection

@push('scripts')
  <script src="{{ mix('js/screens/cas/cas07/cas072.js') }}"></script>
@endpush