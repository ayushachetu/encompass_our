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
				<span class="main-text">{{$name}} | Labor Efficiency Ratio</span>
				<p class="text"><a href="/reports/5">< Back to report dashboard</a></p>
			</div>
			<div class="pull-right report-select">
                <label>Type:</label>
                <select class="form-control" id="report-type" name="report_type">
                    <option value="1" {{(($type==1)?"selected='selected'":"")}}>County</option>
                    <option value="2" {{(($type==2)?"selected='selected'":"")}}>Industry</option>
                    <option value="3" {{(($type==3)?"selected='selected'":"")}}>Major Account</option>
                    <option value="4" {{(($type==4)?"selected='selected'":"")}}>Mananger</option>
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
								<div id="conteiner-ler-detail"></div>
							</div>
							<div class="col-md-6">
								<div id="container-ler" style="height: 400px"></div>	
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
                                <div id="container-direct-labor-detail" style="height: 400px"></div>  
                            </div>
                            <div class="col-md-6">
                                <div id="container-direct-labor" style="height: 400px"></div>  
                            </div>
                            
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped table-report" id="displayTable">
                                    <thead>
                                    <tr>
                                        <th>{{$name}}</th>
                                        <th class="">Revenue($)</th>
                                        <th class="">COGS</th>
                                        <th class="">Gross Margin</th>
                                        <th class="">Direct Labor</th>
                                        <th class="">Labor Efficiency Labor</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $total_revenue=0;
                                        $total_cogs=0;
                                        $total_gross=0;
                                        $total_labor=0;

                                    ?>
                                    @foreach ($list as $key => $item)
                                    <?php 
                                        $gross_profit=abs($data[$key]['revenue'])-$data[$key]['cost'];
                                        $hours=(($data_hours[$key]!=0)?$data_hours[$key]:1);
                                        $LER=$gross_profit/$hours;
                                    ?>
                                    <tr>
                                        <td>{{$item}}</td>
                                        <td class="text-right">{{number_format(abs($data[$key]['revenue']),2)}}</td>
                                        <td class="text-right">{{number_format(abs($data[$key]['cost']),2)}}</td>
                                        <td class="text-right">{{number_format($gross_profit,2)}}</td>
                                        <td class="text-right">{{number_format($data_hours[$key],2)}}</td>
                                        <td class="text-right">{{number_format($LER,2)}}</td>
                                    </tr>
                                    <?php 
                                        $total_revenue+=abs($data[$key]['revenue']);
                                        $total_cogs+=abs($data[$key]['cost']);
                                        $total_gross+=$gross_profit;
                                        $total_labor+=$data_hours[$key];
                                    ?>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="">
                                            <td></td>
                                            <td class="text-right"><strong>{{number_format($total_revenue,2)}}</strong></td>
                                            <td class="text-right"><strong>{{number_format($total_cogs,2)}}</strong></td>
                                            <td class="text-right"><strong>{{number_format($total_gross,2)}}</strong></td>
                                            <td class="text-right"><strong>{{number_format($total_labor,2)}}</strong></td>
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
    $('#container-ler').highcharts({
        chart: {
            type: 'pie'
        },
        title: {
            text: 'LER'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f} %</b>'
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
            name: 'LER',
            data: [
            	@foreach ($list as $key => $item)
            		['{{$item}}', {{number_format(abs($data_ler[$key]),2, '.','')}}],
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
    $('#container-direct-labor').highcharts({
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Direct Labor'
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
            name: 'Direct Labor',
            data: [
            	@foreach ($list as $key => $item)
            		['{{$item}}', {{number_format($data_hours[$key],2,'.','')}}],
            	@endforeach
            ]
        }]
    });
    $('#conteiner-ler-detail').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'LER'
        },
        xAxis: {
            categories: [
                'LER',
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
            valueSuffix: ' LER'
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
                data: [{{number_format(abs($data_ler[$key]),2, '.','')}}]

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
     $('#container-direct-labor-detail').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Direct Labor'
        },
        xAxis: {
            categories: [
                'Direct Labor',
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
            valueSuffix: ' Hours'
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
                data: [{{number_format($data_hours[$key],2,'.','')}}]

            },
        @endforeach
        ]
      });
     $('#report-type').on('change', function (e) { 
            window.location.replace("/report/ler-view/"+$(this).val());
        });
	});
    
        $('#displayTable').DataTable({
                paging: false,
                searching: false,
        });
    
  </script>
@endsection
