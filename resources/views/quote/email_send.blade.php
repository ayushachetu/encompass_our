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
		<!-- Page Header -->
		<div class="page_header">
			<div class="pull-left">
				<a href='/quotes'><i class="icon ti-comment-alt page_header_icon"></i></a>
				<span class="main-text">Quote :: QT-{{$quote->job_number}}-{{$quote->correlative}}</span>
				<p class="text"><a href="/quotes">< Back to List</a></p>
			</div>
		</div>
		<div class="main-content">
			<!-- Second Row -->
		<div class="row grid">
			<!-- /col-md-9 -->
			<div class="col-md-12">
				<!-- panel -->
				<div class="panel panel-piluku">
					<div class="panel-body">
						{!! csrf_field() !!}
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
		                            <div class="col-md-12 col-sm-12">
		                            	<h4>Subject</h4>
			                            <input type="text" name="subject" id="subject" class="form-control" data-validation="required" value="{{  $quote->subject}}">
			                        </div>
			                    </div>
			                    <br/>
			                    <div class="row">
			                    	<div class="col-md-6">
			                    		<div class="form-group">
											<label class="control-label">Client Name:</label>
											<input type="text" name="client_name" class="form-control"  value="{{ $quote->client_name }}" data-validation="required">
										</div>
			                    	</div>
			                    	<div class="col-md-6">
			                    		<!--Input Form-->
										<div class="form-group">
											<label class="control-label">Client Email:</label>
											<input type="text" name="client_email" class="form-control"  value="{{ $quote->client_email }}" >
										</div>
										<!--Input Form-->
			                    	</div>
			                    </div>
			                    <div class="row">
			                    	<div class="col-md-12">
			                    		<div class="form-group">
											<label class="control-label">Send a copy to:</label>
											<input type="text" name="copy_email" class="form-control"  value="" >
											<blockquote><small>Seperate emails by comma</small></blockquote>
										</div>
			                    	</div>
			                    </div>
			                    <div class="row">
			                    	<div class="col-md-12 text-right">
			                    		<a class="btn btn-red btn-lg" target="_blank" href="/quote/pdf/{{$quote->id}}"><i class="ion-ios-cloud-download-outline"></i> <span>ATTACHMENT PDF</span></a>
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
		                            <div class="col-md-3 text-right">
		                            	
		                            </div>
		                            <div class="col-md-6 col-sm-12 text-center">
		                                <button type="button" class="btn btn-success btn-block" id="btn-new-request">Send Message</button>    
		                                <br/>
		                                <br/>
		                                <a href="/quote/view/{{$quote->id}}">Cancel</a>
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
