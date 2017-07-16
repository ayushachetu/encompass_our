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
				<span class="main-text">{{$name}} | Contract & Strategic Sales</span>
				<p class="text"><a href="/reports/4">< Back to report dashboard</a></p>
			</div>
			<div class="pull-right report-select">
                <label>Type:</label>
                <select class="form-control" id="report-type" name="report_type">
                    <option value="1" {{(($type==1)?"selected='selected'":"")}}>Sales Rep</option>
                    <option value="3" {{(($type==3)?"selected='selected'":"")}}>Pipeline</option>
                    <option value="2" {{(($type==2)?"selected='selected'":"")}}>Industry</option>
                </select>
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
						<?php 
                            $col_green="#0CC935";
                            $col_yellow="#FCE327";
                            $col_red="#EC2323";
                        ?>  
                        <div class="row">
							<div class="col-md-12">
								<div id="container-graph"></div>
							</div>
						</div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped table-report" id="displayTable">
                                    <thead>
                                        <tr>
                                            <th class="">{{$name}}</th>
                                            <th class="">Total</th>
                                            <th class="">Forecast</th>
                                            <th class="">Won</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $total=0;
                                        $total_forecast=0;
                                        $total_won=0;
                                    ?>
                                    @foreach ($list as $key => $item)
                                    <tr>
                                        <td>{{$item}}</td>
                                        <td class="text-right">${{number_format( $data[$key] ,2)}}</td>
                                        <td class="text-right">${{number_format( $data_forecast[$key] ,2)}}</td>
                                        <td class="text-right">${{number_format( $data_won[$key],2)}}</td>
                                    </tr>
                                    <?php 
                                        $total+=$data[$key];
                                        $total_forecast+=$data_forecast[$key];
                                        $total_won+=$data_won[$key];
                                    ?>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="">
                                            <td></td>
                                            <td class="text-right"><strong>{{number_format($total,2)}}</strong></td>
                                            <td class="text-right"><strong>{{number_format($total_forecast,2)}}</strong></td>
                                            <td class="text-right"><strong>{{number_format($total_won,2)}}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
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
  <script type="text/javascript" src="{{ asset('assets/code/modules/exporting.js') }}"></script>
  <script type="text/javascript">
  	$(function () {
     $('#container-graph').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: [
                    @foreach ($list as $key => $item)
                    '{{$item}}',
                    @endforeach
                ],
                labels: {
                    formatter: function () {
                        return this.value; // clean, unformatted number for year
                    }
                },
                crosshair: true
            },
            yAxis: {
                title: {
                    text: ''
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ' $'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            credits: {
                  enabled: false
              },
            colors: ['#00BFFF', '#7a7a7a', '#0CC935'],
            series: [
                {
                    name: 'Total Amount',
                    data: [
                    @foreach ($list as $key => $item)
                    { y: {{number_format($data[$key],2,'.', '')}}}, 
                    @endforeach
                    ]   
                },
                {
                    name: 'Total Forecast',
                    data: [
                    @foreach ($list as $key => $item)
                    { y: {{number_format($data_forecast[$key],2,'.', '')}}}, 
                    @endforeach
                    ]   
                },
                {
                    name: 'Total Closed',
                    data: [
                    @foreach ($list as $key => $item)
                    { y: {{number_format($data_won[$key],2,'.', '')}}}, 
                    @endforeach
                    ]   
                }
                
            ]
        });
           
     $('#report-type').on('change', function (e) { 
            window.location.replace("/report/add-on-sales/"+$(this).val());
     });

     $('#displayTable').DataTable({
        paging: false,
        searching: false,
      });
	});
  </script>
  
@endsection
