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
								<h3><i class="icon ti-announcement"></i> Announcement to emails</h3>	
							</div>
						</div>
						<hr/>
						<form method="POST" action="" id="request-form" enctype="multipart/form-data">
							{!! csrf_field() !!}
							<div class="sign-alert">
	                            @if (count($errors) > 0)
	                                <div class="alert alert-danger">
	                                    <ul>
	                                        @foreach ($errors->all() as $error)
	                                            <li>{{ $error }}</li>
	                                        @endforeach
	                                    </ul>
	                                </div>
	                            @endif
	                        </div>
							<div id="main-form">
								<div class="row">
	                                <div class="col-md-8">
	                                    <!-- xradio buttons-->
										<div class="form-group check-radio">
											<label class="control-label">Type of email to send:</label>
											<ul class="list-inline checkboxes-radio">
												<li>
													<input type="radio" name="email_to" id="personal_email" value="2" checked/>
													<label for="personal_email" style="font-size: 1.1em; color: #000;"><span></span>Personal Email (WINTEAM Email)</label>
												</li>
												<li>
													<input type="radio" name="email_to" id="eoc_email" value="1" />
													<label for="eoc_email" style="font-size: 1.1em; color: #000;"><span></span>Encompass Email (@encompassonsite.com)</label>
												</li>
											</ul>
											<small>(Normal employee might not have an @encompassonsite.com email)</small>
										</div>
										<!-- xradio buttons-->
										<hr>
	                                </div>
	                            </div>
	                            <br/>
	                            <div class="row">
	                                <div class="col-md-8">
	                                    <!-- xradio buttons-->
										<div class="form-group check-radio">
											<label class="control-label">Group of receivers:</label>
											<ul class="list-inline checkboxes-radio">
												<li>
													<input type="checkbox" id="receiver1" name="receiver[]" value="6"/>
													<label for="receiver1" style="font-size: 1.1em; color: #000;"><span></span>Area Manager</label>
												</li>
												<li>
													<input type="checkbox" id="receiver2" name="receiver[]" value="8"/>
													<label for="receiver2" style="font-size: 1.1em; color: #000;"><span></span>Area Supervisors & Supervisors</label>
												</li>
												<li>
													<input type="checkbox" id="receiver3" name="receiver[]" value="9" />
													<label for="receiver3" style="font-size: 1.1em; color: #000;"><span></span>Employee</label>
												</li>
											</ul>
										</div>
										<!-- xradio buttons-->
										<hr>
	                                </div>
	                            </div> 
	                            <!--<div class="row">
		                            <div class="col-md-12 col-sm-12">
		                            	<h4>To(TESTING PURPOSE)</h4>
			                            <input type="text" name="to" id="to" class="form-control" value="{{ old('to') }}">
			                        </div>
			                    </div>-->
	                            <div class="row">
		                            <div class="col-md-12 col-sm-12">
		                            	<h4>Subject</h4>
			                            <input type="text" name="subject" id="subject" class="form-control" data-validation="required" value="{{ old('subject') }}">
			                        </div>
			                    </div>
	                            <div class="row">
		                            <div class="col-md-12 col-sm-12">
		                            	<h4>Message</h4>
			                            <textarea id="elm1" name="message" class="form-control" style="height: 200px;line-height:120%;">{{ old('message') }}</textarea>
			                        </div>
			                    </div>
	                            <br/>
	                            <div class="row">
		                            <div class="col-md-6 col-md-offset-3 col-sm-12">
		                                <button type="button" class="btn btn-success btn-block" id="btn-new-request">Send Message</button>    
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
  <script type="text/javascript" src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.countTo.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
  
  <script type="text/javascript">
    $(document).ready(function(){
        /*window.applyValidation(true, '#request-form', 'top');*/
    });

    $(document).ready(function(){
    	$('#btn-new-request').click(function() {
    		if($('#subject').val()==""){
    			alert('Enter a valid subject');
    			$('#subject').focus();
    			return false;
    		}

    		


    		$('.piluku-preloader').removeClass('hidden');
    		$('#request-form').submit();
    	});	
    });
    
</script>
@endsection
