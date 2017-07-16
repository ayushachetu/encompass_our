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
				<span class="main-text">Budget</span>
				<p class="text"><a href="/budget">< Back to budget dashboard</a></p>
			</div>
			<div class="right pull-right">
				<a class="btn btn-red btn-lg" id="download-link"  href="javascript:void(0)"><i class="ion-ios-cloud-download-outline"></i> <span>Download</span></a>
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
						<h2>Report: {{$ini_range}}/01/{{date('Y')}} - {{$end_range}}/{{date("t", strtotime(date('Y').'-'.$end_range.'-'.date('d')))}}/{{date('Y')}}</h2>
						<div class="filter-default">
							{!! csrf_field() !!}
							<div class="row">
								<div class="col-md-4">
									<label>Industry:</label>
									<select class="form-control" name="industry_select" id="industry-select" data-validation="required">
                                        <option value="0">All</option>
                                        <option value="1">Healthcare</option>
                                        <option value="3">Education</option>
                                        <option value="4">Commercial</option>
                                        <option value="5">Hospitality</option>
                                        <option value="6">Government</option>
                                        <option value="7">Public Venue</option>
                                        <option value="8">Retail</option>
                                        <option value="9">Industrial</option>
                                        <option value="10">Event</option>
                                    </select>
								</div>
							</div>
						</div>
						<div class="table-responsive" id="container-table">
								<table class="table table-bordered" id="displayTable">
									<thead>
										<tr>
											<th class="">Job Number</th>
											<th class="">Job Name</th>
											<th class="">Actual</th>
											<th class="">Budget</th>
											<th class="">Variance</th>
											<th class="">Over/Under</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$total=0;
											$total_budget=0;
											$actual_total=0;
											$actual_total_budget=0;
										?>
										@foreach ($list as $item)
											<?php 
												if(is_numeric($item->bg_total))
													$variance=number_format((($item->at_total-$item->bg_total)*100)/$item->bg_total,2);
												else
													$variance="";
											?>
											<tr class="table-row">
												<td>{{ $item->job_number }}</td>
												<td>{{ $item->job_description}}</td>
												<td class="text-right">{{($item->at_total<0)?'(':''}}${{ number_format(abs($item->at_total),2)}}{{($item->at_total<0)?')':''}}</td>
												<td class="text-right">{{($item->bg_total<0)?'(':''}}${{ number_format(abs($item->bg_total),2)}}{{($item->bg_total<0)?')':''}}</td>
												<td class="text-right">{{($item->at_total-$item->bg_total<0)?'(':''}}${{ number_format(abs($item->at_total-$item->bg_total),2)}}{{($item->at_total-$item->bg_total<0)?')':''}}</td>
												<td class="text-right">{{$variance}}%</td>
											</tr>
											<?php 
												$total+=$item->bg_total;
												$actual_total+=$item->at_total;
												//$total_budget+=$item->budget_hours;
												//$actual_total_budget+=$item->hours;
											?>
										@endforeach
										<!--<tr class="bg-danger">
												<td colspan="2" class="text-right"><span style="color:#fff;">TOTAL:</span></td>
												<td class="text-right"><span style="color: #fff;">${{ number_format($actual_total,2)}}</span></td>
												<td class="text-right"><span style="color: #fff;">${{ number_format($total,2)}}</span></td>
												<td></td>
												<td></td>
										</tr>-->
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
  	$( "#industry-select" ).change(function() {
	  $('#container-table').html('<div class="text-center" style="padding: 80px;"><div class="fa fa-spinner fa-spin fa-5x fa-fw"></div></div>');
	  var data = {
        'industry'      :  $( "#industry-select" ).val(),
        '_token'        :  $('input[name="_token"]').val()
      };
	    $.ajax({
	      url: '/budget/load_list',
	      type: "post",

	      data: data,
	      success: function(data){
	        $('#container-table').html(data.html);
	      },
	      error: function(data){
	      	
	      },
	    });
	});
  	$( "#download-link" ).click(function() {
	  window.location.href = "/budget/report-export/"+$('#industry-select').val();
	});


	$(document).ready(function(){
	    $('#displayTable').DataTable({
		    	paging: false,
		    	searching: false,
			});
	});


  </script>
@endsection
