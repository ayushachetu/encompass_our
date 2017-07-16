@extends('layouts.default')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/survey.css') }}">
@endsection

@section('content')
	<div class="piluku-preloader text-center">
		<div class="loader">Loading...</div>
	</div>
	@include('survey.add')
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
										<h3><i class="icon ti-bar-chart"></i> Surveys Management</h3>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-offset-9 col-sm-3 col-xs-offset-1 col-xs-10 text-right">
										<button class="btn btn-lg btn-block btn-evaluation" id="new-survey-btn"><span>New Survey</span></button>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-sm-12">
										<table class="table table-hover" id="s-table">
											<thead>
												<tr>
													<th>Survey Name</th>
													<th>Type</th>
													<th>Creation Date</th>
													<th>Last Modified</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($survey_data as $data)
													<tr data-id="{{ $data->id }}">
														<td class="survey-link" data-id="{{ $data->id }}">{{ $data->name }}</td>
														<td>{{ $data->type }}</td>
														<td>{{ $data->created_at }}</td>
														<td>{{ $data->updated_at }}</td>
														<td>
															<input type="button" class="btn btn-danger" name="Delete" value="Delete">
															@if ($data->is_active == 0)
																<input type="button" class="btn btn-success" name="Launch" value="Launch">
																<input type="button" class="btn btn-warning hidden" name="Hold" value="Hold">
															@else
																<input type="button" class="btn btn-success hidden" name="Launch" value="Launch">
																<input type="button" class="btn btn-warning" name="Hold" value="Hold">
															@endif
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
	<script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>

	<script type="text/javascript" src="{{ asset('assets/js/app.survey.js') }}"></script>

@endsection