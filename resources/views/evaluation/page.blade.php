@extends('layouts.default')
@section('styles')

<link href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('assets/css/jtree.css') }}" rel="stylesheet" type="text/css" >

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
		<div class="main-content">

			<!-- Second Row -->
		<div class="row grid">
			<!-- /col-md-9 -->
			<div class="col-md-12">
				<!-- panel -->
				<div class="panel panel-piluku">
					<!--
					<div class="highlight-header">
						<span>Evaluation: {{ $dateEvaluate }}</span>
					</div>
					-->
					<div class="panel-body">
						{!! csrf_field() !!}
						<div class="row">
							<div class="col-md-12">
								
								<div class="row">
									<div class="col-md-8">
										<ul style="list-style:none; padding:0;">
											<li class="dropdown piluku-dropdown">
												<a data-toggle="dropdown" class="dropdown-toggle btn btn-green" href="#" aria-expanded="true"><span style="color:#fff;">Evaluation List</span> <b class="caret"></b></a>
												<ul class="dropdown-menu dropdown-piluku-menu neat_drop">
													<li><a href="/evaluations/history">Submited List</a></li>
												</ul>
											</li>
											<li></li>
										</ul>
									</div>
									<div class="col-md-4">
										<!-- xselect form with input group   -->
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon bg">
													<i class="ion-android-search"></i>
												</span>
												<select class="name_search form-control">
													<option value="0"> All</option>
													@foreach ($users as $user)
													<option value="{{ $user->id}}"> {{ $user->first_name }} {{ $user->last_name }}</option>
													@endforeach
													@foreach ($users_eval as $user)
													<option value="{{ $user->id}}"> {{ $user->first_name }} {{ $user->last_name }}</option>
													@endforeach
												</select>
											</div>
											<!-- /input-group -->
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div>
									<span class="icon-eval icon-eval-bad"></span> <div class="eval-exp-box"><strong>Doesn’t Meet Expectation</strong> <br/> <em>No Cumple la Expectativa</em></div>
								</div>
							</div>
							<div class="col-md-4">
								<div>
									<span class="icon-eval icon-eval-good"></span> <div class="eval-exp-box"><strong>Meets Expectation</strong> <br/><em>Cumple la Expectativa</em></div>
								</div>
							</div>
							<div class="col-md-4">
								<div>
									<span class="icon-eval icon-eval-excelent"></span > <div class="eval-exp-box"><strong>Exceeds Expectation</strong>  <br/><em>Sobrepasa la Expectativa</em></div>
								</div>
							</div>
						</div>
						<hr/>
						<div id="wrapper-evaluation">
							@foreach ($users as $user)
								<div class="row evaluation-item evaluation-{{ $user->id}}">
									<div class="col-md-12">
										<div class="evaluation-heading">
											<span class="name-employee">{{ $user->first_name }} {{ $user->last_name }}</span>  EMPLOYEE # {{ $user->employee_number}}
											<div class="evaluation-last-date pull-right" id="date-review-{{ $user->id}}">
												Last Review: <span class="date-review"><em>[Never]</em></span>
											</div>
										</div>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-12">
										
										<table class="table table-evaluation">
											<tr>
												<td><h3>Mission/Vision</h3></td>
												<td><span class="icon-eval icon-eval-bad option-1 p1 param" data-param="1" data-type="1" data-status="0" data-user="{{ $user->id}}" id="p1-opt1-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-good option-2 p1 param" data-param="1" data-type="2" data-status="0" data-user="{{ $user->id}}" id="p1-opt2-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-excelent option-3 p1 param" data-param="1" data-type="3" data-status="0" data-user="{{ $user->id}}" id="p1-opt3-{{ $user->id}}"></span></td>
											</tr>
											<tr>
												<td><h3>Value 1 - Trust & Respect</h3></td>
												<td><span class="icon-eval icon-eval-bad option-1 p2 param" data-param="2" data-type="1" data-status="0" data-user="{{ $user->id}}" id="p2-opt1-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-good option-2 p2 param" data-param="2" data-type="2" data-status="0" data-user="{{ $user->id}}" id="p2-opt2-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-excelent option-3 p2 param" data-param="2" data-type="3" data-status="0" data-user="{{ $user->id}}" id="p2-opt3-{{ $user->id}}"></span></td>
											</tr>
											<tr>
												<td><h3>Value 2 - Improve Lives</h3></td>
												<td><span class="icon-eval icon-eval-bad option-1 p3 param" data-param="3" data-type="1" data-status="0" data-user="{{ $user->id}}" id="p3-opt1-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-good option-2 p3 param" data-param="3" data-type="2" data-status="0" data-user="{{ $user->id}}" id="p3-opt2-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-excelent option-3 p3 param" data-param="3" data-type="3" data-status="0" data-user="{{ $user->id}}" id="p3-opt3-{{ $user->id}}"></span></td>
											</tr>
											<tr>
												<td><h3>Value 3 – Continuous Progress</h3></td>
												<td><span class="icon-eval icon-eval-bad option-1 p4 param" data-param="4" data-type="1" data-status="0" data-user="{{ $user->id}}" id="p4-opt1-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-good option-2 p4 param" data-param="4" data-type="2" data-status="0" data-user="{{ $user->id}}" id="p4-opt2-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-excelent option-3 p4 param" data-param="4" data-type="3" data-status="0" data-user="{{ $user->id}}" id="p4-opt3-{{ $user->id}}"></span></td>
											</tr>
											<tr>
												<td><h3>How are their work skills?</h3></td>
												<td><span class="icon-eval icon-eval-bad option-1 p5 param" data-param="5" data-type="1" data-status="0" data-user="{{ $user->id}}" id="p5-opt1-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-good option-2 p5 param" data-param="5" data-type="2" data-status="0" data-user="{{ $user->id}}" id="p5-opt2-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-excelent option-3 p5 param" data-param="5" data-type="3" data-status="0" data-user="{{ $user->id}}" id="p5-opt3-{{ $user->id}}"></span></td>
											</tr>
										</table>								

									</div>
									<div class="col-md-4 col-sm-4 col-xs-12">
										<h3>Comments & Feedback</h3>
										<br/>
										<div>
											<textarea placeholder="" rows="16" cols="30" class="form-control text-area description" name="description-{{ $user->id}}" id="description-{{ $user->id}}"></textarea>
										</div>
										<br/>
										<div>
											<div id="message-{{ $user->id}}"></div>
											<input type="hidden" name="param1" class="param1 param-val" value="0">
											<input type="hidden" name="param2" class="param2 param-val" value="0">
											<input type="hidden" name="param3" class="param3 param-val" value="0">
											<input type="hidden" name="param4" class="param4 param-val" value="0">
											<input type="hidden" name="param5" class="param5 param-val" value="0">
											<button id="btn-evaluation-{{ $user->id}}" data="{{ $user->id}}" class="btn btn-lg btn-block btn-evaluation">Submit</button>
										</div>
										<br/>
										
									</div>
									<hr/>
								</div>
							@endforeach

							@foreach ($users_eval as $user)
								<div class="row evaluation-item evaluation-{{ $user->id}}">
									<div class="col-md-12">
										<div class="evaluation-heading">
											<span class="name-employee">{{ $user->first_name }} {{ $user->last_name }}</span>  EMPLOYEE # {{ $user->employee_number}}
											<div class="evaluation-last-date pull-right" id="date-review-{{ $user->id}}">
												Last Review: <span class="date-review">{{ date('M j Y g:i A', strtotime($user->e_created_at))}}</span>
											</div>
										</div>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-12">
										
										<table class="table table-evaluation">
											<tr>
												<td><h3>Mission/Vision</h3></td>
												<td><span class="icon-eval icon-eval-bad option-1 p1 param" data-param="1" data-type="1" data-status="0" data-user="{{ $user->id}}" id="p1-opt1-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-good option-2 p1 param" data-param="1" data-type="2" data-status="0" data-user="{{ $user->id}}" id="p1-opt2-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-excelent option-3 p1 param" data-param="1" data-type="3" data-status="0" data-user="{{ $user->id}}" id="p1-opt3-{{ $user->id}}"></span></td>
											</tr>
											<tr>
												<td><h3>Value 1 - Trust & Respect</h3></td>
												<td><span class="icon-eval icon-eval-bad option-1 p2 param" data-param="2" data-type="1" data-status="0" data-user="{{ $user->id}}" id="p2-opt1-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-good option-2 p2 param" data-param="2" data-type="2" data-status="0" data-user="{{ $user->id}}" id="p2-opt2-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-excelent option-3 p2 param" data-param="2" data-type="3" data-status="0" data-user="{{ $user->id}}" id="p2-opt3-{{ $user->id}}"></span></td>
											</tr>
											<tr>
												<td><h3>Value 2 - Improve Lives</h3></td>
												<td><span class="icon-eval icon-eval-bad option-1 p3 param" data-param="3" data-type="1" data-status="0" data-user="{{ $user->id}}" id="p3-opt1-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-good option-2 p3 param" data-param="3" data-type="2" data-status="0" data-user="{{ $user->id}}" id="p3-opt2-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-excelent option-3 p3 param" data-param="3" data-type="3" data-status="0" data-user="{{ $user->id}}" id="p3-opt3-{{ $user->id}}"></span></td>
											</tr>
											<tr>
												<td><h3>Value 3 – Continuous Progress</h3></td>
												<td><span class="icon-eval icon-eval-bad option-1 p4 param" data-param="4" data-type="1" data-status="0" data-user="{{ $user->id}}" id="p4-opt1-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-good option-2 p4 param" data-param="4" data-type="2" data-status="0" data-user="{{ $user->id}}" id="p4-opt2-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-excelent option-3 p4 param" data-param="4" data-type="3" data-status="0" data-user="{{ $user->id}}" id="p4-opt3-{{ $user->id}}"></span></td>
											</tr>
											<tr>
												<td><h3>How are their work skills?</h3></td>
												<td><span class="icon-eval icon-eval-bad option-1 p5 param" data-param="5" data-type="1" data-status="0" data-user="{{ $user->id}}" id="p5-opt1-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-good option-2 p5 param" data-param="5" data-type="2" data-status="0" data-user="{{ $user->id}}" id="p5-opt2-{{ $user->id}}"></span></td>
												<td><span class="icon-eval icon-eval-excelent option-3 p5 param" data-param="5" data-type="3" data-status="0" data-user="{{ $user->id}}" id="p5-opt3-{{ $user->id}}"></span></td>
											</tr>
										</table>								

									</div>
									<div class="col-md-4 col-sm-4 col-xs-12">
										<h3>Comments & Feedback</h3>
										<br/>
										<div>
											<textarea placeholder="" rows="16" cols="30" class="form-control text-area description" name="description-{{ $user->id}}" id="description-{{ $user->id}}"></textarea>
										</div>
										<br/>
										<div>
											<div id="message-{{ $user->id}}"></div>	
											<input type="hidden" name="param1" class="param1" value="0">
											<input type="hidden" name="param2" class="param2" value="0">
											<input type="hidden" name="param3" class="param3" value="0">
											<input type="hidden" name="param4" class="param4" value="0">
											<input type="hidden" name="param5" class="param5" value="0">
											<button id="btn-evaluation-{{ $user->id}}" data="{{ $user->id}}" class="btn btn-lg btn-block btn-evaluation">Submit</button>
										</div>
										<br/>
										
									</div>
									<hr/>
								</div>
							@endforeach
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
  <script type="text/javascript" src="{{ asset('assets/js/sparkline.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/bic_calendar.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/widgets.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>

  <script type="text/javascript" src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>


  <script type="text/javascript" src="{{ asset('assets/js/jquery.countTo.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>


  <script type="text/javascript" src="{{ asset('assets/js/app.evaluation.js') }}"></script>
  

@endsection
