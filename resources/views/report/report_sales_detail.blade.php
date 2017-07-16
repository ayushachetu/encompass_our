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
				<span class="main-text">Profitability Detail</span>
				<p class="text"><a href="/reports/1">< Back to report dashboard</a></p>
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
						<div class="row">
							<div class="col-md-8">
								<h3>Report: {{$ini_range}}/01/{{$year}} - {{$end_range}}/{{date("t", strtotime($year.'-'.$end_range.'-'.date('d')))}}/{{$year}}</h3>
							</div>
							<div class="col-md-4 text-right">
								<label>Target Margin</label>
								<input type="text" class="text-right" name="target_margin" id="target_margin" value="{{$margin}}">
								<label>%</label>
							</div>
						</div>
						
						<div class="table-responsive" id="container-table">
								<table class="table table-bordered" id="displayTable">
									<thead>
										<tr>
											<th class="">Job Number</th>
											<th class="">Job Name</th>
											<th class="">Revenue($)</th>
											<th class="">Gross Margin</th>
											<th class="">GM %</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$total_revenue=0;
                                        	$total_gross=0;
										?>
										@foreach ($list as $item)
											<?php 
												$magin_prc=number_format((abs($data[$item->job_number]['revenue'])-$data[$item->job_number]['cost'])/abs((($data[$item->job_number]['revenue']!=0)?$data[$item->job_number]['revenue']:'1')),2)*100;
											?>
											<tr class="table-row tr-bg-success" data-margin="{{$magin_prc}}">
												<td>{{ $item->job_number }}</td>
												<td>{{ $item->job_description}}</td>
												<td class="text-right">{{number_format(abs($data[$item->job_number]['revenue']),2)}}</td>
		                                        <td class="text-right">{{number_format(abs($data[$item->job_number]['revenue'])-$data[$item->job_number]['cost'],2)}}</td>
		                                        <td class="text-right">{{$magin_prc}}%</td>
											</tr>
											<?php 
												$total_revenue+=abs($data[$item->job_number]['revenue']);
                                        		$total_gross+=abs($data[$item->job_number]['revenue'])-$data[$item->job_number]['cost'];
											?>
										@endforeach
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
		});

		$("#target_margin").change(function(){
			var target_margin=parseFloat($( this ).val());
			var range_ini=parseFloat(target_margin*0.97);
			var range_end=parseFloat(target_margin*1.03);

		    $( ".table-row" ).each(function( index ) {
			  var data=parseFloat($( this ).attr('data-margin'));	
			  $( this ).removeClass('tr-bg-success');
			  $( this ).removeClass('tr-bg-danger');
			  $( this ).removeClass('tr-bg-warning');
			  if(range_ini<=data && range_end>=data){
			  	$( this ).addClass('tr-bg-warning');
			  }else if(data<target_margin){
			  	$( this ).addClass('tr-bg-danger');
			  }else if(data>target_margin){
			  	$( this ).addClass('tr-bg-success');
			  }

			});
		});

		var target_margin=parseFloat($( '#target_margin' ).val());
		    $( ".table-row" ).each(function( index ) {

			  var data=parseFloat($( this ).attr('data-margin'));	
			  var range_ini=parseFloat(target_margin*0.97);
			  var range_end=parseFloat(target_margin*1.03);

			  $( this ).removeClass('tr-bg-success');
			  $( this ).removeClass('tr-bg-danger');
			  $( this ).removeClass('tr-bg-warning');
			  if(range_ini<=data && range_end>=data){
			  	$( this ).addClass('tr-bg-warning');
			  }else if(data<target_margin){
			  	$( this ).addClass('tr-bg-danger');
			  }else if(data>target_margin){
			  	$( this ).addClass('tr-bg-success');
			  }
			  
			});
	});


  </script>
@endsection
