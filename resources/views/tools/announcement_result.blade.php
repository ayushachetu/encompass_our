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
								<h3><i class="icon ti-announcement"></i> Announcement Result</h3>	
							</div>
						</div>
						<hr/>	
							<div id="main-form">
								<div class="row">
	                                <div class="col-md-6 col-md-offset-3 col-sm-12">
	                                    <div>
	                                    	<h2 class="text-center">Message sent!</h2>
	                                    	<h3 class="text-center">Subject:{{$subject}}</h3>
	                                    	<h3 class="text-center">Emails Sent: {{count($list_email)}}</h3>
	                                    </div>
			                            <div>
			                                <a href="/announcement" type="submit" class="btn btn-success btn-block" id="btn-new-request">New Message</a>    
			                            </div>
	                                    <hr/>
	                                    <div>
	                                    	<table class="table table-bordered">
	                                    	@foreach ($list_email as $e_item) 
	                                    		<tr>
	                                    			<td>{{$e_item}}</td>
	                                    		</tr>
	                                    	@endforeach
	                                    	</table>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="row">
		                            <div class="col-md-6 col-md-offset-3 col-sm-12">
		                                <a href="/announcement" type="submit" class="btn btn-success btn-block" id="btn-new-request">New Message</a>    
		                            </div>
		                                                   
		                        </div> 
		                        <br/><br/><br/>
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
  <script type="text/javascript" src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/form-validation/jquery.form-validator.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/form-validation.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function(){
        window.applyValidation(true, '#request-form', 'top');
    });
    
</script>
  
@endsection
