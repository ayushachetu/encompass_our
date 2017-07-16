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
						@if (Session::has('status'))
	                    <div class="alert bg-success text-white" role="alert">
	                    	<strong>{{ Session::get('status') }}</strong>
	                    </div>
	                    @endif
						<div class="buttons-list">
							<div class="text-right">
								<a href="/announcement/create" class="btn btn-primary">Add New</a>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<h3><i class="icon ti-announcement"></i> Announcement to dashboard</h3>	
							</div>
						</div>
						<hr/>
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th>Title</th>
										<th class="">Created at</th>
										<th class="">Announcement Until </th>
										<th>&nbsp;</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									@forelse  ($announce_list as $item)
										<tr class="table-row">
											<td>{{ $item->title }}</td>
											<td>{{ date( 'm/d/Y', strtotime( $item->created_at) )  }}</td>
											<td>{{ date( 'm/d/Y', strtotime( $item->closing_date) )  }}</td>
											<td class="text-right">
												<a href="/announcement/edit/{{ $item->id }}" class="btn btn-green"><i class="ion ion-edit"></i></a>
											</td>
											<td>
												<a href="/announcement/delete/{{ $item->id }}" class="btn btn-red"><i class="ion ion-ios-trash-outline"></i></a>
											</td>
										</tr>
									@empty
									     <tr class="table-row">
											<td colspan="5"><h4 class="text-center">No records found.</h4></td>
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
  <script type="text/javascript" src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
  
@endsection
