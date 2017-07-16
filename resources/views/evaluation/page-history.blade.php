@extends('layouts.default')
@section('styles')
<link href="{{ asset('assets/css/animated-masonry-gallery.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('assets/css/rotated-gallery.css') }}" rel="stylesheet" type="text/css" >
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
					</div>-->
					<div class="panel-body">
						{!! csrf_field() !!}
						<div class="row">
							<div class="col-md-12">
								<ul style="list-style:none; padding:0;">
									<li class="dropdown piluku-dropdown">
										<a data-toggle="dropdown" class="dropdown-toggle btn btn-green" href="#" aria-expanded="true"><span style="color:#fff;">Submited List</span> <b class="caret"></b></a>
										<ul class="dropdown-menu dropdown-piluku-menu neat_drop">
											<li><a href="/evaluations">Evaluation List</a></li>
										</ul>
									</li>
									<li></li>
								</ul>
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
						<br/>
						<table class="table table-bordered" id="table-history">
							<tr>
								<th>Employee #</th>
								<th>Name</th>
								<th>Date</th>
								<th class="text-center">Mission/Vision</th>
								<th class="text-center">Value 1 </th>
								<th class="text-center">Value 2 </th>
								<th class="text-center">Value 3 </th>
								<th class="text-center">Skills</th>
								<th class="text-center">Comments & Feedback</th>
							</tr>	
							@foreach ($users as $user)
								<tr>
									<td>{{ $user->employee_number}}</td>	
									<td>{{ $user->first_name }} {{ $user->last_name }}</td>
									<td>{{ date('M j Y g:i A', strtotime($user->created_at))}}</td>
									<td class="text-center"><span class="icon-eval icon-eval-{{ $user->parameter1==1 ? 'bad' : '' }}{{ $user->parameter1==2 ? 'good' : '' }}{{ $user->parameter1==3 ? 'excelent' : '' }} option-{{$user->parameter1}}-active p1 param"></span></td>
									<td class="text-center"><span class="icon-eval icon-eval-{{ $user->parameter2==1 ? 'bad' : '' }}{{ $user->parameter2==2 ? 'good' : '' }}{{ $user->parameter2==3 ? 'excelent' : '' }} option-{{$user->parameter2}}-active p2 param"></span></td>
									<td class="text-center"><span class="icon-eval icon-eval-{{ $user->parameter3==1 ? 'bad' : '' }}{{ $user->parameter3==2 ? 'good' : '' }}{{ $user->parameter3==3 ? 'excelent' : '' }} option-{{$user->parameter3}}-active p3 param"></span></td>
									<td class="text-center"><span class="icon-eval icon-eval-{{ $user->parameter4==1 ? 'bad' : '' }}{{ $user->parameter4==2 ? 'good' : '' }}{{ $user->parameter4==3 ? 'excelent' : '' }} option-{{$user->parameter4}}-active p4 param"></span></td>
									<td class="text-center"><span class="icon-eval icon-eval-{{ $user->parameter5==1 ? 'bad' : '' }}{{ $user->parameter5==2 ? 'good' : '' }}{{ $user->parameter5==3 ? 'excelent' : '' }} option-{{$user->parameter5}}-active p5 param"></span></td>
									<td class="text-center"><span class="ion-chatbox-working" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{{$user->description}}"></span></td>
								</tr>
							@endforeach
						</table>
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

  <script type="text/javascript" src="{{ asset('assets/js/jquery.countTo.js') }}"></script>
  <script type="text/javascript">
  	$('document').ready(function(){
    	$("[data-toggle=popover]").popover();
	});
  </script>	

@endsection
