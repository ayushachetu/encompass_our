@extends('layouts.default')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/matrix-options.css') }}">
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
								<div class="row">
									<div class="col-sm-12">
										<h3><i class="icon ti-layout-grid2"></i> Matrix Options Management</h3>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-offset-9 col-sm-3 col-xs-offset-1 col-xs-10" style="text-align: right;">
										<button class="btn btn-lg btn-block btn-evaluation" id="new-opt-btn"><span>New Option</span></button>
									</div>
								</div>
								<hr>
								<div id="new-opt-div">
									<h3>Enter New Option Details <i class="icon ti-close" id="new-opt-close"></i></h3>
									<div class="row">
										<div class="col-sm-6">
											<label for="new-en-option">English</label>
											<input class="form-control" id="new-en-option" type="text" placeholder="* Option in English">
										</div>
										<div class="col-sm-6">
											<label for="new-es-option">Spanish</label>
											<input class="form-control" id="new-es-option" type="text" placeholder="Option in Spanish">
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 text-right">
											<button type="button" class="btn btn-success" id="add-opt-btn">Add Option</button>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
											<table id="opt-table" class="table table-striped table-bordered">
												<thead>
													<th>Id</th>
													<th>English Options</th>
													<th>Spanish Options</th>
												</thead>
												<tbody>
													@foreach ($matrix_options as $option)
														<tr>
															<td>{{ $option->id }}</td>
															<td>{{ $option->en_option }}</td>
															<td>{{ $option->es_option }}</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-offset-9 col-sm-3 col-xs-offset-1 col-xs-10 text-right">
										<button class="btn btn-lg btn-block btn-warning" id="show-deleted-options"><span>Deleted Option</span></button>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 p-t-10">
										<div class="table-responsive">
											<table id="deleted-opt-table" class="table table-striped table-bordered">
												<thead>
													<th>English Options</th>
													<th>Spanish Options</th>
													<th style="width: 1%;">Restore</th>
												</thead>
												<tbody>
													@forelse ($deleted_matrix_options as $option)
														<tr data-id={{ $option->id }}>
															<td>{{ $option->en_option }}</td>
															<td>{{ $option->es_option }}</td>
															<td><i class="fa fa-reply" aria-hidden="true"></i></td>
														</tr>
													@empty
														<tr id="no-records">
															<td colspan="3">No Records Found</td>
														</tr>
													@endforelse
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
	</div>
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
	<script type="text/javascript" src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/tabledit/jquery.tabledit.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/app.matrix-options.js') }}"></script>
@endsection