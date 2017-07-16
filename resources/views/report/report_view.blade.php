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
				<span class="main-text">{{$name}} | Profitability</span>
				<p class="text"><a href="/reports">< Back to report dashboard</a></p>
			</div>
			<div class="pull-right report-select">
                <label>Type:</label>
                <select class="form-control" id="report-type" name="report_type">
                    <option value="ytd-county" {{(($type==1)?"selected='selected'":"")}}>County</option>
                    <option value="ytd-industry" {{(($type==2)?"selected='selected'":"")}}>Industry</option>
                    <option value="ytd-mayor-account" {{(($type==3)?"selected='selected'":"")}}>Major Account</option>
                    <option value="ytd-manager" {{(($type==4)?"selected='selected'":"")}}>Mananger</option>
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
						<h2>Date: {{$ini_range}}/01/{{$year}} - {{$end_range}}/{{date("t", strtotime($year.'-'.$end_range.'-'.date('d')))}}/{{$year}}</h2>
						<div class="row">
							<div class="col-md-6">
								<div id="conteiner-revenue-detail"></div>
							</div>
							<div class="col-md-6">
								<div id="container-revenue" style="height: 400px"></div>	
							</div>
							
						</div>
						<br/>
						<div class="row">
							<div class="col-md-6">
								<div id="container-gross-margin-detail"></div>
							</div>
							<div class="col-md-6">
								<div id="container-gross-margin" style="height: 400px"></div>   
							</div>
							
						</div>
                        <br/>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div id="container-gross-prc-detail" style="height: 400px"></div>  
                            </div>
                            <div class="col-md-6">
                                <div id="container-gross-prc" style="height: 400px"></div>  
                            </div>
                            
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped table-report" id="displayTable">
                                    <thead>
                                    <tr>
                                        <th>{{$name}}</th>
                                        <th>Revenue($)</th>
                                        <th>Gross Margin($)</th>
                                        <th>GM %</th>
                                    </tr>
                                    </thead>
                                    <tbody>
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
                                    </tbody>
                                    <tfoot>
                                        <tr class="">
                                            <td></td>
                                            <td class="text-right"><strong>{{number_format($total_revenue,2)}}</strong></td>
                                            <td class="text-right"><strong>{{number_format($total_gross,2)}}</strong></td>
                                            <td></td>
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
                    enabled: false
                },
                showInLegend: true
            }
        },
        credits: {
                  enabled: false
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
                    enabled: false
                },
                showInLegend: true
            }
        },
        credits: {
                  enabled: false
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
                    enabled: false
                },
                showInLegend: true
            }
        },
        credits: {
                  enabled: false
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
    $('#conteiner-revenue-detail').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Revenue'
        },
        xAxis: {
            categories: [
                'Revenue',
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
        series: [
        @foreach ($list as $key => $item)
            {
                name: '{{$item}}',
                data: [{{number_format(abs($data[$key]['revenue']),2, '.','')}}]

            },
        @endforeach
        ]
    });
    $('#container-gross-margin-detail').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Gross Margin'
        },
        xAxis: {
            categories: [
                'Revenue',
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
        series: [
        @foreach ($list as $key => $item)
            {
                name: '{{$item}}',
                data: [{{number_format(abs($data[$key]['revenue'])-$data[$key]['cost'],2, '.','')}}]

            },
        @endforeach
        ]
    });
     $('#container-gross-prc-detail').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'GM %'
        },
        xAxis: {
            categories: [
                'Revenue',
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
            valueSuffix: ' %'
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
        series: [
        @foreach ($list as $key => $item)
            {
                name: '{{$item}}',
                data: [{{number_format((abs($data[$key]['revenue'])-$data[$key]['cost'])/abs((($data[$key]['revenue']!=0)?$data[$key]['revenue']:'1')),2,'.','')*100}}]

            },
        @endforeach
        ]
      });
     $('#report-type').on('change', function (e) { 
            window.location.replace("/report/"+$(this).val());
        });
	});
    
        $('#displayTable').DataTable({
                paging: false,
                searching: false,
        });
    
  </script>
@endsection
