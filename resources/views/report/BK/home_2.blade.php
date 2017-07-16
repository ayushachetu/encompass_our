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
				$total_1=$total_3=$total_4=$total_5=$total_6=$total_7=$total_8=$total_9=$total_10=0;
				$tk_total_1=$tk_total_3=$tk_total_4=$tk_total_5=$tk_total_6=$tk_total_7=$tk_total_8=$tk_total_9=$tk_total_10=0;

				$total=array();
				$tk_total=array();

				$diff=array();
				$color=array();

				//Inicialate variables
				for ($i=1; $i <=10 ; $i++) { 
					$total[$i]=0;
				}

				for ($i=1; $i <=10 ; $i++) { 
					$tk_total[$i]=0;
				}	

				for ($i=1; $i <=10 ; $i++) { 
					if($i!=2){	
						foreach ($sum[$i] as $item){
							$total[$i]+=$item->total_job+$item->total;
						}		
					}
				}

				for ($i=1; $i <=10 ; $i++) { 
					if($i!=2){	
						foreach ($tk_sum[$i] as $item){
							$tk_total[$i]+=$item->total;
						}		
					}
				}
				//echo var_dump($total);
				//echo var_dump($tk_total);

				//Differencial

				$col_green="#0CC935";
				$col_yellow="#FCE327";
				$col_red="#EC2323";

				for ($i=1; $i <=10 ; $i++) { 
					if($i!=2){
						$diff[$i]=($tk_total[$i]*100)/(($total[$i]>0)?$total[$i]:1);									
					}
				}


				for ($i=1; $i <=10 ; $i++) { 
					if($i!=2){
						if($diff[$i]<100){
							$color[$i]=$col_red;
						}elseif($diff[$i]<=103){
							$color[$i]=$col_yellow;
						}else{
							$color[$i]=$col_green;
						}
					}
				}								

			?>	
			<!--row-->
			<div class="row">
				<div class="col-md-3">
					<div id="chart_div_1" style="height: 300px;"></div>
				</div>
				<div class="col-md-3">
					<div id="chart_div_3" style="height: 300px;"></div>
				</div>
				<div class="col-md-3">
					<div id="chart_div_4" style="height: 300px;"></div>
				</div>
				<div class="col-md-3">
					<div id="chart_div_5" style="height: 300px;"></div>
				</div>
					
			</div>
			<br/>
			<div class="row">
				
				<div class="col-md-3">
					<div id="chart_div_6" style="height: 300px;"></div>
				</div>
				<div class="col-md-3">
					<div id="chart_div_7" style="height: 300px;"></div>
				</div>
				<div class="col-md-3">
					<div id="chart_div_8" style="height: 300px;"></div>
				</div>
				<div class="col-md-3">
					<div id="chart_div_9" style="height: 300px;"></div>
				</div>
					
			</div>
			<br/>
			<div class="row">
				
				<div class="col-md-4">
					<div id="chart_div_10"></div>
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
  	$('#chart_div_1').highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	            categories: ['Actual', 'Budget']
	        },
	        credits: {
	            enabled: false
	        },
	        exporting: {
			         enabled: false
			},
	        colors: ['{{$color[1]}}', '#7a7a7a'],
	        series: [{
	            name: 'Healthcare',
	            data: [{{$tk_total[1]}}, {{$total[1]}}],
	            colorByPoint: true
	        }]
	    });

	    $('#chart_div_3').highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	            categories: ['Actual', 'Budget']
	        },
	        credits: {
	            enabled: false
	        },
	        exporting: {
			         enabled: false
			},
	        colors: ['{{$color[3]}}', '#7a7a7a'],
	        series: [{
	            name: 'Education',
	            data: [{{$tk_total[3]}}, {{$total[3]}}],
	            colorByPoint: true
	        }]
	    });

	    $('#chart_div_4').highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	            categories: ['Actual', 'Budget']
	        },
	        credits: {
	            enabled: false
	        },
	        exporting: {
			         enabled: false
			},
	        colors: ['{{$color[4]}}', '#7a7a7a'],
	        series: [{
	            name: 'Commercial',
	            data: [{{$tk_total[4]}}, {{$total[4]}}],
	            colorByPoint: true
	        }]
	    });

	    $('#chart_div_5').highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	            categories: ['Actual', 'Budget']
	        },
	        credits: {
	            enabled: false
	        },
	        exporting: {
			         enabled: false
			},
	        colors: ['{{$color[5]}}', '#7a7a7a'],
	        series: [{
	            name: 'Hospitality',
	            data: [{{$tk_total[5]}}, {{$total[5]}}],
	            colorByPoint: true
	        }]
	    });

	    $('#chart_div_6').highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	            categories: ['Actual', 'Budget']
	        },
	        credits: {
	            enabled: false
	        },
	        exporting: {
			         enabled: false
			},
	        colors: ['{{$color[6]}}', '#7a7a7a'],
	        series: [{
	            name: 'Government',
	            data: [{{$tk_total[6]}}, {{$total[6]}}],
	            colorByPoint: true
	        }]
	    });

	    $('#chart_div_7').highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	            categories: ['Actual', 'Budget']
	        },
	        credits: {
	            enabled: false
	        },
	        exporting: {
			         enabled: false
			},
	        colors: ['{{$color[7]}}', '#7a7a7a'],
	        series: [{
	            name: 'Public Venue',
	            data: [{{$tk_total[7]}}, {{$total[7]}}],
	            colorByPoint: true
	        }]
	    });

	    $('#chart_div_8').highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	            categories: ['Actual', 'Budget']
	        },
	        credits: {
	            enabled: false
	        },
	        exporting: {
			         enabled: false
			},
	        colors: ['{{$color[8]}}', '#7a7a7a'],
	        series: [{
	            name: 'Retail',
	            data: [{{$tk_total[8]}}, {{$total[8]}}],
	            colorByPoint: true
	        }]
	    });

	    $('#chart_div_9').highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	            categories: ['Actual', 'Budget']
	        },
	        credits: {
	            enabled: false
	        },
	        exporting: {
			         enabled: false
			},
	        colors: ['{{$color[9]}}', '#7a7a7a'],
	        series: [{
	            name: 'Industrial',
	            data: [{{$tk_total[9]}}, {{$total[9]}}],
	            colorByPoint: true
	        }]
	    });
  </script>
@endsection
