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
						<div class="panel-heading">
							<h3 class="panel-title">
								Job Records
								<span class="panel-options">
									<a href="#" class="panel-refresh">
										<i class="icon ti-reload"></i> 
									</a>
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
											<th>Job Number</th>
											<th>Job Description</th>
											<th>Type</th>
											<th class="">Region</th>
											<th class="">Country</th>
											<th class="">Division</th>
											<th class="">Manager</th>
											<th class="">Service</th>
											<th class="">Mayor Account</th>
											<th class="">Is Parent</th>
											<th class="">Parent Job</th>
											<th class="">Square Feet</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($job as $item)
											<tr class="table-row">
												<td>{{ $item->job_number }}</td>
												<td>{{ $item->job_description }}</td>
												<td>{{ $item->type_number }}</td>
												<td>{{ $item->region }}</td>
												<td>{{ $item->country }}</td>
												<td>{{ $item->division }}</td>
												<td>{{ $item->manager }}</td>
												<td>{{ $item->service }}</td>
												<td>{{ $item->mayor_account }}</td>
												<td>{{ $item->is_parent }}</td>
												<td>{{ $item->parent_job }}</td>
												<td>{{ $item->square_feet }}</td>
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
					{!! $job->render() !!}
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
