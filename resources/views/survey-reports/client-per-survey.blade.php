@extends('layouts.default')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}">
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
								<h3><i class="icon ti-bar-chart"></i> Client Per Survey</h3>
								<hr>

								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon bg">
													<i class="ion-android-search"></i>
												</span>
												<select class="job_search form-control">
													<option value="" disabled selected>Select Job</option>
													@foreach ($jobs_list as $job)
														<option value="#{{ $job->job_number }} - {{ $job->job_description }}"> #{{ $job->job_number }} - {{ $job->job_description }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<select class="form-control inspection-by" disabled>
											<option value="" disabled selected>Inspection Done By</option>
										</select>
									</div>
								</div>
								<hr>

								<div class="surveys-list table-responsive hidden">
									<table class="table table-hover">
										<thead>
											<tr>
												<th>Survey</th>
												<th>Areas</th>
												<th>Start Date</th>
												<th>End Date</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>

								@php( $total_score = 0 )
								@php( $total_count = 0 )
								@if ($show_data == 1)
									<div class="report-data">
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
											<div class="col-sm-6 col-sm-offset-3">
												<div class="well well-sm text-center">
													<h4 class="facility-heading">Facility Name</h4>
													<p class="facility-details">{{ $facility }}</p>
												</div>
											</div>
										</div>
										<div class="progress-wrap facility-standard">
											<div class="row">
												<div class="col-sm-2"><h5>Facility Standard</h5></div>
												<div class="col-sm-8 col-md-9">
													<div class="progress">
														<div class="progress-bar bg-facility" role="progressbar" style="width: 0%;"></div>
													</div>
												</div>
												<div class="col-sm-2 col-md-1 text-right">
													<div class="par-circle">
														70%
													</div>
												</div>
											</div>
										</div>
										<hr>
										@php($sig = '')
										@foreach ($survey_data as $data)
											@php
												$total_score += round($data->score);
												$total_count += 1;
												$sig = url('assets\survey-images\signature\\'.$data->signature);
												$matrix = json_decode($data->matrix);
												$images = json_decode($data->images);
												$id = str_replace(' ', '-', str_replace('/', '', $data->area));
											@endphp
											<div class="panel panel-default panel-black">
												<div class="panel-heading">
													<h4 class="panel-title">
														<a data-toggle="collapse" href="#{{ $id }}">{{ $data->area }}</a>
													</h4>
													<span class="agre">{{ round($data->score) }}%</span>
												</div>
												<div id="{{ $id }}" class="panel-collapse collapse in">
													<div class="panel-body">
														@foreach ($matrix as $option => $value)
															@php
																if ($value == 'Poor') { $s = 70; $bg = 'bg-red'; }
																elseif ($value == 'Fair') { $s = 80; $bg = 'bg-yellow'; }
																elseif ($value == 'Good') { $s = 90; $bg = 'bg-lime-green'; }
																else { $s = 100; $bg = 'bg-dark-green'; }
															@endphp
															<div class="progress-wrap">
																<div class="row">
																	<div class="col-sm-2"><h5>{{ $option }}</h5></div>
																	<div class="col-sm-8 col-md-9">
																		<div class="progress">
																			<div class="progress-bar bg-quality" role="progressbar" style="width: {{ $s }}%;"></div>
																		</div>
																	</div>
																	<div class="col-sm-2 col-md-1 text-right">
																		<div class="par-circle {{ $bg }}">
																			{{ $s }}%
																		</div>
																	</div>
																</div>
															</div>
															<hr>
														@endforeach

														<div class="row image-section">
															@foreach ($images as $img_com)
																<div class="col-md-3">
																	<div class="thumbnail">
																		@foreach ($img_com as $img => $com)
																			@php
																				$img = url('assets\survey-images\original\\'.$img);
																			@endphp
																			<img class="btn-block" src="{{ $img }}" alt="{{ $img }}">
																			<div class="caption">
																				<p>{{ $com }}</p>
																			</div>
																		@endforeach
																	</div>
																</div>
															@endforeach
														</div>
														<hr>

														<div class="form-group">
															<textarea class="form-control" readonly>{{ $data->comments }}</textarea>
														</div>
														<hr>

														<div class="progress-wrap">
															<div class="row">
																<div class="col-sm-2"><h5>APAA</h5></div>
																<div class="col-sm-8 col-md-9">
																	<div class="progress">
																		<div class="progress-bar bg-quality" role="progressbar" style="width: {{ $data->rating_level * 20 }}%;"></div>
																	</div>
																</div>
																<div class="col-sm-2 col-md-1 text-right">
																	<div class="par-circle">
																		{{ $data->rating_level }}
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										@endforeach

										<div class="row">
											<div class="col-md-4 col-md-offset-4">
												<img src="{{ $sig }}" class="img-thumbnail img-responsive" alt="{{ $sig }}">
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

	<script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>

	<script type="text/javascript">
		$(document).ready(function () {
			var total_score = "{{ $total_score }}";
			var total_count = "{{ $total_count }}";
			if (total_count > 0) {
				$(".facility-standard .progress-bar").css('width', (total_score/total_count)+"%");
				$(".facility-standard .par-circle").text((total_score/total_count)+"%");
			}

			if ("{{ $show_data }}" == "1") {
				var facility = "{{ $facility }}";
				var auditors = {!! json_encode($auditors) !!};
				var user = "{{ $user }}";

				$('.job_search').val(facility).trigger('change');
				enable_auditors(auditors);
				$('.inspection-by').val(user);
			}

			$(".job_search").select2();
			$(".inspection-by").select2();

			$(".job_search").change(function () {
				var data = {};
				data._token = $('input[name="_token"]').val();
				data.job = $(this).val();

				$.ajax({
					url: '/survey-reports/client-per-survey/auditors',
					type: 'post',
					data: data,
					success: function (response) {
						if (response.auditors !== undefined && response.auditors.length > 0)
							enable_auditors(response.auditors);
						else
							sweetAlert("Oops...", "No data found for selected facility!", "error");
					},
					error: function (response) {
						sweetAlert("Oops...", "Something went wrong!", "error");
					}
				});
			});

			$(".inspection-by").change(function () {
				var data = {};
				data._token = $('input[name="_token"]').val();
				data.user = $(this).val();
				if (data.user == null || data.user == "")
					return;

				$.ajax({
					url: '/survey-reports/client-per-survey/list',
					type: 'post',
					data: data,
					success: function (response) {
						show_surveys(response.surveys);
					},
					error: function (response) {
						sweetAlert("Oops...", "Something went wrong!", "error");
					}
				});
			});
		});

		function enable_auditors(auditors) {
			var html = '<option value="" disabled selected>Inspection Done By</option>';
			for (var i = 0; i < auditors.length; i++)
				html += '<option value="'+auditors[i].user_id+'">'+auditors[i].first_name+' '+auditors[i].last_name+'</option>';
			$('.inspection-by').html(html).trigger('change');
			$('.inspection-by').prop("disabled", false);
		}

		function show_surveys(surveys) {
			var html = '';
			for (var i = 0; i < surveys.length; i++) {
				if (surveys[i].area != 'All')
					surveys[i].area = 'Custom';
				if (surveys[i].status != 'ended')
					surveys[i].updated_at = 'In-progress';
				html += '<tr data-id="'+surveys[i].id+'">'+
									'<td>'+surveys[i].name+'</td>'+
									'<td>'+surveys[i].area+'</td>'+
									'<td>'+surveys[i].created_at+'</td>'+
									'<td>'+surveys[i].updated_at+'</td>'+
								'</tr>';
			}

			$('.surveys-list table tbody').html(html);
			$('.surveys-list').removeClass('hidden');
			$('.surveys-list table tbody tr').click(function () {
				window.location = '/survey-reports/client-per-survey?s='+$(this).attr('data-id');
			});
		}
	</script>
@endsection