@extends('layouts.default')
@section('styles')
<link href="{{ asset('assets/css/animated-masonry-gallery.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('assets/css/rotated-gallery.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('assets/css/jtree.css') }}" rel="stylesheet" type="text/css" >
@endsection
@section('content')
	<div class="piluku-preloader text-center">
		<div class="loader">Loading...</div>
	  </div>
	<div class="wrapper">
	@include('includes.sidebar')
	<div class="content" id="content">
		<div class="overlay"></div>			
		@include('includes.topbar')

		<!-- Page Header -->
		<div class="page_header">
			<div class="pull-left">
				<i class="icon ti-comment-alt page_header_icon"></i>
				<span class="main-text">Draft Quotes</span>
				<p class="text"><a href="/quotes">< Back to quote dashboard</a></p>
			</div>
			<div class="right pull-right">
				<a href="/quotes/create" class="btn btn-primary btn-lg">Add New</a>
			</div>
		</div>
		<!-- /pageheader -->  
		<div class="main-content">
			<div class="row">
				<div class="col-md-12">
					<!-- panel -->
					<div class="panel panel-piluku panel-users">
						@if (Session::has('status'))
	                    <div class="alert bg-success text-white" role="alert">
	                    	<strong>{{ Session::get('status') }}</strong>
	                    </div>
	                    @endif
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-hover table-bordered">
									<thead>
										<tr>
											<th class="">Subject</th>
											<th class="">Job Number</th>
											<th class="">Total</th>
											<th class="">Date</th>
											<th>&nbsp;</th>
										</tr>
									</thead>
									<tbody>
										@forelse ($list as $item)
											<tr class="table-row">
												<td>{{ $item->subject }}</td>
												<td>{{ $item->job_number }}</td>
												<td class="text-right">${{ number_format($item->total,2) }}</td>
												<td>{{ date( 'm/d/Y', strtotime( $item->created_at) ) }}</td>
												<td class="text-right td-small">
													<a href="/quote/edit/{{ $item->id }}" class="btn btn-green"><i class="ion ion-edit"></i></a>
												</td>
											</tr>
										@empty
										    <tr><td colspan="5" class="text-center">No records found.</td></tr>
										@endforelse
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
					{!! $list->render() !!}
				</div>
			</div>	 
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
  <script type="text/javascript" src="{{ asset('assets/js/jquery.countTo.js') }}"></script>

@endsection
