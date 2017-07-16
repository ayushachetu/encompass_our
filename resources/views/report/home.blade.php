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
				<a href="/report/sales-detail" class="btn btn-success btn-lg">Details <span class="icon  ti-angle-double-right "></span></a>
			</div>
		</div>
		<!-- /pageheader -->  
		<div class="main-content">
			<div class="row">
				<div class="col-md-12">
					<div class="filter-box-top">
						<div class="row">
							<div class="col-md-2">
								<span class="label-range">Set Months Range:</span>	
							</div>
							<div class="col-md-1">
								<div class="text-center label-range"><strong>From</strong></div>	
							</div>
							<div class="col-md-2">
								<select class="form-control filter-field" id="ini_month_filter" data-type="1">
									<option value="1" {{($month_ini==1)?'selected="selected"':''}}>January</option>
								    <option value="2" {{($month_ini==2)?'selected="selected"':''}}>February</option>
								    <option value="3" {{($month_ini==3)?'selected="selected"':''}}>March</option>
								    <option value="4" {{($month_ini==4)?'selected="selected"':''}}>April</option>
								    <option value="5" {{($month_ini==5)?'selected="selected"':''}}>May</option>
								    <option value="6" {{($month_ini==6)?'selected="selected"':''}}>June</option>
								    <option value="7" {{($month_ini==7)?'selected="selected"':''}}>July</option>
								    <option value="8" {{($month_ini==8)?'selected="selected"':''}}>August</option>
								    <option value="9" {{($month_ini==9)?'selected="selected"':''}}>September</option>
								    <option value="10" {{($month_ini==10)?'selected="selected"':''}}>October</option>
								    <option value="11" {{($month_ini==11)?'selected="selected"':''}}>November</option>
								    <option value="12" {{($month_ini==12)?'selected="selected"':''}}>December</option>
								</select>
							</div>
							<div class="col-md-1">
								<div class="text-center label-range"><strong>To</strong></div>	
							</div>
							<div class="col-md-2">
								<select class="form-control filter-field" id="end_month_filter" data-type="2">
									<option value="1" {{($month_end==1)?'selected="selected"':''}}>January</option>
								    <option value="2" {{($month_end==2)?'selected="selected"':''}}>February</option>
								    <option value="3" {{($month_end==3)?'selected="selected"':''}}>March</option>
								    <option value="4" {{($month_end==4)?'selected="selected"':''}}>April</option>
								    <option value="5" {{($month_end==5)?'selected="selected"':''}}>May</option>
								    <option value="6" {{($month_end==6)?'selected="selected"':''}}>June</option>
								    <option value="7" {{($month_end==7)?'selected="selected"':''}}>July</option>
								    <option value="8" {{($month_end==8)?'selected="selected"':''}}>August</option>
								    <option value="9" {{($month_end==9)?'selected="selected"':''}}>September</option>
								    <option value="10" {{($month_end==10)?'selected="selected"':''}}>October</option>
								    <option value="11" {{($month_end==11)?'selected="selected"':''}}>November</option>
								    <option value="12" {{($month_end==12)?'selected="selected"':''}}>December</option>
								</select>
							</div>
							<div class="col-md-2">
								<select class="form-control filter-field" id="year_filter" data-type="3">
									<option value="12" {{($year_filter==12)?'selected="selected"':''}}>2017</option>
									<option value="0" {{($year_filter==0)?'selected="selected"':''}}>2016</option>
								</select>
							</div>
							<div class="col-md-1">
								<a id="filter-btn" ref="javascript:void(0)" class="btn btn-primary btn-block btn-lg"><span class="fa fa-filter"></span></a>
							</div>
							<div class="col-md-1">
								<a href="/reports/pdf/1" class="btn btn-default btn-block btn-lg"><span class="fa fa-file-pdf-o fa-1x"></span></a>
							</div>
						</div>
						
					</div>
					
				</div>
			</div>
			<!--row-->
			<div class="row">
				<div class="col-md-6 col-xs-12 col-sm-6 ">
					<div id="graph-county"></div>
					<div>
						<a class="btn btn-block btn-info" href="{{url('/')}}/report/ytd-county" >View Details</a>
					</div>
				</div>
				<div class="col-md-6 col-xs-12 col-sm-6 ">
					<div id="graph-industry"></div>
					<div>
						<a class="btn btn-block btn-info" href="{{url('/')}}/report/ytd-industry" >View Details</a>
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				
				<div class="col-md-6 col-xs-12 col-sm-6 ">
					<div id="graph-mayor"></div>
					<div>
						<a class="btn btn-block btn-info" href="{{url('/')}}/report/ytd-mayor-account" >View Details</a>
					</div>
				</div>
				<div class="col-md-6 col-xs-12 col-sm-6 ">
					<div id="graph-manager"></div>
					<div>
						<a class="btn btn-block btn-info" href="{{url('/')}}/report/ytd-manager" >View Details</a>
					</div>
				</div>
			</div>
			<!--row-->
			<div class="row">
				
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
  <script type="text/javascript">
  	$('#filter-btn').click(function() {
  		  var ini_month=$('#ini_month_filter').val();
  		  var end_month=$('#end_month_filter').val();
  		  var year=$('#year_filter').val();

	      $('#wrapper-dates').hide();
	      $('#wrapper-dates-loader').show();
	      $.ajax({
	        url:  '/report/filter-values/'+ini_month+'/'+end_month+'/'+year,
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
@endsection
