@extends('layouts.default')
@section('styles')
<link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" >
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
				<i class="icon ti-briefcase page_header_icon"></i>
				<span class="main-text">Contract & Strategic Sales Detail</span>
				<p class="text"><a href="/reports/4">< Back to report dashboard</a></p>
			</div>
			<div class="right pull-right">
				
			</div>

		</div>
		<!-- /pageheader -->  

		<div class="main-content">
		<!-- Second Row -->
		<div class="row grid">
			<!-- /col-md-9 -->
			<div class="col-md-12">
				<!-- panel -->
				<div class="panel panel-piluku">
					<div class="panel-body">
						<h2>Report: {{date( 'm/d/Y', strtotime( $ini_range) )}} - {{date( 'm/d/Y', strtotime( $end_range) )}}</h2>
						<div class="table-responsive" id="container-table">
								<table class="table table-bordered" id="displayTable">
									<thead>
										<tr>
											<th class="">Name</th>
											<th class="">Amount($)</th>
											<th class="">Stage</th>
											<th class="">Pipeline</th>
											<th class="">Sales Rep</th>
											<th class="">Industry</th>
											<th class="">Created</th>
											<th class="">Close</th>
										</tr>
									</thead>
									<tbody>
										<?php $total=0; ?>
										@foreach ($list as $item)
											
											<tr class="table-row">
												<td>{{ $item->name }}</td>
												<td class="text-right">{{ number_format($item->amount,2)}}</td>
												<td>{{ $item->stage_name }}</td>
												<td>{{ $item->pipeline_name }}</td>
												<td>{{ $hubspotowner_list[$item->hubspot_owner_id] }}</td>
												<td>{{ $item->deal_vertical }}</td>
												<td>{{date( 'm/d/Y', strtotime( $item->create_date) )}}</td>
												<td>{{date( 'm/d/Y', strtotime( $item->close_date) )}}</td>
											</tr>
											<?php 
												$total+=$item->amount;
											?>
										@endforeach
										<tfoot>
											<td></td>
											<td class="text-right">{{number_format($total, 2)}}</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tfoot>
									</tbody>
								</table>
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
  <script type="text/javascript" src="{{ asset('assets/js/jquery-ui-1.10.3.custom.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
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
	    $('#displayTable').DataTable({
		    	paging: false,
		    	searching: true,
		    	order: [[ 6, "desc" ]]
		});
	});


  </script>
@endsection
