@extends('layouts.default')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datepicker3.css') }}" />
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
								<h3>
									<span>
										<i class="icon ti-bar-chart"></i> 
										Client Cumulative
									</span>

									<span class="dropdown hidden" id="scheduling-dropdown" style="float: right;">
										<button class="btn btn-default dropdown-toggle btn-block" type="button" id="scheduling-menu" data-toggle="dropdown">
											Scheduling
										</button>
										<ul class="dropdown-menu" role="menu" aria-labelledby="scheduling-menu">
											<li role="presentation"><a role="menuitem" tabindex="-1" href="#">New</a></li>
											<li role="presentation"><a role="menuitem" tabindex="-1" href="/scheduled-jobs/list">Existing</a></li>
										</ul>
									</span>
								</h3>
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
												<option value="Major Account">Major Account</option>
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
													<option value="Major Account">Major Account</option>
												@elseif ($primary_filter == 'Manager')
													<option value="Job">Job</option>
													<option value="Manager" selected>Manager</option>
													<option value="Industry">Industry</option>
													<option value="Major Account">Major Account</option>
												@elseif ($primary_filter == 'Industry')
													<option value="Job">Job</option>
													<option value="Manager">Manager</option>
													<option value="Industry" selected>Industry</option>
													<option value="Major Account">Major Account</option>
												@elseif ($primary_filter == 'Major Account')
													<option value="Job">Job</option>
													<option value="Manager">Manager</option>
													<option value="Industry">Industry</option>
													<option value="Major Account" selected>Major Account</option>
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
												@elseif ($primary_filter == 'Major Account')
													@foreach ($secondary_filter as $filter)
														<option value="{{ $filter->major_account_id }}" selected>{{ $filter->name }}</option>
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

									<div id="scheduler-filter-wrapper">
										<div class="row">
											<div class="col-sm-11">
												<div class="row">
													<div class="col-sm-2">
														<label class="scheduling-label">Frequency</label>
													</div>
													<div class="col-sm-10">
														<div class="is-recursive-container">
															<select class="form-control" id="is-recursive">
																<option selected disabled value="">Recursive/Once</option>
																<option value="recursive">Recursive</option>
																<option value="Once">Once</option>
															</select>
														</div>
														<div class="frequency-container">
															<select class="form-control" id="frequency" disabled>
																<option selected disabled value="">Send Every</option>
																<option value="Weekly">Week</option>
																<option value="Monthly">Month</option>
																<option value="Yearly">Year</option>
																<option value="Once">N/A</option>
															</select>
														</div>
														<div class="send-on-container">
															<input type="text" class="form-control" id="send-on" placeholder="Send On" disabled>
														</div>
													</div>
												</div>
												<br>

												<div class="row">
													<div class="col-sm-2">
														<label class="scheduling-label">Recipients</label>
													</div>
													<div class="col-sm-10">
														<select class="form-control" id="recipients-by-roles" multiple>
															@foreach ($recipients_by_role as $recipient)
																<option value="{{ $recipient->id }}">{{ $recipient->name }}</option>
															@endforeach
														</select>
														<br>

														<select class="form-control" id="custom-recipients" multiple></select>
													</div>
												</div>
												<br>

												<div class="row">
													<div class="col-sm-2">
														<label class="scheduling-label">Custom Message</label>
													</div>
													<div class="col-sm-10">
														<textarea class="form-control" id="custom-message" rows="2" placeholder="Write message for recipients" style="line-height: 30px;"></textarea>
													</div>
												</div>
												<br>

												<div class="row">
													<div class="col-sm-offset-2 col-sm-10">
														<input type="button" class="btn btn-success" name="create-scheduler" value="Create Scheduler" />
													</div>
												</div>
											</div>
										</div>
									<hr>
									</div>

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

										<div class="row">
											<div class="col-sm-12 text-center">
												<div class="color-info">
													<div class="par-circle bg-red"></div>
													<div>< 70%</div>
													<div>problematic</div>
												</div>

												<div class="color-info">
													<div class="par-circle bg-orange"></div>
													<div>70-80%</div>
													<div>deficient</div>
												</div>

												<div class="color-info">
													<div class="par-circle bg-yellow"></div>
													<div>80-90%</div>
													<div>average</div>
												</div>

												<div class="color-info">
													<div class="par-circle bg-lime-green"></div>
													<div>90-95%</div>
													<div>good</div>
												</div>

												<div class="color-info">
													<div class="par-circle bg-dark-green"></div>
													<div>> 95%</div>
													<div>outstanding</div>
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

	<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/daterangepicker/moment.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/daterangepicker/daterangepicker.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/bootstrap-datepicker.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/code/highcharts.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/code/modules/exporting.js') }}"></script>

	<script type="text/javascript" src="{{ asset('assets/js/app.survey-reports.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/app.survey-reports-charts.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/app.survey-scheduling.js') }}"></script>

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

				chart_service_standards({{ $standard }});

				chart_filter_scores("{{ json_encode($filter_scores) }}");

				chart_survey_scores("{{ json_encode($survey_scores) }}");

				chart_survey_area_scores("{{ json_encode($survey_area_scores) }}");

				chart_area_ratings("{{ json_encode($area_ratings) }}", {{ $area_ratings_agg }});

				chart_respondents("{{ json_encode($emp_surveys) }}", {{ $emp_surveys_total['total'] }});

				$('#scheduling-dropdown').removeClass('hidden');
				attach_scheduling_events();
			});
		</script>
	@endif
@endsection