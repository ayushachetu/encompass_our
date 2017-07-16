@extends('layouts.default')
@section('styles')
<link href="{{ asset('assets/css/animated-masonry-gallery.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('assets/css/rotated-gallery.css') }}" rel="stylesheet" type="text/css" >
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
				<i class="icon ti-layout-media-left page_header_icon"></i>
				<span class="main-text">Data</span>
			</div>
		</div>
		<!-- /pageheader -->  
		<div class="main-content">
			<!-- *** Infobox 5 *** -->
			<div class="paneltype-heading">Process Data</div>
			<!--row-->
			<div class="row">
				<div class="col-md-3 col-xs-12 col-sm-6 ">
					<div class="info-five primarybg-info">
						<div class="logo"><i class="ti-layers"></i></div>
						<p>Billable hours Data</p>
						<p>
							<a href="/data/process_billable_hours_data" class="btn btn-default">Process Folder</a>
						</p>
						<br/>
						<p>
							<a href="/data/view_billable_hours_data" class="btn btn-warning">View Data</a>
						</p>
					</div>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-6 ">
					<div class="info-five redbg-info">
						<div class="logo"><i class="ti-layers"></i></div>
						<p>Job Data</p>
						<p>
							<a href="/data/process_job_data" class="btn btn-default">Process Folder</a>
						</p>
						<br/>
						<p>
							<a href="/data/view_job_data" class="btn btn-warning">View Data</a>
						</p>
					</div>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-6 ">
					<div class="info-five primarybg-info">
						<div class="logo"><i class="ti-layers"></i></div>
						<p>Expense Data</p>
						<p>
							<a href="/data/process_expense_data" class="btn btn-default">Process Folder</a>
						</p>
						<br/>
						<p>
							<a href="/data/view_expense_data" class="btn btn-warning">View Data</a>
						</p>
					</div>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-6 ">
					<div class="info-five redbg-info">
						<div class="logo"><i class="ti-layers"></i></div>
						<p>Budget Data</p>
						<p>
							<a href="/data/process_budget_data" class="btn btn-default">Process Folder</a>
						</p>
						<br/>
						<p>
							<a href="/data/view_budget_data" class="btn btn-warning">View Data</a>
						</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3 col-xs-12 col-sm-6 ">
					<div class="info-five primarybg-info">
						<div class="logo"><i class="ti-layers"></i></div>
						<p>Account Data</p>
						<p>
							<a href="/data/process_account_data" class="btn btn-default">Process Folder</a>
						</p>
						<br/>
						<p>
							<a href="/data/view_account_data" class="btn btn-warning">View Data</a>
						</p>
					</div>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-6 ">
					<div class="info-five redbg-info">
						<div class="logo"><i class="ti-layers"></i></div>
						<p>Square Feet Data</p>
						<p>
							<a href="/data/process_feet_data" class="btn btn-default">Process Folder</a>
						</p>
						<br/>
						<p>
							<a href="/data/view_job_data" class="btn btn-warning">View Data</a>
						</p>
					</div>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-6 ">
					<div class="info-five primarybg-info">
						<div class="logo"><i class="ti-layers"></i></div>
						<p>Labor Tax Data</p>
						<p>
							<a href="/data/process_labor_tax_data" class="btn btn-default">Process Folder</a>
						</p>
						<br/>
					</div>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-6 ">
					<div class="info-five redbg-info">
						<div class="logo"><i class="ti-layers"></i></div>
						<p>Budget Monthly Data</p>
						<p>
							<a href="/data/process_budget_monthly_data" class="btn btn-default">Process Folder</a>
						</p>
						<br/>
						
					</div>
				</div>
			</div>
			<!-- row -->    	 
	
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
