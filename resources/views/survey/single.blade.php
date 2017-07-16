@extends('layouts.default')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/survey.css') }}">
@endsection

@section('content')
	<div class="piluku-preloader text-center">
		<div class="loader">Loading...</div>
	</div>
	@include('survey.add-question')
	@include('survey.edit')
	@include('survey.preview-question')
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
									<div class="col-sm-9 col-xs-12">
										<h3><i class="icon ti-bar-chart"></i> {{ $survey_data->name }}</h3>
										<h4>{{ $survey_data->description }}</h4>
										<h5>
											<span>Type: {{ $survey_data->type }}</span>
										</h5>
										<div>
											<button type="button" class="btn btn-sm btn-default" id="show-edit-survey">
												<span class="glyphicon glyphicon-pencil"></span>
											</button>
										</div>
									</div>
									<div class="col-sm-3 col-xs-12 text-right">
										<button class="btn btn-lg btn-block btn-evaluation" id="import-ques-btn"><span>Import Questions</span></button>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-sm-12">
									@if (count($survey_questions_data) > 0)
										<table class="table table-hover" id="survey-questions-list">
											<thead>
												<th>Question</th>
												<th>Actions</th>
											</thead>
											<tbody>
												@foreach ($survey_questions_data as $data)
													<tr data-id="{{ $data->id }}">
														<td>{{ $data->name }}</td>
														<td><i class="fa fa-2x fa-trash"></i></td>
													</tr>
												@endforeach
											</tbody>
										</table>
									@else
										<p class="f-s-18">Kindly import questions in the survey.</p>
									@endif
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

	<script type="text/javascript" src="{{ asset('assets/js/app.survey-single.js') }}"></script>

	<script type="text/javascript">
		function options_data(options) {
			var options_data = {!! $options !!};
			var data = {};
			for (var i = 0; i < options.length; i++)
				data[options[i]] = options_data[options[i]];
			return data;
		}
	</script>
@endsection