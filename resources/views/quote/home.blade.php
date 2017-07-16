@extends('layouts.default')
@section('styles')
<link href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('assets/css/jtree.css') }}" rel="stylesheet" type="text/css" >
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

		<!-- Page Header -->
		<div class="page_header">
			<div class="pull-left">
				<i class="icon ti-comment-alt page_header_icon"></i>
				<span class="main-text">Quotes Dashboard</span>
			</div>
			<div class="right pull-right">
				<a href="/quotes/create" class="btn btn-primary btn-lg"><span class="ion-ios-plus"></span> Add New</a>
			</div>
		</div>
		<!-- /pageheader -->  
		<div class="main-content">
			<!--row-->
			<div class="row">
				<div class="col-md-3 col-xs-12 col-sm-6 ">
					<div class="info-five orangebg-info">
						<div class="logo"><i class="ti-pencil-alt"></i></div>
						<p>Drafts</p>
						<p>
							<a href="/quotes/list/1" class="btn btn-default">View</a>
						</p>
					</div>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-6 ">
					<div class="info-five primarybg-info">
						<div class="logo"><i class=" ti-settings"></i></div>
						<p>Sent Quotes</p>
						<p>
							<a href="/quotes/list/2" class="btn btn-default">View</a>
						</p>
					</div>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-6 ">
					<div class="info-five greenbg-info">
						<div class="logo"><i class="ti-check-box"></i></div>
						<p>Approved Quotes</p>
						<p>
							<a href="/quotes/list/3" class="btn btn-default">View</a>
						</p>
					</div>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-6 ">
					<div class="info-five redbg-info">
						<div class="logo"><i class="ti-close"></i></div>
						<p>Denied Quotes</p>
						<p>
							<a href="/quotes/list/4" class="btn btn-default">View</a>
						</p>
					</div>
				</div>
				@if(Auth::user()->getRole()==Config::get('roles.ADMIN') || Auth::user()->getRole()==Config::get('roles.DIR_POS'))
				<div class="col-md-3 col-xs-12 col-sm-6 ">
					<div class="info-five defaultbg-info">
						<div class="logo"><i class="ti-import"></i></div>
						<p>
							<a href="/quotes/export" class="btn btn-default">Export</a>
						</p>
					</div>
				</div>
				@endif
			</div>  	 
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
  <script type="text/javascript" src="{{ asset('assets/js/jquery.countTo.js') }}"></script>

@endsection
