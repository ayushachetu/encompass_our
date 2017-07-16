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
								<h3><i class="icon  ion-ios-keypad-outline"></i> Payroll</h3>	
							</div>
							<a href="/payroll-tools" class="btn btn-green"><i class="ti-angle-left"></i> Return to List</a>
						</div>
						<form method="POST" action="/payroll-request-submit" id="request-form" enctype="multipart/form-data">
							{!! csrf_field() !!}
							<div id="main-form">
								
	                            <div class="row">
	                              <div class="col-md-6 col-md-offset-3 col-sm-12">	
	                            	<h3>DOWNLOAD</h3>
	                              </div>
	                            </div>  
	                            <div class="row">
		                            <div class="col-md-6 col-md-offset-3 col-sm-12">
		                                <a href="/payroll-get-file/{{$name_file}}" class="btn btn-success btn-block" id="btn-new-request">TEXT FILE WOTC</a>    
		                            </div>
		                                                   
		                        </div> 
		                        <br/><br/><br/>
							</div>
						</form>
						
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
  <script type="text/javascript" src="{{ asset('assets/js/bootstrap-datepicker.js') }}"></script>	
  


  <script type="text/javascript" src="{{ asset('assets/js/form-validation/jquery.form-validator.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/form-validation.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.maskedinput.js') }}"></script>
  
  
@endsection
