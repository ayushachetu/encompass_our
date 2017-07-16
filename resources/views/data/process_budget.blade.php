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
				<span class="main-text">Data Budget</span>
			</div>
		</div>
		<div class="panel-body">
			<ol class="breadcrumb">
				<li><a href="/data_dashboard">Data</a></li>
				<li class="active">Budget</li>
			</ol>		
		</div>
		<!-- /pageheader -->  
		<div class="main-content">
			<!-- *** Infobox 5 *** -->
			<div class="paneltype-heading">Process Data Budget</div>
			<!--row-->
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-piluku">
						<div class="panel-body">
							<table class="table table-bordered">
								@foreach ($files as $item)
									<tr class="table-row">
										<td>{{ $item->file_name }}</td>
										<td>{{ $item->created_at }}</td>
									</tr>
								@endforeach
							</table>
						</div>
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
