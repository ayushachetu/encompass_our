<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Report</title>
    <link href="{{ asset('assets/css/style-pdf.css') }}" rel="stylesheet" type="text/css" >
  </head>
  <body>
	<div class="wrapper ">
	<div class="content" id="content">
		
		<div class="main-content">
			
			<div class="row">
				<div class="col-md-6 col-xs-12 col-sm-6 ">
					<div id="graph-county"></div>
				</div>
				<div class="col-md-6 col-xs-12 col-sm-6 ">
					<div id="graph-industry"></div>
				</div>
			</div>
			<br/>
			<div class="row">
				
				<div class="col-md-6 col-xs-12 col-sm-6 ">
					<div id="graph-mayor"></div>
				</div>
				<div class="col-md-6 col-xs-12 col-sm-6 ">
					<div id="graph-manager"></div>
				</div>
			</div>
			<!--row-->
			<div class="row">
				
			</div>
		</div>
	  </div>  
	</div>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.js') }}"></script>	
  <script type="text/javascript" src="{{ asset('assets/code/highcharts.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/code/modules/exporting.js') }}"></script>
  <script type="text/javascript">
  	$(function () {
	    $('#graph-county').highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: 'County'
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
	        @foreach ($county_list as $key => $item)
	        	{
		            name: '{{$item}}',
		            data: [{{number_format(abs($data_county[$key]['revenue']),2, '.','')}}]

		        },
	    	@endforeach
	        ]
	    });
	    $('#graph-industry').highcharts({
	        chart: {
	            type: 'column',
	        },
	        title: {
	            text: 'Industry'
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
	            },

	        },
	        credits: {
			      enabled: false
			  },
	        series: [
	        @foreach ($industry_list as $key => $item)
	        	{
		            name: '{{$item}}',
		            data: [{{number_format(abs($data_industry[$key]['revenue']),2, '.','')}}]

		        },
	    	@endforeach
	        ]
	    });
	    $('#graph-mayor').highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: 'Major Account'
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
	        @foreach ($mayor_list as $key => $item)
	        	{
		            name: '{{$item}}',
		            data: [{{number_format(abs($data_mayor[$key]['revenue']),2, '.','')}}]

		        },
	    	@endforeach
	        ]
	    });
	    $('#graph-manager').highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: 'Manager'
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
	        @foreach ($manager_list as $key => $item)
	        	{
		            name: '{{$item}}',
		            data: [{{number_format(abs($data_manager[$key]['revenue']),2, '.','')}}]

		        },
	    	@endforeach
	        ]
	    });
	});
  </script>

  </body>
</html>
