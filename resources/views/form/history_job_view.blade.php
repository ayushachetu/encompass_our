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
		<div class="main-content">

			<!-- Second Row -->
		<div class="row grid">
			<!-- /col-md-9 -->
			<div class="col-md-12">
				<!-- panel -->
				<div class="panel panel-piluku">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<h3><i class="icon  ion-ios-keypad-outline"></i> Job Request</h3>	
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-hover table-bordered">
								<tbody>
									<tr class="table-row">
										<td class="text-right" style="width: 250px;">Date Created:</td>
										<td>{{ date( 'm/d/Y', strtotime( $item->created_at) )  }}</td>
									</tr>
									<tr class="table-row">
										<td class="text-right">Email:</td>
										<td>{{ $item->email}}</td>
									</tr>
									<tr class="table-row">
										<td class="text-right">Job Number:</td>
										<td>{{ $item->account_number}}</td>
									</tr>
									<tr class="table-row">
										<td class="text-right">Job Name:</td>
										<td>{{ $item->account_name}}</td>
									</tr>
									<tr class="table-row">
										<td class="text-right">Customer Name:</td>
										<td>{{ $item->customer_name}}</td>
									</tr>
									<tr class="table-row">
										<td class="text-right">Customer Email:</td>
										<td>{{ $item->customer_email}}</td>
									</tr>
									<tr class="table-row">
										<td class="text-right">Customer Phone:</td>
										<td>{{ $item->customer_cellphone }}</td>
									</tr>

									<tr class="table-row">
										<td class="text-right">Scope of Work:</td>
										<td valign="top">{{ $item->scope_work  }}</td>
									</tr>

									<tr class="table-row">
										<td class="text-right">Job Location:</td>
										<td>{{ $item->job_location  }}</td>
									</tr>

									<tr class="table-row">
										<td class="text-right">Target Start date and time:</td>
										<td>{{ $item->target_start }}</td>
									</tr>

									<tr class="table-row">
										<td class="text-right">Total labor hours needed:</td>
										<td>{{ $item->labor_hours}}</td>
									</tr>

									<tr class="table-row">
										<td class="text-right">Employee pay rate:</td>
										<td>{{ $item->employee_pay_rate}}</td>
									</tr>

									<tr class="table-row">
										<td class="text-right">Material Cost:</td>
										<td>{{ $item->material_cost }}</td>
									</tr>

									<tr class="table-row">
										<td class="text-right">Sub-contractor to be used:</td>
										<td>{{ $item->sub_contractor  }}</td>
									</tr>
									
								</tbody>
							</table>
						</div>
						<div class="text-left">
							<a href="/history-job">< Return to List</a>
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

  <script type="text/javascript" src="{{ asset('assets/js/jquery.countTo.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
  
@endsection
