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
								<h3><i class="icon  ion-ios-keypad-outline"></i> Financial [Coupa]</h3>	
							</div>
						</div>
						<a href="/financial" class="btn btn-green"><i class="ti-angle-left"></i> Return to List</a>
						<form method="POST" action="/financial-call" id="request-form" enctype="multipart/form-data">
							{!! csrf_field() !!}
							<div id="main-form">
								<div class="row">
	                                <div class="col-md-3 col-md-offset-3">
	                                    <h4>From(*)</h4>
	                                    <input class="form-control date-format" name="date_ini" id="" type="text" placeholder="" value="{{$date_ini}}" data-validation="required">
	                                </div>
	                                <div class="col-md-3">
	                                    <h4>To(*)</h4>
	                                    <input class="form-control date-format" name="date_end" id="" type="text" placeholder="" value="{{$date_end}}" data-validation="required">
	                                </div>
	                            </div> 

	                            <br/>
	                            <div class="row">
		                            <div class="col-md-6 col-md-offset-3 col-sm-12">
			                            <div class="alert bg-danger text-white">
			                            	<ul class="list-inline checkboxes-radio">
			                            		<li>
			                            			<input type="checkbox" name="mark_exported" id="mark_exported" value="1" checked="checked"> 
			                            			<label for="mark_exported" style="color:#fff; font-size: 1em;"><span></span>Mark invoices as exported on Coupa, if not you must mark them manually on coupa.</label>
			                            		</li>
			                            	</ul>
			                            	
			                            </div>
			                        </div>
			                    </div>
	                            <br/>
	                            <div class="row">
		                            <div class="col-md-6 col-md-offset-3 col-sm-12">
		                                <a href="javascript:void(0);" class="btn btn-success btn-block" id="btn-new-request">Send Request</a>    
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
    $(document).ready(function(){
    	$('#btn-new-request').click(function() {
    		$('.piluku-preloader').removeClass('hidden');
    		$('#request-form').submit();
    	});	
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


  <script type="text/javascript" src="{{ asset('assets/js/form-validation/jquery.form-validator.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/form-validation.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.maskedinput.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/app.form.js') }}"></script>
  
@endsection
