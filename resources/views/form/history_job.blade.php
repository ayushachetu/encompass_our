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
								<a href="/history-job-download" class="btn btn-green pull-right"> Download</a>
							</div>
						</div>
						<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="">Email</th>
											<th class="">Job Number</th>
											<th class="">Job Name</th>
											<th class="">Customer Name</th>
											<th class="">Customer Email</th>
											<th class="">Created at</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										@foreach ($list as $item)
											<tr class="table-row">
												<td>{{ $item->email }}</td>
												<td>{{ $item->account_number}}</td>
												<td>{{ $item->account_name}}</td>
												<td>{{ $item->customer_name}}</td>
												<td>{{ $item->customer_email}}</td>
												<td>{{ date( 'm/d/Y', strtotime( $item->created_at) )  }}</td>
												<td class="text-right">
													<a href="/history-job-view/{{ $item->id }}" class="btn btn-green"><i class="ti-angle-right"></i></a>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<div class="row">
							<div class="col-md-12">
								{!! $list->render() !!}
							</div>
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
