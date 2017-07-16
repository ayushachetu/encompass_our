@extends('layouts.default')
@section('styles')
<link href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}" rel="stylesheet" type="text/css" >
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

			<!-- Second Row -->
		<div class="row grid">
			<!-- /col-md-9 -->
			<div class="col-md-12">
				<!-- panel -->
				<div class="panel panel-piluku">
					<div class="panel-body">
						{!! csrf_field() !!}
						<div class="row">
							<div class="col-md-12">
								<h3><i class="icon  ion-ios-keypad-outline"></i> Tools</h3>	
							</div>
						</div>
					</div>
				</div>
				<div id="wrapper-tools">
					<div class="row">
						<div class="col-md-3 col-sm-6 col-xs-12">
							<a href="/manager-form" target="_blank" class="btn btn-success btn-radius btn-block">Job Request</a><br/>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<a href="/talent-form" target="_blank" class="btn btn-success btn-radius btn-block">Talent Change Request</a><br/>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<a href="/training-form" target="_blank" class="btn btn-success btn-radius btn-block">Training Registration</a><br/>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<a href="https://app.jazz.co/home/signin" target="_blank" class="btn btn-success btn-radius btn-block">Talent Recruitment</a><br/>
						</div>
					</div>
					<hr/>
					<div class="row">
						<div class="col-md-3 col-sm-6 col-xs-12">
							<a href="/exit-interview-form" target="_blank" class="btn btn-success btn-radius btn-block">Exit Interview</a><br/>
						</div>
					</div>
				</div>
				<!-- /panel -->
			</div>
			<!-- /col-md-9 -->
		</div>
		<!-- /Second row -->
			
		</div>
	</div>  
	</div>
	<!-- wrapper -->
@endsection
@section('scripts')
  <script>
    jQuery(window).load(function () {
      $('.piluku-preloader').addClass('hidden');
    });
  </script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/wow.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.loadmask.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.accordion.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/materialize.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/build/d3.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/nvd3/nv.d3.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/sparkline.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/bic_calendar.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/widgets.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>

  <script type="text/javascript" src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>


  <script type="text/javascript" src="{{ asset('assets/js/jquery.countTo.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
  
@endsection
