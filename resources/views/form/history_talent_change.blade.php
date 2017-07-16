@extends('layouts.default')
@section('styles')
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
						<div class="row">
							<div class="col-md-12">
								<h3><i class="icon  ion-ios-keypad-outline"></i> Talent Change</h3>	
								<a href="/history-talent-download/2" class="btn btn-green pull-right"> Download Terminate</a>
							</div>
						</div>
						<div class="table-responsive">
								<table class="table table-hover table-bordered" id="displayTable">
									<thead>
										<tr>
											<th class="">Name</th>
											<th class="">Email</th>
											<th class="">Site Name</th>
											<th class="">Site Account Number</th>
											<th class="">Type</th>
											<th class="">Employee Number</th>
											<th class="">Employee Name</th>
											<th class="">Created at</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										@foreach ($list as $item)
											<?php $data_array=unserialize($item->value); ?>
											<tr class="table-row">
												<td>{{ $item->name }}</td>
												<td>{{ $item->email}}</td>
												<td>{{ $item->site_name}}</td>
												<td>{{ $item->site_account_number}}</td>
												<td>{{ $type_list[$item->type]}}</td>
												<td>{{ (isset($data_array['employee_number'])?$data_array['employee_number']:'N/A')}}</td>
												<td>{{ (isset($data_array['employee_name'])?$data_array['employee_name']:'N/A')}}</td>
												<td>{{ date( 'm/d/Y', strtotime( $item->created_at) )  }}</td>
												<td><a class="btn btn-warning btn-info" data="{{ $item->id }}"><span class="fa fa-info-circle "></span></a></td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<div class="row">
							<div class="col-md-12">
								{!! $list->render() !!}
							</div>
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
	<!-- Modal Large -->
<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="ti-close"></span></button>
				<h4 class="modal-title" id="myModalLabel1">Item Details</h4>
			</div>
			<div class="modal-body">
				
			</div>
			
		</div>
	</div>
</div>
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
  <script type="text/javascript" src="{{ asset('assets/js/bic_calendar.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/widgets.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>


  <script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
  <script type="text/javascript">
  	$(document).ready(function(){
  		$( ".btn-info" ).click(function() {
		    var data_id=$(this).attr('data');
		    $('#itemModal').modal('show');
		    $('#itemModal .modal-body').html('<div class="text-center">Loading...</div>');


		    $.ajax({
		        url:  '/history-talent-change/get-item/'+data_id,
		        type: "get",
		        success: function(dataResponse){
		       	  $('#itemModal .modal-body').html(dataResponse.html);	 	
		        },
		        error: function(data){
		          
		        },
		      });
		    
		    
		  });
  	});	
  </script>
  
@endsection
