@extends('layouts.default')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/survey-reports.css') }}">
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
								<div class="row">
									<div class="col-sm-12">
										<h3><i class="icon ti-bar-chart"></i> Survey Reports</h3>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-sm-12">
										<a class="report-link" href="/survey-reports/client-cumulative">Client Cumulative</a>

										<a class="report-link" href="/survey-reports/client-per-survey">Client Per Survey</a>

										<a class="report-link" href="/survey-reports/manager-supervisor-issue-notification">Manager Supervisor Issue Notification</a>
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
@endsection