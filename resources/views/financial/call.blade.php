@extends('layouts.default')
@section('styles')
<link href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}" rel="stylesheet" type="text/css" >
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
						{!! csrf_field() !!}
						<div class="row">
							<div class="col-md-12">
								<h3><i class="icon  ion-ios-keypad-outline"></i> Financial [Coupa]</h3>	
							</div>
						</div>
						<?php 
						//echo $contents;
						//echo $xml->{'invoice-number'}."-here";
						//var_dump($xml);

						/*foreach ($xml as $item) {
							var_dump($item->{'invoice-lines'})."<br/>";
						}*/
						?>
						<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="">Vendor Number</th>
											<th class="">Invoice Number</th>
											<th class="">PO Number</th>
											<th class="">Invoice Date</th>
											<th class="">Invoice Amount</th>
											<th class="">GL Number</th>
											<th class="">Job Number</th>
											<th class="">Distribution Amount</th>
											<th class="">Work Ticket Number</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($financials as $financial)
											<tr class="table-row">
												<td>{{ $financial->vendor_number }}</td>
												<td>{{ $financial->invoice_number }}</td>
												<td>{{ $financial->po_number }}</td>
												<td>{{ $financial->invoice_date }}</td>
												<td>{{ $financial->invoice_amount }}</td>
												<td>{{ $financial->account_number }}</td>
												<td>{{ $financial->job_number }}</td>
												<td>{{ $financial->distribution_amount }}</td>
												<td>{{ $financial->work_ticket_number }}</td>
											</tr>
										@endforeach
									</tbody>
								</table>
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
  <script type="text/javascript" src="{{ asset('assets/js/sparkline.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/bic_calendar.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/widgets.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>

  <script type="text/javascript" src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>


  <script type="text/javascript" src="{{ asset('assets/js/jquery.countTo.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
  
@endsection
