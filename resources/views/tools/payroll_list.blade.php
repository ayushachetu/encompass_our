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
								<h3><i class="icon  ion-ios-keypad-outline"></i> Payroll</h3>	
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 text-right">
								<a href="/payroll-request" class="btn btn-primary" id="btn-new-request">New Request</a>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<th>ID</th>
										<th>Name</th>
										<th class="">Email</th>
										<th class="">Date Start</th>
										<th class="">Date End</th>
										<th class="">Date Created</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@forelse  ($list as $item)
										<tr class="table-row">
											<td>{{ $item->id }}</td>
											<td>{{ $item->name }}</td>
											<td>{{ $item->email }}</td>
											<td>{{ date( 'm/d/Y', strtotime( $item->date_ini) )  }}</td>
											<td>{{ date( 'm/d/Y', strtotime( $item->date_end) )  }}</td>
											<td>{{ date( 'm/d/Y H:i', strtotime( $item->created_at) )  }}</td>
											<td><a  class="btn btn-success" href="/payroll-get-file-csv/{{ $item->id }}">></a></td>
										</tr>
									@empty
									     <tr class="table-row">
											<td colspan="6"><h4 class="text-center">No records found.</h4></td>
										</tr>
									@endforelse
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
    $(document).ready(function(){
    	$('#btn-new-request').click(function() {
    		$('.piluku-preloader').removeClass('hidden');
    	});	
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
