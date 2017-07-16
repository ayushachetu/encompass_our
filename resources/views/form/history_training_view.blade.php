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
								<h3><i class="icon  ion-ios-keypad-outline"></i> Training</h3>	
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
										<td class="text-right" style="width: 250px;">Training Date:</td>
										<td><strong>{{ date( 'm/d/Y', strtotime( $item->date_training) )  }}</strong></td>
									</tr>
									<tr class="table-row">
										<td class="text-right">Name:</td>
										<td>{{ $item->name}}</td>
									</tr>
									<tr class="table-row">
										<td class="text-right">Email:</td>
										<td>{{ $item->email}}</td>
									</tr>
									<tr>
										<td class="text-right"><strong>Comment</strong></td>
										<td >{{$item->comment}}</td>
									</tr>
									
								</tbody>
							</table>
							<h4>Employee List</h4>
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<th>Employee Name</th>
										<th>Employee Number</th>
										<th>Account Number</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($list as $item)
										<tr class="table-row">
											<td>{{ $item->employee_name}}</td>
											<td>{{ $item->employee_number}}</td>
											<td>{{ $item->account_number}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>

						</div>
						<div class="text-left">
							<a href="/history-training">< Return to List</a>
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
