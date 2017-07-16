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
				<i class="icon ti-image page_header_icon"></i>
				<span class="main-text">{{$name}}</span>
				<p class="text"><a href="/reports">< Back to report dashboard</a></p>
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
						<h2>Date: {{$ini_range}}/01/{{date('Y')}} - {{$end_range}}/{{date("t", strtotime(date('Y').'-'.$end_range.'-'.date('d')))}}/{{date('Y')}}</h2>
						<div class="row">
							<div class="col-md-6">
								<table class="table table-bordered table-striped table-report">
									<tr>
										<td>{{$name}}</td>
										<td>Revenue($)</td>
										<td>Gross Margin($)</td>
										<td>GM %</td>
									</tr>
									<?php 
										$total_revenue=0;
										$total_gross=0;

									?>
									@foreach ($list as $key => $item)
									<tr>
										<td>{{$item}}</td>
										<td class="text-right">{{number_format(abs($data[$key]['revenue']),2)}}</td>
										<td class="text-right">{{number_format(abs($data[$key]['revenue'])-$data[$key]['cost'],2)}}</td>
										<td class="text-right">{{number_format((abs($data[$key]['revenue'])-$data[$key]['cost'])/abs((($data[$key]['revenue']!=0)?$data[$key]['revenue']:'1')),2)*100}}%</td>
									</tr>
									<?php 
										$total_revenue+=abs($data[$key]['revenue']);
										$total_gross+=abs($data[$key]['revenue'])-$data[$key]['cost'];
									?>
									@endforeach
									<tr class="">
										<td></td>
										<td class="text-right"><strong>{{number_format($total_revenue,2)}}</strong></td>
										<td class="text-right"><strong>{{number_format($total_gross,2)}}</strong></td>
										<td></td>
									</tr>

								</table>
							</div>
							<div class="col-md-6">
								<div id="container-revenue" style="height: 400px"></div>	
							</div>
							
						</div>
						<br/>
						<div class="row">
							<div class="col-md-6">
								<div id="container-gross-margin" style="height: 400px"></div>	
							</div>
							<div class="col-md-6">
								<div id="container-gross-prc" style="height: 400px"></div>	
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
  <script type="text/javascript" src="{{ asset('assets/code/highcharts.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/code/themes/sand-signika.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/code/modules/exporting.js') }}"></script>
  <script type="text/javascript">
  	$(function () {
    $('#container-revenue').highcharts({
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Revenue'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Revenue',
            data: [
            	@foreach ($list as $key => $item)
            		['{{$item}}', {{number_format(abs($data[$key]['revenue']),2, '.','')}}],
            	@endforeach
            ]
        }]
    });

    $('#container-gross-margin').highcharts({
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Gross Margin'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Gross Margin',
            data: [
            	@foreach ($list as $key => $item)
            		['{{$item}}', {{number_format(abs($data[$key]['revenue'])-$data[$key]['cost'],2, '.','')}}],
            	@endforeach
            ]
        }]
    });
    $('#container-gross-prc').highcharts({
        chart: {
            type: 'pie'
        },
        title: {
            text: 'GM %'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'GM %',
            data: [
            	@foreach ($list as $key => $item)
            		['{{$item}}', {{number_format((abs($data[$key]['revenue'])-$data[$key]['cost'])/abs((($data[$key]['revenue']!=0)?$data[$key]['revenue']:'1')),2,'.','')}}],
            	@endforeach
            ]
        }]
    });
	});
  </script>
  
@endsection
