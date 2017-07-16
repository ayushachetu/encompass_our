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
			<div class="manage_buttons">
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-9">
						<div class="buttons-list">
							<div class="pull-right-btn">
								<a href="/user/create" class="btn btn-primary">Add New User</a>
							</div>
						</div>
					</div>
				</div>
			</div>

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
								Users
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
											<th class="text-center">Employee No.</th>
											<th class="">First Name</th>
											<th class="">Last Name</th>
											<th class="">E-Mail</th>
											<th class="">Type</th>
											<th>&nbsp;</th>
											<th>&nbsp;</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($users as $user)
											<tr class="table-row">
												<td>{{ $user->employee_number }}</td>
												<td>{{ $user->first_name }}</td>
												<td>{{ $user->last_name }}</td>
												<td><a href="#">{{ $user->email }}</a></td>
												<td>{{ $roles[$user->role] }}</td>
												<td class="text-right">
													<a href="/user/edit/{{ $user->id }}" class="btn btn-green"><i class="ion ion-edit"></i></a>
												</td>
												<td>
													<a href="/user/delete/{{ $user->id }}" class="btn btn-red"><i class="ion ion-ios-trash-outline"></i></a>
												</td>
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
					{!! $users->render() !!}
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
