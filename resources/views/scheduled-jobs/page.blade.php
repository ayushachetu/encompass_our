@extends('layouts.default')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}">
<style type="text/css">
	@media (min-width: 768px) {
		table thead th {
			min-width: 100px;
		}
	}
</style>
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
										Scheduled Jobs
									</span>
								</h3>
								<hr>

								<div class="row">
									<div class="col-sm-12">
										<table class="table table-hover">
											<thead>
												<tr>
													<th>Date Range</th>
													<th>Filter 1</th>
													<th>Filter 2</th>
													<th>Recipients By Roles</th>
													<th>Custom Recipients</th>
													<th>Frequency</th>
													<th>Send On</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($scheduled_jobs_data as $data)
													<tr>
														<td>Last {{ $data->report_range }} Days</td>
														<td>{{ $data->primary_filter }}</td>
														<td>{{ $data->secondary_filter }}</td>
														<td>{{ $data->recipients_by_roles }}</td>
														<td>{{ $data->custom_recipients }}</td>
														<td>{{ $data->frequency }}</td>
														<td>{{ $data->send_on }}</td>
														<td>
															<!-- <button type="button" class="btn btn-warning edit">Edit</button> -->
															<button type="button" class="btn btn-danger delete" data-id="{{ $data->id }}">Delete</button>
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

	<script type="text/javascript" src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>

	<script type="text/javascript">
		$(window).load(function () {
			$('.piluku-preloader').addClass('hidden');
		});

		$('.delete').click(function () {
			var ref = $(this);
			swal({
				title: "Are you sure?",
				text: "Really delete scheduled task!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, Delete it!",
				cancelButtonText: "No, Let it be!",
				closeOnConfirm: true,
				closeOnCancel: true
			}, function(isConfirm) {
				if (isConfirm) {
					var data = {};
					data._token = $('input[name="_token"]').val();
					data.id = ref.attr('data-id');

					$.ajax({
						url: '/scheduled-jobs/delete',
						type: 'post',
						data: data,
						success: function (response) {
							if (response.message !== undefined) {
								swal("Deleted!", response.message, "success");
								ref.parents('tr').remove();
							}
						},
						error: function (response) {
							if (response.responseJSON.message !== undefined)
								swal("Oops!", response.responseJSON.message, "error");
						}
					});
				}
			});
		});
	</script>
@endsection