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
				<i class="icon ti-briefcase page_header_icon"></i>
				<span class="main-text">Budget</span>
				<p class="text"><a href="/reports">< Back to report dashboard</a></p>
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
						<h2>Report Graph: {{$ini_range}}/01/{{date('Y')}} - {{$end_range}}/{{date("t", strtotime(date('Y').'-'.$end_range.'-'.date('d')))}}/{{date('Y')}}</h2>
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
										$total[$i]+=$item->total;
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
						<div class="row">
							<div class="col-md-8">
								<div id="chart_div"></div>
							</div>
							<div class="col-md-4">
								
							</div>
								
						</div>

						<div class="row">
							<div class="col-md-4">
								<div id="chart_div_1"></div>
							</div>
							<div class="col-md-4">
								<div id="chart_div_3"></div>
							</div>
							<div class="col-md-4">
								<div id="chart_div_4"></div>
							</div>
								
						</div>

						<div class="row">
							<div class="col-md-4">
								<div id="chart_div_5"></div>
							</div>
							<div class="col-md-4">
								<div id="chart_div_6"></div>
							</div>
							<div class="col-md-4">
								<div id="chart_div_7"></div>
							</div>
								
						</div>
						<div class="row">
							<div class="col-md-4">
								<div id="chart_div_8"></div>
							</div>
							<div class="col-md-4">
								<div id="chart_div_9"></div>
							</div>
							<div class="col-md-4">
								<div id="chart_div_10"></div>
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
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
  	google.charts.load('current', {packages: ['corechart', 'bar']});
	/*google.charts.setOnLoadCallback(drawMain);*/

	function drawMain() {
	      var data = new google.visualization.DataTable();
	      data.addColumn('string', 'Job');
	      data.addColumn('number', 'Actual');
	      data.addColumn('number', 'Budget');

	        
	      data.addRows([
	        ['Healtcare', {{$tk_total_1}}, {{$total_1}}],
	        ['Education', {{$tk_total_3}}, {{$total_3}}],
	        ['Commercial', {{$tk_total_4}}, {{$total_4}}],
	        ['Hospitality', {{$tk_total_5}}, {{$total_5}}],
	        ['Government', {{$tk_total_6}}, {{$total_6}}],
	        ['Public Venue', {{$tk_total_7}}, {{$total_7}}],
	        ['Retail', {{number_format($tk_total_8,2,'.','')}}, {{number_format($total_8,2,'.','')}}],
	        ['Industrial', {{number_format($tk_total_9,2,'.','')}}, {{number_format($total_9,2,'.','')}}],
	        ['Event', {{number_format($tk_total_10,2,'.','')}}, {{number_format($total_10,2,'.','')}}],
	      ]);

	        
	      var options = {
	      	height: 400,
	        title: 'Job Cost',
	        vAxis: {
	          title: 'Actual vs Budget'
	        },
	        colors: ['{{$color[1]}}', '#7a7a7a', '{{$color[3]}}', '#7a7a7a', '{{$color[4]}}', '#7a7a7a', '{{$color[5]}}', '#7a7a7a', '{{$color[6]}}', '#7a7a7a', '{{$color[7]}}', '#7a7a7a', '{{$color[8]}}', '#7a7a7a', '{{$color[9]}}', '#7a7a7a', '{{$color[10]}}', '#7a7a7a']
	      };

	      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
	      chart.draw(data, options);
	    }

	google.charts.setOnLoadCallback(draw1);

	function draw1() {
	      var data = new google.visualization.DataTable();
	      data.addColumn('string', 'Job');
	      data.addColumn('number', 'Actual');
	      data.addColumn('number', 'Budget');

	        
	      data.addRows([
	        ['Healtcare', {{$tk_total[1]}}, {{$total[1]}}],
	      ]);

	        
	      var options = {
	      	height: 400,
	        title: 'Job Cost : Healthcare',
	        vAxis: {
	          title: 'Actual vs Budget',
	          baseline: 0
	        },
	        colors: ['{{$color[1]}}', '#7a7a7a']
	      };

	      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_1'));
	      chart.draw(data, options);
	    }

	 google.charts.setOnLoadCallback(draw3);

	function draw3() {
	      var data = new google.visualization.DataTable();
	      data.addColumn('string', 'Job');
	      data.addColumn('number', 'Actual');
	      data.addColumn('number', 'Budget');

	        
	      data.addRows([
	        ['Education', {{$tk_total[3]}}, {{$total[3]}}],
	      ]);

	        
	      var options = {
	      	height: 400,
	        title: 'Job Cost : Education',
	        vAxis: {
	          title: 'Actual vs Budget',
	          baseline: 0
	        },
			colors: ['{{$color[3]}}', '#7a7a7a']
	      };

	      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_3'));
	      chart.draw(data, options);
	    }

	    google.charts.setOnLoadCallback(draw4);

	function draw4() {
	      var data = new google.visualization.DataTable();
	      data.addColumn('string', 'Job');
	      data.addColumn('number', 'Actual');
	      data.addColumn('number', 'Budget');

	        
	      data.addRows([
	        ['Commercial', {{$tk_total[4]}}, {{$total[4]}}],
	      ]);

	        
	      var options = {
	      	height: 400,
	        title: 'Job Cost : Commercial',
	        vAxis: {
	          title: 'Actual vs Budget',
	          baseline: 0
	        },
	        colors: ['{{$color[4]}}', '#7a7a7a']
	      };

	      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_4'));
	      chart.draw(data, options);
	    }

	    google.charts.setOnLoadCallback(draw5);

	function draw5() {
	      var data = new google.visualization.DataTable();
	      data.addColumn('string', 'Job');
	      data.addColumn('number', 'Actual');
	      data.addColumn('number', 'Budget');

	        
	      data.addRows([
	        ['Hospitality', {{$tk_total[5]}}, {{$total[5]}}],
	      ]);

	        
	      var options = {
	      	height: 400,
	        title: 'Job Cost : Hospitality',
	        vAxis: {
	          title: 'Actual vs Budget',
	          baseline: 0
	        },
	        colors: ['{{$color[5]}}', '#7a7a7a']
	      };

	      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_5'));
	      chart.draw(data, options);
	    }

	    google.charts.setOnLoadCallback(draw6);

	function draw6() {
	      var data = new google.visualization.DataTable();
	      data.addColumn('string', 'Job');
	      data.addColumn('number', 'Actual');
	      data.addColumn('number', 'Budget');

	        
	      data.addRows([
	        ['Government', {{$tk_total[6]}}, {{$total[6]}}],
	      ]);

	        
	      var options = {
	      	height: 400,
	        title: 'Job Cost : Government',
	        vAxis: {
	          title: 'Actual vs Budget',
	          baseline: 0
	        },
	        colors: ['{{$color[6]}}', '#7a7a7a']
	      };

	      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_6'));
	      chart.draw(data, options);
	    }

	    google.charts.setOnLoadCallback(draw7);

	function draw7() {
	      var data = new google.visualization.DataTable();
	      data.addColumn('string', 'Job');
	      data.addColumn('number', 'Actual');
	      data.addColumn('number', 'Budget');

	        
	      data.addRows([
	        ['Public Venue', {{$tk_total[7]}}, {{$total[7]}}],
	      ]);

	        
	      var options = {
	      	height: 400,
	        title: 'Job Cost : Public Venue',
	        vAxis: {
	          title: 'Actual vs Budget',
	          baseline: 0
	        },
	        colors: ['{{$color[7]}}', '#7a7a7a']
	      };

	      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_7'));
	      chart.draw(data, options);
	    }

	    google.charts.setOnLoadCallback(draw8);

	function draw8() {
	      var data = new google.visualization.DataTable();
	      data.addColumn('string', 'Job');
	      data.addColumn('number', 'Actual');
	      data.addColumn('number', 'Budget');

	        
	      data.addRows([
	        ['Retail', {{number_format($tk_total[8],2,'.','')}}, {{number_format($total[8],2,'.','')}}],
	      ]);

	        
	      var options = {
	      	height: 400,
	        title: 'Job Cost : Retail',
	        vAxis: {
	          title: 'Actual vs Budget',
	          baseline: 0
	        },
	        colors: ['{{$color[8]}}', '#7a7a7a']
	      };

	      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_8'));
	      chart.draw(data, options);
	    }

	    google.charts.setOnLoadCallback(draw9);

	function draw9() {
	      var data = new google.visualization.DataTable();
	      data.addColumn('string', 'Job');
	      data.addColumn('number', 'Actual');
	      data.addColumn('number', 'Budget');

	        
	      data.addRows([
	        ['Industrial', {{number_format($tk_total[9],2,'.','')}}, {{number_format($total[9],2,'.','')}}],
	      ]);

	        
	      var options = {
	      	height: 400,
	        title: 'Job Cost : Industrial',
	        vAxis: {
	          title: 'Actual vs Budget',
	          baseline: 0
	        },
	        colors: ['{{$color[9]}}', '#7a7a7a']
	      };

	      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_9'));
	      chart.draw(data, options);
	    }
  </script>

@endsection
