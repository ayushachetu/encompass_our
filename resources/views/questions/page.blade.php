@extends('layouts.default')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/questions.css') }}">
@endsection

@section('content')
	<div class="piluku-preloader text-center">
		<div class="loader">Loading...</div>
	</div>
	@include('questions.add')
	@include('questions.edit')
	<div class="wrapper ">
		@include('includes.sidebar')
		<div class="content" id="content">
			<div class="overlay"></div>
			@include('includes.topbar')
			<div class="main-content">
				<div class="row grid">
					<div class="col-sm-12">
						<div class="panel panel-piluku">
							<div class="panel-body">
								{!! csrf_field() !!}
								<div class="row">
									<div class="col-sm-12">
										<h3><i class="icon ti-view-list-alt"></i> Questions Management</h3>
									</div>
								</div>
								<div class="pull-right" style="text-align: right;">
										<button class="btn btn-lg btn-block btn-evaluation" id="new-ques-btn"><span>New Question</span></button>
									</div>
								<div class="clearfix"></div>
								<hr>
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
											<table id="q-table" class="table table-striped table-bordered">
												<thead>
													<th>Options</th>
													<th>Question</th>
													<?php $industry_index = []; ?>
													@for ($i = 0; $i < count($industries); $i++)
														<?php $industry_index[$i] = (string) $industries[$i]->industry_id; ?>
														<th>{{ $industries[$i]->name }}</th>
													@endfor
												</thead>
												<tbody>
													@foreach ($questions_data as $data)
														<tr data-id="{{ $data->id }}" data-priority="{{ $data->priority }}">
															<td>
																<i class="fa fa-2x fa-angle-down" aria-hidden="true"></i>&nbsp;
																<i class="fa fa-2x fa-angle-up" aria-hidden="true"></i>&nbsp;
																<i class="fa fa-2x fa-pencil" aria-hidden="true"></i>&nbsp;
																<i class="fa fa-2x fa-trash" aria-hidden="true"></i>
															</td>
															<td>
																{{ $data->name }}
															</td>
															@for ($i=0; $i < count($industry_index); $i++)
																@if (strpos($data->industries, $industry_index[$i]) !== false)
																	<td>
																		<input type="checkbox" class="industry-question-chk" checked="checked" data-industry="{{ $industry_index[$i] }}">
																	</td>
																@else
																	<td>
																		<input type="checkbox" class="industry-question-chk" data-industry="{{ $industry_index[$i] }}">
																	</td>
																@endif
															@endfor
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script>
		jQuery(window).load(function () {
			$('.piluku-preloader').addClass('hidden');
		});
	</script>
	<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/wow.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/jquery.loadmask.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/jquery.accordion.js') }}"></script>
	
	<script type="text/javascript" src="{{ asset('assets/js/build/d3.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/nvd3/nv.d3.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/bic_calendar.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/widgets.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/jquery.countTo.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/app.questions.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/app.questions-add.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/app.questions-edit.js') }}"></script>

	<script type="text/javascript">
		function deleted_options(index) {
			var data = {!! json_encode($deleted_options) !!};
			return data[index];
		}
	</script>
@endsection