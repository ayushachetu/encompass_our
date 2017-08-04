@extends('layouts.default')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datepicker3.css') }}" />
<style type="text/css">
	.select2-container {
		margin-bottom: 10px;
	}
</style>
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
										Create Trigger
									</span>
								</h3>
								<hr>
								<div class="row">
									<div class="col-sm-2">
										<label class="triggers-label" for="recipients-by-roles">Recipients</label>
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
										<label class="triggers-label" for="on-action">On Action</label>
									</div>
									<div class="col-sm-10">
										<select class="form-control" id="on-action">
											<option value="survey-completed">Survey completed</option>
											<option value="low-score-on-survey">Low score on survey</option>
											<option value="low-score-on-a-question">Low score on a question</option>
											<option value="low-ratings-score">Low ratings score</option>
										</select>
									</div>
								</div>
								<br>

								<div class="row">
									<div class="col-sm-2">
										<label class="triggers-label" for="">Execution Time</label>
									</div>
									<div class="col-sm-2">
										<input type="radio" name="execution-radio" id="Immediately" value="Immediately">
										<label for="Immediately"><span></span>Immediately</label>
									</div>
									<div class="col-sm-2">
										<input type="radio" name="execution-radio" value="Custom" id="Custom">
										<label for="Custom"><span></span>Custom</label>
									</div>
									<div class="col-sm-3">
										<input type="number" min="0" max="59" class="form-control hidden" id="execution-time" placeholder="* Number">
									</div>
									<div class="col-sm-3">
										<select class="form-control hidden" name="execution-unit" id="execution-unit">
											<option value="minutes">Minute(s)</option>
											<option value="hours">Hour(s)</option>
										</select>
									</div>
								</div>
								<br>

								<div class="row">
									<div class="col-sm-2">
										<label class="triggers-label" for="data-to-send">Data to send</label>
									</div>
									<div class="col-sm-10">
										<select class="form-control" id="data-to-send" multiple>
											<option value="all">All survey data</option>
											<option value="score">Score</option>
											<option value="comments">Comments</option>
											<option value="images">Images</option>
											<option value="ratings">Ratings</option>
										</select>
									</div>
								</div>
								<br>

								<div class="row">
									<div class="col-sm-2">
										<label class="triggers-label" for="custom-message">Custom Message</label>
									</div>
									<div class="col-sm-10">
										<textarea class="form-control" id="custom-message" placeholder="Write custom message"></textarea>
									</div>
								</div>
								<br>

								<div class="row">
									<div class="col-sm-2">
										<label class="triggers-label" for="jobs-list">Jobs</label>
									</div>
									<div class="col-sm-10">
										<select class="form-control" id="jobs-list" multiple>
											@foreach($jobs as $job)
												<option value="{{ $job->job_number }}"> #{{$job->job_number}} - {{$job->job_description}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<br>

								<div class="row">
									<div class="col-sm-2">
									</div>
									<div class="col-sm-10">
										<input type="button" class="btn btn-success" id="create-trigger" value="Create Trigger">
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

  <script type="text/javascript" src="{{ asset('assets/js/app.triggers.js') }}"></script>

	<script type="text/javascript">
		$(window).load(function () {
			$('.piluku-preloader').addClass('hidden');
		});
	</script>
@endsection