@extends('layouts.app')

@section('title', 'パレット一覧')

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
								<div class="form-group">
									<label>指定引取場所事業所名</label>
									<div class="row">
										<label class="col-10 pr-1">
											<input type="text" class="form-control" value="{{isset($palTransItem->pallets[0]->sy_office_code)?$palTransport->getMstOfficeBySyOfficeCode($palTransItem->pallets[0]->sy_office_code):''}}" disabled>
										</label>
										<div class="col-1 pl-1">
											<a href="{{isset($palTransItem->pallets[0]->sy_office_code)?route('master.mst-022',$palTransport->getMstOfficeIdBySyOfficeCode($palTransItem->pallets[0]->sy_office_code)):''}}">
												<i class="nc-icon nc-alert-circle-i mt-2"></i>
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>集荷日</label>
									<input type="text" class="form-control" value="{{date('Y/m/d',strtotime($palTransItem->deliver_complete_time))}}" disabled>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>使用車両番号</label>
									<input type="text" class="form-control" value="{{$palTransItem->car_no}}" disabled>
								</div>
							</div>
						</div>
						<hr>
						<h5>パレット情報</h5>
						<div class="table-responsive">
							<table class="table text-nowrap">
								<thead>
									<tr>
										<th width="100px">
											パレット番号
										</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$array = json_decode(json_encode($palTransItem->pallets), true);
									array_multisort(array_column($array, 'pallet_no'), SORT_ASC, $array);
									?>
									@foreach($array as $ar)
									<tr>
										<td>
											<span class="fontbig">{{$ar['pallet_no']}}</span>
										</td>
									</tr>
									@endforeach()
								</tbody>
							</table>
							<div class="text-right">
								<a href="{{ route('palette.pal-050') }}">
									<button type="button" class="btn btn-warning btn-round">戻る</button>
								</a>
							</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection