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
								<h3><i class="icon ti-announcement"></i> Edit Announcement</h3>	
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
	                                <div class="col-md-12">
	                                	<?php 
	                                		$flag_all=false;
	                                		$arr_perm = array(
	                                			0 => 0,
	                                			1 => 0, 
	                                			2 => 0,
	                                			3 => 0,
	                                			4 => 0,
	                                		);
	                                		if($announce->permission=="11111"){
	                                			$flag_all=true;
	                                		}else{
	                                			$arr_perm = str_split($announce->permission);
	                                		}
	                                	?>
	                                    <!-- xradio buttons-->
										<div class="form-group check-radio">
											<label class="control-label">View Announcement:</label>
											<ul class="list-inline checkboxes-radio">
												<li>
													<input type="checkbox" id="receiver0" name="receiver[]" value="0" {{ (($flag_all)?'checked="checked"':'') }}/>
													<label for="receiver0" style="font-size: 1.1em; color: #000;"><span></span>All</label>
												</li>
												<li>
													<input type="checkbox" id="receiver1" name="receiver[]" value="4" {{ (($arr_perm[0])?'checked="checked"':'') }}/>
													<label for="receiver1" style="font-size: 1.1em; color: #000;"><span></span>Director</label>
												</li>
												<li>
													<input type="checkbox" id="receiver2" name="receiver[]" value="6" {{ (($arr_perm[1])?'checked="checked"':'') }}/>
													<label for="receiver2" style="font-size: 1.1em; color: #000;"><span></span>Area Manager</label>
												</li>
												<li>
													<input type="checkbox" id="receiver3" name="receiver[]" value="5" {{ (($arr_perm[2])?'checked="checked"':'') }}/>
													<label for="receiver3" style="font-size: 1.1em; color: #000;"><span></span>Area Supervisors</label>
												</li>
												<li>
													<input type="checkbox" id="receiver4" name="receiver[]" value="8" {{ (($arr_perm[3])?'checked="checked"':'') }}/>
													<label for="receiver4" style="font-size: 1.1em; color: #000;"><span></span>Supervisors</label>
												</li>
												<li>
													<input type="checkbox" id="receiver5" name="receiver[]" value="9" {{ (($arr_perm[4])?'checked="checked"':'') }}/>
													<label for="receiver5" style="font-size: 1.1em; color: #000;"><span></span>Employee</label>
												</li>
											</ul>
										</div>
										<!-- xradio buttons-->
										<hr>
	                                </div>
	                            </div> 
	                            <div class="row">
		                            <div class="col-md-4 col-sm-12">
		                            	<h4>Display announcement until (date):</h4>
			                            <input type="text" name="closing_date" id="closing_date" class="form-control date-format" data-validation="required" value="{{ date( 'm/d/Y', strtotime( $announce->closing_date) )  }}">
			                        </div>
			                    </div>
	                            <div class="row">
		                            <div class="col-md-12 col-sm-12">
		                            	<h4>Title</h4>
			                            <input type="text" name="title" id="title" class="form-control" data-validation="required" value="{{ $announce->title }}">
			                        </div>
			                    </div>
	                            <div class="row">
		                            <div class="col-md-12 col-sm-12">
		                            	<h4>Message</h4>
			                            <textarea name="message" class="form-control" style="height: 200px;line-height:120%;">{{ $announce->message }}</textarea>
			                        </div>
			                    </div>
	                            <br/>
	                            <div class="row">
		                            <div class="col-md-6 col-md-offset-3 col-sm-12">
		                                <button type="button" class="btn btn-success btn-block" id="btn-new-request">Save</button>    
		                            </div>
		                                                   
		                        </div> 
		                        <br/>
		                        <div class="text-center">
		                        	<a href="/announcement-dashboard">Cancel</a>
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
  <script type="text/javascript" src="{{ asset('assets/js/jquery.maskedinput.js') }}"></script>
  
  <script type="text/javascript">
    $(document).ready(function(){
        /*window.applyValidation(true, '#request-form', 'top');*/
    });

    $(document).ready(function(){
    	$('#btn-new-request').click(function() {
    		if($('#title').val()==""){
    			alert('Enter a valid title');
    			$('#title').focus();
    			return false;
    		}
    		$('.piluku-preloader').removeClass('hidden');
    		$('#request-form').submit();
    	});	
    	$(".date-format").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
    });
    
</script>
@endsection
