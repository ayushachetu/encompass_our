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
	<div class="wrapper">
	@include('includes.sidebar')
	<div class="content" id="content">
		<div class="overlay"></div>			
		@include('includes.topbar')

		<!-- Page Header -->
		<div class="page_header">
			<div class="pull-left">
				<i class="icon ti-comment-alt page_header_icon"></i>
				<span class="main-text">Export Quotes</span>
				<p class="text"><a href="/quotes">< Back to quote dashboard</a></p>
			</div>
		</div>
		<!-- /pageheader -->  
		<div class="main-content">
			<div class="row">
				<div class="col-md-12">
					<!-- panel -->
					<div class="panel panel-piluku panel-users">
						@if (Session::has('status'))
	                    <div class="alert bg-success text-white" role="alert">
	                    	<strong>{{ Session::get('status') }}</strong>
	                    </div>
	                    @endif
						<div class="panel-body">
							<form class="form" method="post" id="form-wrapper">
								{!! csrf_field() !!}
								<div class="table-responsive">
									<table class="table table-hover table-bordered">
										<thead>
											<tr>
												<th class="">Number</th>
												<th class="">Account Number</th>
												<th class="">Subject</th>
												<th class="">Total</th>
												<th class="">Date Approved</th>
												<th class="text-right td-small"><ul class="list-inline checkboxes-radio">	
														<li class="ms-hover">
															<input type="checkbox" class="" name="export_all" id="cb-all" value="1">
															<label for="cb-all"><span></span></label>
														</li>                                                                               
													</ul>
												</th>
											</tr>
										</thead>
										<tbody>
											@forelse ($list as $item)
												<tr class="table-row" id="tr-{{ $item->id }}">
													<td>QT-{{ $item->job_number }}-{{ $item->correlative }}</td>
													<td>{{ $item->job_number }}</td>
													<td>{{ $item->subject }}</td>
													<td class="text-right">${{ number_format($item->total,2) }}</td>
													<td>
														{{ date( 'm/d/Y', strtotime( $item->action_at) ) }}	
													</td>
													<td class="text-right td-small">
														<ul class="list-inline checkboxes-radio">
															
															<li class="ms-hover">
																<input type="checkbox" class="items-list" name="export_list[]" id="cb-{{ $item->id }}" value="{{ $item->id }}">
																<label for="cb-{{ $item->id }}"><span></span></label>
															</li>                                                                               
														</ul>
														
													</td>
												</tr>
											@empty
											    <tr><td colspan="6" class="text-center">No records found.</td></tr>
											@endforelse
										</tbody>
									</table>
								</div>
								<div class="col-md-12">
									<div class="text-right">
										<a href="/quotes">Cancel</a>
										<button type="button" class="btn btn-primary btn-lg" id="btn-export">Export</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<!-- /panel -->
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					{!! $list->render() !!}
				</div>
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
    $(document).ready(function(){
    	$('#btn-export').click(function() {
    		$('.piluku-preloader').removeClass('hidden');

    		$('#form-wrapper').submit();
    		setTimeout(function(){ 
    			var values = $('input:checkbox:checked.items-list').map(function () {
				  return this.value;
				}).get(); 

    			$.map( values, function( val, i ) {
				  $('#tr-'+val).remove();
				});
				$('.piluku-preloader').addClass('hidden');		
    		 }, 1500);

    		
    	});	
    	$('#cb-all').click(function() {
    		if($('#cb-all:checked').val()==1){
    			$('.items-list').prop( "checked", true );
    		}else{
    			$('.items-list').prop( "checked", false );
    		}
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
  <script type="text/javascript" src="{{ asset('assets/js/jquery.countTo.js') }}"></script>

@endsection
