@extends('layouts.default')
@section('styles')
<link href="{{ asset('assets/css/animated-masonry-gallery.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('assets/css/rotated-gallery.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('assets/css/jtree.css') }}" rel="stylesheet" type="text/css" >
@endsection
@section('content')	
	<div class="wrapper ">
	@include('includes.sidebar')
	<div class="content" id="content">
	<div class="overlay"></div>				
	@include('includes.topbar')
		<!-- main content -->
		<div class="main-content">
			<div class="row">
				<div class="col-md-12">
					<!-- panel -->
					<div class="panel panel-piluku panel-users">
						@if (Session::has('status'))
	                    <div class="alert bg-success text-white" role="alert">
	                    	<strong>{{ Session::get('status') }}</strong>
	                    </div>
	                    @endif
	                    <div class="panel-body">
							<ol class="breadcrumb">
								<li><a href="/data_dashboard">Data</a></li>
								<li class="active">Billable Hours</li>
							</ol>		
						</div>
						<div class="panel-heading">
							<h3 class="panel-title">
								Billable Hours Records
								<span class="panel-options">
									<a href="#" class="panel-minimize">
										<i class="icon ti-angle-up"></i> 
									</a>
									
								</span>
							</h3>
						</div>
						<div class="panel-body nopadding">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>Employee Number</th>
											<th class="">Job Number</th>
											<th class="">Work Date</th>
											<th class="">Hours</th>
											<th class="">Lunch</th>
											<th class="">Overtime</th>
											<th class="">Pay Rate</th>
											<th class="">Square Foots</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($billable_hours as $item)
											<tr class="table-row">
												<td>{{ $item->employee_number }}</td>
												<td>{{ $item->job_number }}</td>
												<td>{{ $item->work_date }}</td>
												<td>{{ $item->regular_hours }}</td>
												<td>{{ $item->lunch_hours }}</td>
												<td>{{ $item->overtime_hours }}</td>
												<td>{{ $item->pay_rate }}</td>
												<td>{{ $item->square_foots }}</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- /panel -->
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					{!! $billable_hours->render() !!}
				</div>
			</div>
		</div>
		<!-- /main content -->	
	</div>
</div>
@endsection

@section('scripts')
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
@endsection
