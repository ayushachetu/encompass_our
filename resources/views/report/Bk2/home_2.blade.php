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
					<option value="1" {{(($type==1)?"selected='selected'":"")}}>Sales</option>
					<option value="2" {{(($type==2)?"selected='selected'":"")}}>Profitability</option>
				</select>
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
							<div class="col-md-3">
								<select class="form-control" id="ini_month">
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
							<div class="col-md-3">
								<select class="form-control" id="end_month">
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
							<div class="col-md-2 text-right">
								<a href="/budget/report" class="btn btn-success btn-lg">Details</a>
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
				<div class="col-md-12 col-xs-12 col-sm-12 ">
					<div id="graph-county"></div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12 ">
					<div id="graph-industry"></div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12">
					<div id="graph-mayor"></div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12">
					<div id="graph-manager"></div>
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
  <script type="text/javascript" src="{{ asset('assets/js/jquery.countTo.js') }}"></script>
  <script type="text/javascript">
  	$('#ini_month').on('change', function (e) {        
      var value_month=$('#ini_month').val();
      $('#wrapper-dates').hide();
      $('#wrapper-dates-loader').show();
      $.ajax({
        url:  '/report/change-month/1/'+value_month,
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

  	$('#end_month').on('change', function (e) {        
      var value_month=$('#end_month').val();
      $('#wrapper-dates').hide();
      $('#wrapper-dates-loader').show();
      $.ajax({
        url:  '/report/change-month/2/'+value_month,
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

  	$('#report-type').on('change', function (e) { 
  		window.location.replace("/reports/"+$(this).val());
  	});	
  </script>
  <script type="text/javascript" src="{{ asset('assets/code/highcharts.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/code/themes/sand-signika.js') }}"></script>
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
	        subtitle: {
	            text: '<a href="{{url('/')}}/report/profitability/1" style="color:#8cc63f;">View Details</a>'
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
	        colors: ['#f45b5b', '#7a7a7a'],
	        series: [
	        
	        	{
		            name: 'Actual',
		            data: [
		            	@foreach ($data_county_actual as $key => $item)
		            		<?php $diff=($data_county_actual[$key]*100)/(($data_county_budget[$key]>0)?$data_county_budget[$key]:1); 
		            			if($diff<100){
									$color=$col_red;
								}elseif($diff<=103){
									$color=$col_yellow;
								}else{
									$color=$col_green;
								}
		            		?>
		            		{ y: {{$item}}, color: '{{$color}}' },
		            	@endforeach
		            	],	
		        },
	    		{
		            name: 'Budget',
		            data: [
		            	@foreach ($data_county_budget as $key => $item)
		            		{{$item}},
		            	@endforeach
		            ],

		        },
	        ]
	    });
	    $('#graph-industry').highcharts({
	        chart: {
	            type: 'column',
	        },
	        title: {
	            text: 'Industry'
	        },
	        subtitle: {
	            text: '<a href="{{url('/')}}/report/profitability/2" style="color:#8cc63f;">View Details</a>'
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
	        colors: ['#f45b5b', '#7a7a7a'],
	        series: [
	        
		        {
		            name: 'Actual',
		            data: [
		            	@foreach ($data_industry_actual as $key => $item)
		            		<?php $diff=($data_industry_actual[$key]*100)/(($data_industry_budget[$key]>0)?$data_industry_budget[$key]:1); 
		            			if($diff<100){
									$color=$col_red;
								}elseif($diff<=103){
									$color=$col_yellow;
								}else{
									$color=$col_green;
								}
		            		?>
		            		{ y: {{$item}}, color: '{{$color}}' },
		            	@endforeach
		            	]		

		        },
	    		{
		            name: 'Budget',

		            data: [
		            	@foreach ($data_industry_budget as $key => $item)
		            		{{$item}},
		            	@endforeach
		            ],

		        },
	    	
	        ]
	    });
	    $('#graph-mayor').highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: 'Mayor Account'
	        },
	        subtitle: {
	            text: '<a href="{{url('/')}}/report/profitability/3" style="color:#8cc63f;">View Details</a>'
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
	        colors: ['#f45b5b', '#7a7a7a'],
	        series: [
	    		{
		            name: 'Actual',
		            data: [
		            	@foreach ($data_mayor_actual as $key => $item)
		            		<?php $diff=($data_mayor_actual[$key]*100)/(($data_mayor_budget[$key]>0)?$data_mayor_budget[$key]:1); 
		            			if($diff<100){
									$color=$col_red;
								}elseif($diff<=103){
									$color=$col_yellow;
								}else{
									$color=$col_green;
								}
		            		?>
		            		{ y: {{$item}}, color: '{{$color}}' },
		            	@endforeach
		            	]		

		        },
	    		{
		            name: 'Budget',

		            data: [
		            	@foreach ($data_mayor_budget as $key => $item)
		            		{{$item}},
		            	@endforeach
		            ],

		        },
	        ]
	    });
	    $('#graph-manager').highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: 'Manager'
	        },
	        subtitle: {
	            text: '<a href="{{url('/')}}/report/profitability/4" style="color:#8cc63f;">View Details</a>'
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
	        colors: ['#f45b5b', '#7a7a7a'],
	        series: [
	    	{
	            name: 'Actual',
	            data: [
	            	@foreach ($data_manager_actual as $key => $item)
	            		<?php $diff=($data_manager_actual[$key]*100)/(($data_manager_budget[$key]>0)?$data_manager_budget[$key]:1); 
		            			if($diff<100){
									$color=$col_red;
								}elseif($diff<=103){
									$color=$col_yellow;
								}else{
									$color=$col_green;
								}
		            		?>
	            		{ y: {{$item}}, color: '{{$color}}' },
	            	@endforeach
	            	]		

	        },
    		{
	            name: 'Budget',

	            data: [
	            	@foreach ($data_manager_budget as $key => $item)
	            		{{$item}},
	            	@endforeach
	            ],

	        },
	        ]
	    });
	});
  </script>
@endsection
