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

		<!-- Page Header -->
		<div class="page_header">
			<div class="pull-left">
				<i class="icon ti-image page_header_icon"></i>
				<span class="main-text">Reports Dashboard</span>
			</div>
			<div class="pull-right report-select">
				<label>Report Dashboard</label>
				<select class="form-control" id="report-type" name="report_type">
					<option value="1" {{(($type==1)?"selected='selected'":"")}}>Profitability</option>
					<option value="5" {{(($type==5)?"selected='selected'":"")}}>Labor Efficiency Ratio</option>
					<option value="2" {{(($type==2)?"selected='selected'":"")}}>Actual vrs Target</option>
					<option value="3" {{(($type==3)?"selected='selected'":"")}}>Time Management</option>
					<option value="4" {{(($type==4)?"selected='selected'":"")}}>Contract & Strategic Sales</option>
				</select>
			</div>
			<div class="clearfix"></div>
			<div class="pull-right">
				<a href="/report/timekeeping-detail" class="btn btn-success btn-lg">Details <span class="icon  ti-angle-double-right "></span></a>
			</div>
			
		</div>
		<!-- /pageheader -->  
		<div class="main-content">
			<div class="row">
				<div class="col-md-12">
					<div class="filter-box-top">
						<div class="row">
							<div class="col-md-2">
								<span class="label-range">Set Date Range:</span>	
							</div>
							<div class="col-md-1">
								<div class="text-center label-range"><strong>From</strong></div>	
							</div>
							<div class="col-md-7">
								<div class="input-group input-daterange">
									<input type="text" name="ini_date" id="ini_date" class="form-control filter-field" value="{{ date( 'm/d/Y', strtotime( $date_ini) )}}">
									<span class="input-group-addon bg">TO</span>
									<input type="text" name="end_date" id="end_date" class="form-control filter-field" value="{{ date( 'm/d/Y', strtotime( $date_end) )}}">
								</div>
							</div>
							<div class="col-md-1">
								<a id="filter-btn" ref="javascript:void(0)" class="btn btn-primary btn-block btn-lg"><span class="fa fa-filter"></span></a>
							</div>
							
						</div>
						
					</div>
					
				</div>
			</div>
			<?php 
				$col_green="#0CC935";
				$col_yellow="#FCE327";
				$col_red="#EC2323";

			?>	
			<!--row-->
			<!--row-->
			<div class="row">
				<div class="col-md-6 col-xs-12 col-sm-6 ">
					<div id="graph-county"></div>
					<div>
						<a class="btn btn-block btn-info" href="{{url('/')}}/report/timekeeping/1" >View Details</a>
					</div>
				</div>
				<div class="col-md-6 col-xs-12 col-sm-6 ">
					<div id="graph-industry"></div>
					<div>
						<a class="btn btn-block btn-info" href="{{url('/')}}/report/timekeeping/2" >View Details</a>
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-6 col-xs-12 col-sm-6">
					<div id="graph-mayor"></div>
					<div>
						<a class="btn btn-block btn-info" href="{{url('/')}}/report/timekeeping/3" >View Details</a>
					</div>
				</div>
				<div class="col-md-6 col-xs-12 col-sm-6">
					<div id="graph-manager"></div>
					<div>
						<a class="btn btn-block btn-info" href="{{url('/')}}/report/timekeeping/4" >View Details</a>
					</div>
				</div>
			</div>
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
  <script type="text/javascript" src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/wow.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.loadmask.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.accordion.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/materialize.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/build/d3.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/nvd3/nv.d3.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/sparkline.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/bic_calendar.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/widgets.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/bootstrap-datepicker.js') }}"></script>	

  <script type="text/javascript">
  	$('#filter-btn').click(function() {
  		  var ini_date=$('#ini_date').val();
  		  var end_date=$('#end_date').val();
  		  
	      $('#wrapper-dates').hide();
	      $('#wrapper-dates-loader').show();
	      $.ajax({
	        url:  '/report/filter-date-values/'+ini_date.replace(/\//g, "-")+'/'+end_date.replace(/\//g, "-"),
	        type: "get",
	        success: function(dataResponse){
	          $('#wrapper-dates').show();
	          $('#wrapper-dates-loader').hide();
	          location.reload();
	        },
	        error: function(data){
	          
	        },
	      });
  	});	

  	$('.filter-field').on('change', function (e) {    
  		$('.filter-box-top').addClass('filter-box-active');	    
  	});

  	$('#report-type').on('change', function (e) { 
  		window.location.replace("/reports/"+$(this).val());
  	});	
  </script>
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
	            	@foreach ($county_list as $key => $item)
	            	'{{$item}}',
	            	@endforeach
	            ],
	            labels: {
	                formatter: function () {
	                    return this.value; // clean, unformatted number for year
	                },
	                enabled: false
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
	            },
	            series: {
	                stacking: 'normal',
	            }
	        },
	        credits: {
			      enabled: false
			  },
	        colors: ['#7a7a7a', '#7a7a7a', '#7a7a7a'],
	        series: [
	        	{
		            name: 'Hours',
		            data: [
			            @foreach ($county_list as $key => $item)
		        		<?php $diff=($data_county_actual[$key]*100)/(($data_county_budget[$key]>0)?$data_county_budget[$key]:1); 
		        			if($diff<97){
								$color=$col_green;
							}elseif($diff<=103){
								$color=$col_yellow;
							}else{
								$color=$col_red;
							}
		        		?>
			            	{ y: {{number_format($data_county_actual[$key],2,'.', '')}}, color: '{{$color}}' },
			        	@endforeach    
		            ]	
			    },
		    	{
		            type: 'line',
		            dashStyle: 'shortdot',
		            name: 'Target',
		            data: [
		            	@foreach ($county_list as $key => $item)
		            		{{$data_county_budget[$key]}},
		            	@endforeach
		            	],
		            marker: {
		                lineWidth: 1,
		                lineColor: '#000000',
		                fillColor: '#ffffff'
		            },
		            

		        }
		    	
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
	            	@foreach ($industry_list as $key => $item)
	            	'{{$item}}',
	            	@endforeach
	            ],
	            labels: {
	                formatter: function () {
	                    return this.value; // clean, unformatted number for year
	                },
	                enabled: false
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
	            },
	            series: {
	                stacking: 'normal',
	            }

	        },
	        credits: {
			      enabled: false
			  },
	        colors: ['#7a7a7a', '#7a7a7a', '#7a7a7a'],
	        series: [
	        	{
		            name: 'Hours',
		            data: [
		            @foreach ($industry_list as $key => $item)
	        		<?php $diff=($data_industry_actual[$key]*100)/(($data_industry_budget[$key]>0)?$data_industry_budget[$key]:1); 
	        			if($diff<97){
							$color=$col_green;
						}elseif($diff<=103){
							$color=$col_yellow;
						}else{
							$color=$col_red;
						}
	        		?>
		            { y: {{number_format($data_industry_actual[$key],2,'.', '')}}, color: '{{$color}}' },
		        	@endforeach    
		            ]	
			    },
			    {
		            type: 'line',
		            dashStyle: 'shortdot',
		            name: 'Target',
		            data: [
		            	@foreach ($industry_list as $key => $item)
		            		{{$data_industry_budget[$key]}},
		            	@endforeach
		            	],
		            marker: {
		                lineWidth: 1,
		                lineColor: '#000000',
		                fillColor: '#ffffff'
		            },
		            

		        }
		    	
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
	            	@foreach ($mayor_list as $key => $item)
	            	'{{$item}}',
	            	@endforeach
	            ],
	            labels: {
	                formatter: function () {
	                    return this.value; // clean, unformatted number for year
	                },
	                enabled: false
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
	            },
	            series: {
	                stacking: 'normal',
	            }
	        },
	        credits: {
			      enabled: false
			  },
	        colors: ['#7a7a7a', '#7a7a7a', '#7a7a7a'],
	        series: [
	        	{
		            name: 'Hours',
		            data: [
		            	@foreach ($mayor_list as $key => $item)
		        		<?php $diff=($data_mayor_actual[$key]*100)/(($data_mayor_budget[$key]>0)?$data_mayor_budget[$key]:1); 
		        			if($diff<97){
								$color=$col_green;
							}elseif($diff<=103){
								$color=$col_yellow;
							}else{
								$color=$col_red;
							}
		        		?>
		            	{ y: {{number_format($data_mayor_actual[$key],2,'.', '')}}, color: '{{$color}}' },
		            	@endforeach
		            ]	
			    },
			    {
		            type: 'line',
		            dashStyle: 'shortdot',
		            name: 'Target',
		            data: [
		            	@foreach ($mayor_list as $key => $item)
		            		{{$data_mayor_budget[$key]}},
		            	@endforeach
		            	],
		            marker: {
		                lineWidth: 1,
		                lineColor: '#000000',
		                fillColor: '#ffffff'
		            },
		            

		        }
		    	
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
	                @foreach ($manager_list as $key => $item)
	            	'{{$item}}',
	            	@endforeach
	            ],
	            labels: {
	                formatter: function () {
	                    return this.value; // clean, unformatted number for year
	                },
	                enabled: false
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
	            },
	            series: {
	                stacking: 'normal',
	            }
	        },
	        credits: {
			      enabled: false
			  },
	        colors: ['#7a7a7a', '#7a7a7a', '#7a7a7a'],
	        series: [
	        	{
		            name: 'Hours',
		            data: [
		            @foreach ($manager_list as $key => $item)
	        		<?php $diff=($data_manager_actual[$key]*100)/(($data_manager_budget[$key]>0)?$data_manager_budget[$key]:1); 
	        			if($diff<97){
							$color=$col_green;
						}elseif($diff<=103){
							$color=$col_yellow;
						}else{
							$color=$col_red;
						}
	        		?>	
		            { y: {{number_format($data_manager_actual[$key],2,'.', '')}}, color: '{{$color}}' },
		            @endforeach
		            ]	
			    },
			    {
		            type: 'line',
		            dashStyle: 'shortdot',
		            name: 'Target',
		            data: [
		            	@foreach ($manager_list as $key => $item)
		            		{{$data_manager_budget[$key]}},
		            	@endforeach
		            	],
		            marker: {
		                lineWidth: 1,
		                lineColor: '#000000',
		                fillColor: '#ffffff'
		            },
		        }
		    	
	        ]
	    });
	    $('.input-daterange').datepicker({
	        startDate: "{{ date( 'm/d/Y', strtotime( $date_ini_data) )}}",
	        endDate: "{{ date( 'm/d/Y', strtotime( $date_end_data) )}}"
	    });
	});
  </script>
@endsection
