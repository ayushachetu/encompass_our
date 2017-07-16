@extends('layouts.default')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/survey-reports.css') }}" />
@endsection

@section('content')
	<div class="piluku-preloader text-center">
		<div class="loader">Loading...</div>
	</div>
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
								<h3><i class="icon ti-bar-chart"></i> Client Cumulative</h3>
								<hr>
								@if ($show_data != 1)
									<div class="row filters-container">
										<div class="col-sm-6">
											<div id="report-range">
												<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
												<span></span> <b class="caret"></b>
											</div>
										</div>
										<div class="col-sm-6">
											<select class="form-control" id="primary-filter">
												<option value="" disabled selected>Filter By</option>
												<option value="Job">Job</option>
												<option value="Manager">Manager</option>
												<option value="Industry">Industry</option>
											</select>
										</div>
									</div>
									<br>
									<div class="text-center" style="cursor: not-allowed;">
										<input type="button" class="btn btn-success" name="get-report" value="Get Report" disabled />
									</div>
									<hr>
								@endif

								@if ($show_data == 1)
									<div class="row filters-container">
										<div class="col-sm-4">
											<div id="report-range">
												<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
												<span></span> <b class="caret"></b>
											</div>
										</div>
										<div class="col-sm-4">
											<select class="form-control" id="primary-filter">
												<option value="" disabled>Filter By</option>
												@if ($primary_filter == 'Job')
													<option value="Job" selected>Job</option>
													<option value="Manager">Manager</option>
													<option value="Industry">Industry</option>
												@elseif ($primary_filter == 'Manager')
													<option value="Job">Job</option>
													<option value="Manager" selected>Manager</option>
													<option value="Industry">Industry</option>
												@elseif ($primary_filter == 'Industry')
													<option value="Job">Job</option>
													<option value="Manager">Manager</option>
													<option value="Industry" selected>Industry</option>
												@endif
											</select>
										</div>
										<div class="col-sm-4">
											<select class="form-control" id="secondary-filter" multiple>
												<option value="" disabled>Select {{ $primary_filter }}</option>
												@if ($primary_filter == 'Manager')
													@foreach ($secondary_filter as $filter)
														<option value="{{ $filter->manager_id }}" selected>{{ $filter->first_name . ' ' . $filter->last_name }}</option>
													@endforeach
												@else
													@foreach ($secondary_filter as $filter)
														<option value="{{ $filter }}" selected>{{ $filter }}</option>
													@endforeach
												@endif
											</select>
										</div>
									</div>
									<br>
									<div class="text-center">
										<input type="button" class="btn btn-success" name="get-report" value="Get Report" />
									</div>
									<hr>

									<div class="report-data ">
										<div class="row">
											<div class="col-sm-6">
												<img src="http://encompass.chetu.local:8080/assets/img/Encompass-Logo.jpg" alt="logo" width="200" />
											</div>
											<div class="col-sm-6 text-right">
												<img src="http://encompass.chetu.local:8080/assets/img/Encompass-Logo.jpg" alt="logo" width="200" />
											</div>
										</div>
										<hr>

										<div class="progress-wrap">
											<div class="row">
												<div class="col-sm-2"><h5>Service Standards</h5></div>
												<div class="col-sm-8 col-md-9">
													<div class="progress">
														<div class="progress-bar bg-facility" role="progressbar" style="width: {{ $standard }}%;"></div>
													</div>
												</div>
												<div class="col-sm-2 col-md-1 text-right">
													@if (round($standard) > 95)
														<div class="par-circle bg-dark-green">
													@elseif (round($standard) >= 85)
														<div class="par-circle bg-lime-green">
													@elseif (round($standard) >= 75)
														<div class="par-circle bg-yellow">
													@else
														<div class="par-circle bg-red">
													@endif
															{{ round($standard) }}%
														</div>
												</div>
											</div>
										</div>
										<hr>

										@foreach ($filter_scores as $filter => $data)
											<div class="progress-wrap">
												<div class="row">
													<div class="col-sm-2">
														<h5>{{ $filter }}</h5>
													</div>
													<div class="col-sm-8 col-md-9">
														<div class="progress">
															<div class="progress-bar" role="progressbar" style="width: {{ $data['total'] / $data['count'] }}%; "></div>
														</div>
													</div>
													<div class="col-sm-2 col-md-1 text-right">
														@if (round($data['total'] / $data['count']) > 95)
															<div class="par-circle bg-dark-green">
														@elseif (round($data['total'] / $data['count']) >= 85)
															<div class="par-circle bg-lime-green">
														@elseif (round($data['total'] / $data['count']) >= 75)
															<div class="par-circle bg-yellow">
														@else
															<div class="par-circle bg-red">
														@endif
																{{ round($data['total'] / $data['count']) }}%
															</div>
													</div>
												</div>
											</div>
											<hr>
										@endforeach

										@foreach ($survey_scores as $survey => $data)
											<div class="progress-wrap">
												<div class="row">
													<div class="col-sm-2"><h5>{{ $survey }}</h5></div>
													<div class="col-sm-8 col-md-9">
														<div class="progress">
															<div class="progress-bar" role="progressbar" style="width: {{ $data['total'] / $data['count'] }}%; background: {{ $data['bgcolor'] }}"></div>
														</div>
													</div>
													<div class="col-sm-2 col-md-1 text-right">
														@if (round($data['total'] / $data['count']) > 95)
															<div class="par-circle bg-dark-green">
														@elseif (round($data['total'] / $data['count']) >= 85)
															<div class="par-circle bg-lime-green">
														@elseif (round($data['total'] / $data['count']) >= 75)
															<div class="par-circle bg-yellow">
														@else
															<div class="par-circle bg-red">
														@endif
																{{ round($data['total'] / $data['count']) }}%
															</div>
													</div>
												</div>
											</div>
											<hr>
										@endforeach

										@foreach ($survey_area_scores as $survey_name => $area_scores)
											<div class="panel panel-default panel-black">
												<div class="panel-heading">
													<h4 class="panel-title">
														<a data-toggle="collapse" href="#{{ $survey_scores[$survey_name]['id'] }}">{{ $survey_name }}</a>
													</h4>
													<span class="agre">{{ round($survey_scores[$survey_name]['total'] / $survey_scores[$survey_name]['count']) }}%</span>
												</div>
												<div id="{{ $survey_scores[$survey_name]['id'] }}" class="panel-collapse collapse in">
													<div class="panel-body">
														@foreach ($area_scores as $area => $score)
															<div class="progress-wrap">
																<div class="row">
																	<div class="col-sm-2"><h5>{{ $area }}</h5></div>
																	<div class="col-sm-8 col-md-9">
																		<div class="progress">
																			<div class="progress-bar" role="progressbar" style="width: {{ $score }}%; background: {{ $survey_scores[$survey_name]['bgcolor'] }};"></div>
																		</div>
																	</div>
																	<div class="col-sm-2 col-md-1 text-right">
																		@if (round($score) > 95)
																			<div class="par-circle bg-dark-green">
																		@elseif (round($score) >= 85)
																			<div class="par-circle bg-lime-green">
																		@elseif (round($score) >= 75)
																			<div class="par-circle bg-yellow">
																		@else
																			<div class="par-circle bg-red">
																		@endif
																				{{ round($score) }}%
																			</div>
																	</div>
																</div>
															</div>
															<hr>
														@endforeach
													</div>
												</div>
											</div>
										@endforeach

										<div class="panel panel-default panel-black">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a data-toggle="collapse" href="#appa-by-area">APPA By Area</a>
												</h4>
												<span class="agre">{{ round($area_ratings_agg, 1) }}</span>
											</div>
											<div id="appa-by-area" class="panel-collapse collapse in">
												<div class="panel-body">
													@foreach ($area_ratings as $data)
														<div class="progress-wrap">
															<div class="row">
																<div class="col-sm-2"><h5>{{ $data->area }}</h5></div>
																<div class="col-sm-8 col-md-9">
																	<div class="progress">
																		<div class="progress-bar bg-team" role="progressbar" style="width: {{ $data->rating * 20 }}%;"></div>
																	</div>
																</div>
																<div class="col-sm-2 col-md-1 text-right">
																	<div class="par-circle">
																		{{ round($data->rating, 1) }}
																	</div>
																</div>
															</div>
														</div>
														<hr>
													@endforeach
												</div>
											</div>
										</div>

										<div class="panel panel-default panel-black">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a data-toggle="collapse" href="#survey-count">Survey Count By Respondent</a>
												</h4>
												<span class="agre">{{ $emp_surveys_total['total'] }}</span>
											</div>
											<div id="survey-count" class="panel-collapse collapse in">
												<div class="panel-body">
													@foreach ($emp_surveys as $data)
														<div class="progress-wrap">
															<div class="row">
																<div class="col-sm-2"><h5>Emp #{{ $data->user_id }}</h5></div>
																<div class="col-sm-8 col-md-9">
																	<div class="progress">
																		<div class="progress-bar bg-emp" role="progressbar" style="width: {{ $emp_surveys_total[$data->user_id] }}%;">
																		</div>
																	</div>
																</div>
																<div class="col-sm-2 col-md-1 text-right">
																	<div class="par-circle">
																		{{ $data->count }}
																	</div>
																</div>
															</div>
														</div>
														<hr>
													@endforeach
												</div>
											</div>
										</div>
									</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/wow.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/jquery.loadmask.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/jquery.accordion.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>

	<script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/daterangepicker/moment.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/daterangepicker/daterangepicker.js') }}"></script>

	<script type="text/javascript" src="{{ asset('assets/js/app.survey-reports.js') }}"></script>

	<script type="text/javascript">
		$(window).load(function () {
			$('.piluku-preloader').addClass('hidden');
		});
	</script>

	@if ($show_data == 1)
		<script type="text/javascript">
			$(document).ready(function () {
				$('#secondary-filter').select2();

				$('#report-range span').html("{{ $report_range }}");
			});
		</script>
	@endif
@endsection