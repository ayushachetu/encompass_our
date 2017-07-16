@extends('layouts.default')
@section('styles')

@endsection
@section('content')
	<div class="piluku-preloader text-center">
		<div class="loader">Loading...</div>
	  </div>
	<div class="wrapper">	  
	<div class="left-bar ">
		<div class="admin-logo">
			<div class="logo-holder pull-left">
				<span>Encompassonsite</span>
			</div>
			<!-- logo-holder -->			
			<a href="#" class="menu-bar  pull-right"><i class="ti-menu"></i></a>
		</div>
		{!! $sidebar_template !!}
	</div>
	<!-- /left-bar -->

	<div class="content" id="content">
		<div class="overlay"></div>			
		
		@include('includes.topbar')
		<div class="main-content">
		@if($role_user!=Config::get('roles.SUPERVISOR') && $role_user!=Config::get('roles.AREA_SUPERVISOR') && $role_user!=Config::get('roles.EMPLOYEE')) 	
			<!-- Second Row -->
			<div class="row grid">
				<div class="col-md-3">
					<div class="pricing-header facts-header">
						Quick Facts <span class="ion-ios-play-outline"></span>
					</div>
				</div>
				<?php 
					if(is_object($count_employee)) $count_employee=0;
					if(is_object($count_portfolio)) $count_portfolio=0;
				?>
				<div class="col-md-3">
					<div class="pricing-price facts-header-inner" style="text-align:left;">
						People <span class="badge pull-right">{{$count_employee}}</span>
					</div>
				</div>
				<div class="col-md-3">
					<?php 
						$display_sf=$sum_square_feet;
						if($display_sf>1000){
							$display_sf=number_format(substr($display_sf, 0, -6),0)."K";
						}else{
							if($display_sf==1)
								$display_sf=0;	
							else	
							$display_sf=number_format($display_sf,2);
						}
					?>
					<div class="pricing-price facts-header-inner" style="text-align:left;">
						SF <span class="badge pull-right">{{$display_sf}}</span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="pricing-price facts-header-inner" style="text-align:left;">
						Accounts <span class="badge pull-right">{{$count_portfolio}}</span>
					</div>
				</div>
			</div>
		@endif
		<div class="announcement-panel">
			@foreach ($result_announce as $announce)
				<div class="announcement-item">
					<div class="announcement-title">
						<span class="ti-announcement"></span> {{$announce->title}}: <span class="announcement-body">{{$announce->message}}</span>
					</div>
				</div>
			@endforeach
		</div>
			<div class="row grid">	
			<!-- /col-md-9 -->
			<div class="col-md-12">
				<!-- panel -->
				<div class="panel panel-piluku">
					<div class="panel-body">
						
						<!--
						<div class="title-heading-main">
							Portfolio Performance
						</div>-->
						@if(($role_user!=Config::get('roles.SUPERVISOR') || $role_user!=Config::get('roles.AREA_SUPERVISOR')) && $role_user!=Config::get('roles.EMPLOYEE')) 			
						<div class="row">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<div class="inner-head-noline">Sales <small>(3 Months Lag)</small></div>
								<div id="sales-graph"></div>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<div class="inner-head-noline"><a href="/report/cost-budget-detail">Expenses vrs Target <small>(4 Weeks Lag)</small></a></div>
								<div id="profitability-graph"></div>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<div class="inner-head-noline"><a href="/report/sales-detail">Top/Bottom <small>(GM) (3 Months Lag)</small></a></div>
								<div>
									<table class="table table-bordered table-top-bottom" style="margin-bottom:  0 ;">
										<?php $cnt=0;?>
										@foreach ($revenue_data['top'] as $item)
											<?php $cnt++;?>
											<tr {!! ($cnt==4)?'style="border-bottom: 2px dashed #d7dce5 !important;"':'' !!}>
												<td><span class="pull-left icon-green-sm"><i class="ion ion-android-arrow-dropup-circle icon-table"></i></span> {{$item->job_description}}</td>
												<td class="text-right">${{number_format($item->total,0)}}</td>
											</tr>
										@endforeach
										@foreach ($revenue_data['bottom'] as $item)
											<tr>
												<td><span class="pull-left icon-red-sm"><i class="ion ion-android-arrow-dropdown-circle icon-table"></i></span> {{$item->job_description}}</td>
												<td class="text-right">${{number_format($item->total,0)}}</td>
											</tr>
										@endforeach
									</table>
								</div>	
							</div>
						</div>
						<div>
							<a class="btn btn-gray btn-round" onclick="$('#collapseTwo').toggle();"><span class="icon ion-ios-plus-empty"></span></a>
						</div>
						<div  id="collapseTwo" >
							<div>
								<div class="inner-head">Salaries & Wages - Direct <small>(Includes taxes & benefits - 4 Week Lag)</small></div>
							</div>
							<div class="row">
								<?php 
							      	$calc_val=$total_salies_wages_amount['labor_tax'];
							        if($calc_val==0)
							            $calc_val=1;
							        $calc_val_past=$total_salies_wages_amount_past['labor_tax'];
							        if($calc_val_past==0)
							            $calc_val_past=1;

							        $calculation_1_lt1=($total_salies_wages_amount['expense']*100)/($calc_val);
							        $calculation_2_lt1=($total_salies_wages_amount_past['expense']*100)/($calc_val_past);
							        $calculation_lt1=$calculation_2_lt1-$calculation_1_lt1;
							      ?>
								<div class="col-md-4">
									<div>
										<h5> % Used v Budgeted($)</h5>
										<div class="row">
											<div class="col-md-12">
												<div id="bar-used-budget-amount" class="ct-chart ct-golden-section"></div>
											</div>	
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<div>
										<h5> WK Change($)</h5>
										<div class="row">
											<div class="col-md-12">
												<?php if($calculation_lt1<0){ ?>
													<span class="pull-left icon-red-sm">
														<i class="ion ion-android-arrow-dropup-circle"></i>
													</span>
												<?php }else{?>
													<span class="pull-left icon-green-sm">
														<i class="ion ion-android-arrow-dropdown-circle"></i>
													</span>
												<?php }?>
												{{number_format($calculation_lt1,2)}}% 
												<div style="display:none">
													Budget: {{$total_salies_wages_amount['labor_tax']}}<br/>
													Expense: {{$total_salies_wages_amount['expense']}}<br/>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div>
										<h5> % Used v Budgeted(Hrs)</h5>
										<div class="row">
											<div class="col-md-12">
												<div id="bar-used-budget-hours" class="ct-chart ct-golden-section"></div>
											</div>	
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<?php 
										$calculation_budget=$total_hours['used']/(($total_hours['budget']==0)?1:$total_hours['budget']); 
										$calculation_budget_past=$total_hours_past['used']/(($total_hours_past['budget']==0)?1:$total_hours_past['budget']); 
										$calc_b1=$calculation_budget*100;
										$calc_b2=$calculation_budget_past*100;
										$calc_b_diff=$calc_b2-$calc_b1;

									?>
									<div>
										<h5> WK Change(Hrs)</h5>
										<div class="row">
											<div class="col-md-12">
												<?php if($calc_b_diff<0){ ?>
													<span class="pull-left icon-red-sm">
														<i class="ion ion-android-arrow-dropup-circle"></i>
													</span>
												<?php }else{?>
													<span class="pull-left icon-green-sm">
														<i class="ion ion-android-arrow-dropdown-circle"></i>
													</span>
												<?php }?>
												{{number_format($calc_b_diff,2)}}% 
											</div>	
										</div>
									</div>
								</div>
							</div>
							<div>
								<div class="inner-head">Supplies<small>(Includes taxes & benefits - 4 Week lag)</small></div>
							</div>
							<?php 
						      	$calc_val=$total_supplies['budget_monthly'];
						        if($calc_val==0)
						            $calc_val=1;
						        $calculation_1=($total_supplies['expense']*100)/($calc_val);
						        $calculation_2=($total_supplies_past['expense']*100)/($calc_val);
						        $calculation=$calculation_2-$calculation_1;
								
						      ?>
							<div class="row">
								<div class="col-md-4">
									<div>
										<h5> Supplies $ vs Budget</h5>
										<div class="row">
											<div class="col-md-12">
												<div id="bar-used-supplies" class="ct-chart ct-golden-section"></div>
											</div>	
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<div>
										<h5> WK Change($)</h5>
										<div class="row">
											<div class="col-md-12">
												<?php if($calculation<0){ ?>
													<span class="pull-left icon-red-sm">
														<i class="ion ion-android-arrow-dropup-circle"></i>
													</span>
												<?php }else{?>
													<span class="pull-left icon-green-sm">
														<i class="ion ion-android-arrow-dropdown-circle"></i>
													</span>
												<?php }?>
												{{number_format($calculation,2)}}% 
											</div>	
										</div>
									</div>
								</div>
							</div>	
						</div>
						@endif	
						@if(($role_user==Config::get('roles.AREA_MANAGER') && $job_portfolio_id==0) || ($role_user==Config::get('roles.DIR_POS')) || $role_user==Config::get('roles.SUPERVISOR') || $role_user==Config::get('roles.AREA_SUPERVISOR') || $role_user==Config::get('roles.EMPLOYEE'))
							<hr class="tick-seperator"/>
							<div class="row">
							<div class="col-md-2 col-sm-6 col-xs-12">
								<div>
									<h5 style="margin-bottom: 0px;padding-top: 10px;margin-top: 5px;">Personal Performance:
									<span class=""><span class="fa fa-info-circle info-icon" data-container="body" data-html="true" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="<div class='metric-box' style='margin:0;'><div class='text-red'><strong> &lt;1.9 </strong></div><div class='text-orange'><strong> 1.91 - 2.1 </strong></div><div class='text-light-green'><strong> 2.11-2.7 </strong></div><div class='text-green'><strong>  >2.7 </strong></div></div>" ></span></span>
									</h5>
									<div>(4 week lagging)</div>
								</div>
							</div>
							<div class="col-md-5 col-sm-6 col-xs-12">
								<?php 
									if(is_object($total_evaluation)) $total_evaluation=0;
									if(is_object($total_evaluation_no)) $total_evaluation_no=0;
									if(is_object($total_evaluation_past)) $total_evaluation_past=0;
									if(is_object($total_evaluation_no_past)) $total_evaluation_no_past=0;
									
									$calc_evaluation_now=$total_evaluation/((($total_evaluation_no==0)?1:$total_evaluation_no)*5);
									$calc_evaluation_past=$total_evaluation_past/((($total_evaluation_no_past==0)?1:$total_evaluation_no_past)*5);
									$calc_evaluation_diff=$calc_evaluation_now/(($calc_evaluation_past==0)?1:$calc_evaluation_past);
									$class_color="";
									if($calc_evaluation_now<=1.9){
										$class_color="red";
									}elseif ($calc_evaluation_now<=2.1) {
										$class_color="orange";
									}elseif ($calc_evaluation_now<=2.7) {
										$class_color="light-green";
									}else{
										$class_color="green";
									}

								?>
								<div class="metric-box metric-box-right">
									<div class="right">
										<h5 class="" style="margin-bottom:3px;">
										<?php if($calc_evaluation_diff>0){ ?>
											<span class="pull-left icon-green-sm">
												<i class="ion ion-android-arrow-dropup-circle"></i>
											</span>
										<?php }else{?>
											<span class="pull-left icon-red-sm">
												<i class="ion ion-android-arrow-dropdown-circle"></i>
											</span>
										<?php }?>
										{{ number_format($calc_evaluation_diff*100,2) }}%</h5>
										<small>vs Last Week</small>
									</div>
									
								</div>
								<div class="metric-box">
									<div class="pull-left">
										<i class="ion ion-ios-circle-filled icon-{{$class_color}}" style="font-size:36px;"></i>
									</div>
									<div class="right">
										<h4 class="text-{{$class_color}}" style="font-size: 36px;">
											{{number_format($calc_evaluation_now,2)}}
											@if(($role_user==Config::get('roles.EMPLOYEE')))
												<span class="icon-eval icon-eval-{{$class_color}}"></span>
											@endif
										</h4>
									</div>
								</div>
							</div>
							<div class="col-md-5 col-sm-6 col-xs-12">
									@if(($role_user!=Config::get('roles.SUPERVISOR')) || ($role_user!=Config::get('roles.AREA_SUPERVISOR')))
										<div id="evaluation-graph" class="ct-chart ct-golden-section"></div>	
									@endif
							</div>
						</div>
							<div>
								<a class="btn btn-gray btn-round" onclick="$('#collapseEval').toggle();"><span class="icon ion-ios-plus-empty"></span></a>
								@if(($role_user!=Config::get('roles.EMPLOYEE')))
									<a class="btn btn-gray btn-round" data="0" user-id="{{$user_id}}" id="btn-comments"><span class="icon ion-ios-chatboxes-outline"></span></a>
								@endif
							</div>
							<div id="comments-panel"></div>
							<div  id="collapseEval">
								<div class="row">
									<div class="col-md-3">
										<h5 class="inner-head">Mission/Vision</h5>
										<div>
											<?php 
												$calc_evaluation_now=$total_evaluation_param['param1']/((($total_evaluation_no==0)?1:$total_evaluation_no));
												$class_color="";
												if($calc_evaluation_now<=1.9){
													$class_color="red";
												}elseif ($calc_evaluation_now<=2.1) {
													$class_color="orange";
												}elseif ($calc_evaluation_now<=2.7) {
													$class_color="light-green";
												}else{
													$class_color="green";
												}

											?>
											<div class="metric-box">
												<div class="pull-left">
													<i class="ion ion-ios-circle-filled icon-{{$class_color}}" style="font-size:24px;"></i>
												</div>
												<div class="right">
													<h4 class="text-{{$class_color}}" style="font-size: 24px;">
														{{number_format($calc_evaluation_now,2)}}
													</h4>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<h5 class="inner-head">Value 1 - Trust & Respect</h5>
										<div>
											<?php 
												$calc_evaluation_now=$total_evaluation_param['param2']/((($total_evaluation_no==0)?1:$total_evaluation_no));
												$class_color="";
												if($calc_evaluation_now<=1.9){
													$class_color="red";
												}elseif ($calc_evaluation_now<=2.1) {
													$class_color="orange";
												}elseif ($calc_evaluation_now<=2.7) {
													$class_color="light-green";
												}else{
													$class_color="green";
												}

											?>
											<div class="metric-box">
												<div class="pull-left">
													<i class="ion ion-ios-circle-filled icon-{{$class_color}}" style="font-size:24px;"></i>
												</div>
												<div class="right">
													<h4 class="text-{{$class_color}}" style="font-size: 24px;">{{number_format($calc_evaluation_now,2)}}</h4>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<h5 class="inner-head">Value 2 - Improve Lives</h5>
										<div>
											<?php 
												$calc_evaluation_now=$total_evaluation_param['param3']/((($total_evaluation_no==0)?1:$total_evaluation_no));
												$class_color="";
												if($calc_evaluation_now<=1.9){
													$class_color="red";
												}elseif ($calc_evaluation_now<=2.1) {
													$class_color="orange";
												}elseif ($calc_evaluation_now<=2.7) {
													$class_color="light-green";
												}else{
													$class_color="green";
												}

											?>
											<div class="metric-box">
												<div class="pull-left">
													<i class="ion ion-ios-circle-filled icon-{{$class_color}}" style="font-size:24px;"></i>
												</div>
												<div class="right">
													<h4 class="text-{{$class_color}}" style="font-size: 24px;">{{number_format($calc_evaluation_now,2)}}</h4>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<h5 class="inner-head">Value 3 – Continuous Progress</h5>
										<div>
											<?php 
												$calc_evaluation_now=$total_evaluation_param['param4']/((($total_evaluation_no==0)?1:$total_evaluation_no));
												$class_color="";
												if($calc_evaluation_now<=1.9){
													$class_color="red";
												}elseif ($calc_evaluation_now<=2.1) {
													$class_color="orange";
												}elseif ($calc_evaluation_now<=2.7) {
													$class_color="light-green";
												}else{
													$class_color="green";
												}

											?>
											<div class="metric-box">
												<div class="pull-left">
													<i class="ion ion-ios-circle-filled icon-{{$class_color}}" style="font-size:24px;"></i>
												</div>
												<div class="right">
													<h4 class="text-{{$class_color}}" style="font-size: 24px;">{{number_format($calc_evaluation_now,2)}}</h4>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<h5 class="inner-head">How are their work skills?</h5>
										<div>
											<?php 
												$calc_evaluation_now=$total_evaluation_param['param5']/((($total_evaluation_no==0)?1:$total_evaluation_no));
												$class_color="";
												if($calc_evaluation_now<=1.9){
													$class_color="red";
												}elseif ($calc_evaluation_now<=2.1) {
													$class_color="orange";
												}elseif ($calc_evaluation_now<=2.7) {
													$class_color="light-green";
												}else{
													$class_color="green";
												}

											?>
											<div class="metric-box">
												<div class="pull-left">
													<i class="ion ion-ios-circle-filled icon-{{$class_color}}" style="font-size:24px;"></i>
												</div>
												<div class="right">
													<h4 class="text-{{$class_color}}" style="font-size: 24px;">
														{{number_format($calc_evaluation_now,2)}}
													</h4>
												</div>
											</div>
										</div>
									</div>
									
								</div>
							</div>
							@if(($role_user==Config::get('roles.EMPLOYEE')))
								<div class="inner-panel">
									<table class="table table-bordered" id="">
										<tr>
											<th><strong>Comments</strong></th>
										</tr>	
										@forelse ($comment_list as $comment)
											<tr>
												<td>{{ $comment->description}}</td>	
											</tr>
										@empty
										     <tr>
												<td>No comments yet</td>	
											</tr>
										@endforelse
									</table>
								</div>
							@endif
						@endif
						<!-- /row -->
						<hr/>
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
  <script type="text/javascript" src="{{ asset('assets/js/jquery.countTo.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/app.metrics.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/code/highcharts.js') }}"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  
  <script type="text/javascript">
  		<?php 
  			//Colors
	      	$col_green="#0CC935";
			$col_yellow="#f7941d";
			$col_red="#EC2323";
  		?>
  	  
	  
	  @if(($role_user!=Config::get('roles.SUPERVISOR') || $role_user!=Config::get('roles.AREA_SUPERVISOR')) && $role_user!=Config::get('roles.EMPLOYEE'))
	  
	  $('#sales-graph').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: ['Specialty'],

        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            },
            labels: {
		            enabled: false
		        },
        },
        
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        credits: {
		      enabled: false
		  },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: false,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        colors: ['#0CC935', '#29aae2', '#7a7a7a'],
        series: [
         {
            name: 'Approved',
            data: [{{$sales_data['approved']}}]
        },
        {
            name: 'Progress',
            data: [{{$sales_data['progress']}}]
        },
        {
            name: 'Draft',
            data: [{{$sales_data['draft']}}]
        } ]
    });

	$('#profitability-graph').highcharts({
	        chart: {
	            type: 'bar'
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	            categories: [
	            	@foreach ($profitability_data['manager_list'] as $key => $item)
	                '{{$item}}',
	                @endforeach

	            ],
	            labels: {
	                formatter: function () {
	                    return this.value; // clean, unformatted number for year
	                },
	            },
	            crosshair: true
	        },
	        yAxis: {
	            title: {
	                text: ''
	            },
	            labels: {
	                overflow: 'justify',
	                enabled: false
	            },
	        },
	        tooltip: {
	            valuePrefix: ' $'
	        },
	        plotOptions: {
	            column: {
	                pointPadding: 0.2,
	                borderWidth: 0
	            },
	            series: {
	                stacking: 'normal'
	            },
	        },
	        legend: {
	            enabled: false
	        },
	        credits: {
			      enabled: false
			  },
	        colors: ['#7a7a7a', '#7a7a7a', '#7a7a7a'],
	        series: [
	        	{	name: 'Actual',
		            data: [
	        	@foreach ($profitability_data['manager_list'] as $key => $item)
        		<?php 

        			$diff=($profitability_data['actual'][$key]*100)/(($profitability_data['budget'][$key]>0)?$profitability_data['budget'][$key]:1); 
        			if($diff<97){
						$color=$col_green;
					}elseif($diff<=103){
						$color=$col_yellow;
					}else{
						$color=$col_red;
					}
        		?>   
		            { y: {{number_format($profitability_data['actual'][$key],2,'.', '')}}, color: '{{$color}}' },
		    	@endforeach
		    		]
		    	},
		    	{
		            type: 'spline',
		            name: 'Target',
		            dashStyle: 'shortdot',
		            data: [
		            	@foreach ($profitability_data['manager_list'] as $key => $item)
		                {{number_format($profitability_data['budget'][$key],2,'.', '')}},
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
  

	  <?php
        if($calculation_1_lt1<97){
			$color2="#0CC935";
		}elseif ($calculation_1_lt1<=103) {
			$color2="#f7941d";
		}else{
			$color2="#EC2323";
		} ?>

	 $('#bar-used-budget-amount').highcharts({
	        chart: {
	            type: 'bar'
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	            categories: ['.','4 Week Lag','.'],
	            labels: {
	                overflow: 'justify',
	                enabled: false
	            },
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: ''
	            },
	        },
	        colors: ['{{$color2}}', '#7a7a7a' ],
	        legend: {
	            reversed: true
	        },
	        plotOptions: {
	            series: {
	                stacking: 'normal',
	                pointWidth: 80
	            }
	        },
	        legend: {
	            enabled: false
	        },
	        credits: {
			      enabled: false
			  },
	        series: [
	        {
	            name: 'Used %',
	            data: [0, {{number_format($calculation_1_lt1,2,".","")}}, 0],
	            colorByPoint: true,
	            colors: ['{{$color2}}'],
	        },
	    	{
	            type: 'spline',
	            name: 'Target',
	            dashStyle: 'shortdot',
	            colors: ['#000000'],
	            data: [100, 100, 100],
	            marker: {
	                lineWidth: 1,
	                lineColor: '#000000',
	                fillColor: '#ffffff',
	            },
	            
	        }
	        ]
	    });

	 <?php
        if(($calculation_budget*100)<97){
			$color3="#0CC935";
		}elseif(($calculation_budget*100)<=103) {
			$color3="#f7941d";
		}else{
			$color3="#EC2323";
		} ?>
	
	$('#bar-used-budget-hours').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: ['.','4 Week Lag', '.'],
            labels: {
                overflow: 'justify',
                enabled: false
            },
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        colors: ['{{$color3}}', '#7a7a7a' ],
        legend: {
            reversed: true
        },
        plotOptions: {
            series: {
                stacking: 'normal',
                pointWidth: 80
            }
        },
        legend: {
	            enabled: false
	        },
	     credits: {
		      enabled: false
		  },   
        series: [
        {
            name: 'Used %',
            data: [0, {{number_format($calculation_budget*100,2,".","")}}, 0],
            colorByPoint: true,
            colors: ['{{$color3}}'],
        },
	    	{
	            type: 'spline',
	            name: 'Target',
	            dashStyle: 'shortdot',
	            data: [100, 100, 100],
	            marker: {
	                lineWidth: 1,
	                lineColor: '#000000',
	                fillColor: '#ffffff',
	            },

	            colors: ['#ffffff'],
	        }
        ]
    });	     


   
      <?php 
      	$calc_val=$total_supplies['budget_monthly'];
        if($calc_val==0)
            $calc_val=1;
        $calculation=($total_supplies['expense']*100)/($calc_val);
        
        if($calculation<97){
			$color4="#0CC935";
		}elseif ($calculation<=103) {
			$color4="#f7941d";
		}else{
			$color4="#EC2323";
		} ?>

	$('#bar-used-supplies').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: ['.', '4 Week Lag', '.'],
            labels: {
                overflow: 'justify',
                enabled: false
            },
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        legend: {
            reversed: true
        },
        plotOptions: {
            series: {
                stacking: 'normal',
                pointWidth: 80
            }
        },
        legend: {
	            enabled: false
	        },
	    credits: {
		      enabled: false
		  },
        series: [
        {
            name: 'Used %',
            data: [0, {{number_format($calculation,2,".","")}}, 0],
            colorByPoint: true,
            colors: ['{{$color4}}'],
        },
    	{
	            type: 'spline',
	            name: 'Target',
	            dashStyle: 'shortdot',
	            data: [100, 100, 100],
	            marker: {
	                lineWidth: 1,
	                lineColor: '#000000',
	                fillColor: '#ffffff',
	            },

	            colors: ['#ffffff'],
	        }
        ]
    });	     	

      
      @endif

      @if(($role_user==Config::get('roles.AREA_MANAGER') && $job_portfolio_id==0) || ($role_user==Config::get('roles.DIR_POS'))  )
      <?php 
      	
      	if($role_user==Config::get('roles.DIR_POS') && $manager_id==0)
      		$count_employee=$count_evaluation;

      	if(is_object($total_evaluation_user)) $total_evaluation_user=0;
      	if(is_object($count_employee)) $count_employee=0;

      	$calculation=number_format(($total_evaluation_user*100)/(($count_employee==0)?1:$count_employee),2, ".",""); 
      	//Validation
      	if($calculation>100) $calculation=100;
      	$color="";
      	if($calculation<70){
			$color=$col_red;
		}elseif($calculation<=80){
			$color=$col_yellow;
		}else{
			$color=$col_green;
		}

      ?>


      $('#evaluation-graph').highcharts({
	        chart: {
	            type: 'bar'
	        },
	        title: {
	            text: 'Evaluation Progress'
	        },
	        xAxis: {
	            categories: ['+', '+', '+'],
	            labels: {
		            enabled: false
		        },
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: ''
	            },

	        },
	        legend: {
	            enabled: false
	        },
	        plotOptions: {
	            series: {
	                stacking: 'normal',
	                pointWidth: 80
	            },

	        },
	        credits: {
			      enabled: false
			  },
	        series: [
	        {
	            name: '%',
	            data: [0, {{$calculation}}, 0],
	            colorByPoint: true,
	            colors: ['{{$color}}'],

	        },
	        {
	            type: 'spline',
	            name: 'Target',
	            dashStyle: 'shortdot',
	            data: [100, 100, 100],
	            marker: {
	                lineWidth: 1,
	                lineColor: '#000000',
	                fillColor: '#ffffff',
	            },

	            colors: ['#ffffff'],
	        }

	        ]
	    });
    @endif


	$( document ).ready(function() {
		var active_menu=0;
		$('#collapseTwo').hide();
		$('#collapseEval').hide();
		$('#menu_portfolio').show();
		$('#menu_site').show();
		$('#btn_menu_portfolio').unbind();
		$('#btn_menu_site').unbind();
		$("[data-toggle=popover]").popover();

		$('#btn_menu_portfolio').on('click', function (e) {   
			$('.piluku-preloader').removeClass("hidden");
			location.href="/dashboard";	
		});		

		$('#btn_menu_site').on('click', function (e) {   
			$('.piluku-preloader').removeClass("hidden");
			location.href="/dashboard";
		});		

		menu_display();

		$( window ).resize(function() {
			menu_display();		  
		});

		function menu_display(){
			if($( window ).width()<=1183 && $( window ).width()>682 && active_menu==0){
			  	console.log($( window ).width());
			  	$('#menu_portfolio').hide();
				$('#menu_site').hide();
				$( "#btn_menu_site" ).hover(function() {
					    $('#menu_site').show();
					  }, function() {
					    $('#menu_site').hide();
					  }	
				  
				);

				$( "#menu_site" ).hover(function() {
					    $('#menu_site').show();
					  }, function() {
					    $('#menu_site').hide();
					  }	
				  
				);

				$( "#btn_menu_portfolio" ).hover(function() {
					    $('#menu_portfolio').show();
					  }, function() {
					    $('#menu_portfolio').hide();
					  }
				  
				);

				$( "#menu_portfolio" ).hover(function() {
					    $('#menu_portfolio').show();
					  }, function() {
					    $('#menu_portfolio').hide();
					  }
				);
				active_menu=1;
			  }else{
			  	
			  }
		}
	});

  </script>
@endsection
