@extends('layouts.default')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}">
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
				<div class="row grid">
					<div class="col-sm-12">
						<div class="panel panel-piluku">
							<div class="panel-body">
								{!! csrf_field() !!}
								<h3>
									<span>
										<i class="icon ti-bar-chart"></i> 
										Triggers
									</span>

									<span style="float: right;">
										<a href="triggers/create" class="btn btn-primary">Create New</a>
									</span>
								</h3>
								<hr>

								<div class="row">
									<div class="col-sm-12">
										<table class="table table-hover">
											<thead>
												<tr>
													<th>RecipientsByRoles</th>
													<th>CustomRecipients</th>
													<th>OnAction</th>
													<th>ExecutionTime</th>
													<th>DataToSend</th>
													<th>CustomMessage</th>
													<th>Jobs</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
												@foreach($data as $row)
													<tr>
														<td>{{ $row->recipients_by_roles }}</td>
														<td>{{ $row->custom_recipients }}</td>
														<td>{{ $row->on_action }}</td>
														<td>{{ $row->execution_time }}</td>
														<td>{{ $row->data_to_send }}</td>
														<td>{{ $row->custom_message }}</td>
														<td>{{ $row->jobs }}</td>
														<td>
															<input type="button" class="btn btn-danger" value="Delete">
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/wow.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/jquery.loadmask.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/jquery.accordion.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>

	<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/daterangepicker/moment.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/daterangepicker/daterangepicker.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/bootstrap-datepicker.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/code/highcharts.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/code/modules/exporting.js') }}"></script>

  <script type="text/javascript" src="{{ asset('assets/js/app.triggers.js') }}"></script>

	<script type="text/javascript">
		$(window).load(function () {
			$('.piluku-preloader').addClass('hidden');
		});
	</script>
@endsection