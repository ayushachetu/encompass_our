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
				<span class="main-text">Actual Expense y Target Expense</span>
				<p class="text"><a href="/dashboard">< Back to  dashboard</a></p>
			</div>
			<div class="right pull-right"></div>
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
						<h2>Report: {{$ini_range}} - {{$end_range}}</h2>
						<div class="table-responsive" id="container-table">
								<table class="table table-bordered" id="displayTable">
									<thead>
										<tr>
											<th class="">Job Number</th>
											<th class="">Job Name</th>
											<th class="">Manager</th>
											<th class="">Actual Expense($)</th>
											<th class="">Target Expense($)</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$total_cost=0;
                                        	$total_budget=0;
										?>
										@foreach ($list as $item)
											<?php ?>
											<tr class="table-row">
												<td>{{ $item->job_number }}</td>
												<td>{{ $item->job_description}}</td>
												<td>{{ $item->manager_name}}</td>
												<td class="text-right">{{number_format($data[$item->job_number]['cost'],2)}}</td>
		                                        <td class="text-right">{{number_format($data[$item->job_number]['budget'],2)}}</td>
											</tr>
											<?php 
												$total_cost+=$data[$item->job_number]['cost'];
                                        		$total_budget+=$data[$item->job_number]['budget'];
											?>
										@endforeach
									</tbody>
									<tfoot>
										<td></td>
										<td></td>
										<td></td>
										<td>{{number_format($total_cost,2)}}</td>
										<td>{{number_format($total_budget,2)}}</td>
									</tfoot>
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
		    	searching: false,
		});
	});

  </script>
@endsection