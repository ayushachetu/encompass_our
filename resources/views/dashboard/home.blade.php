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

		<div class="main-content">
	       <div class="row">
		<!-- *** OVERLAPPING BARS ON MOBILE *** -->
			<div class="col-md-6 col-sm-6">
				<!--*** Image Text Widget ***-->
				<div class="panel panel-piluku">
					<div class="image-text-widget">
						<div class="widget-image">
							<div class="widget-image-opacity"></div>
							<div class="widget-text">
								Control Panel
							</div>
						</div>
						<div class="widget-image-text">
							<div class="widget-heading">Welcome</div>
							<a href="#" class="btn btn-primary btn-orange">Profile</a>
						</div>
					</div>
				</div>
				<!--*** /Image Text Widget ***-->
				<!-- /panel -->
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
  <script type="text/javascript" src="{{ asset('assets/js/chartist/chartist.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/chartist/chartist.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/chartist/overlapping-bars.js') }}"></script>
  

@endsection
