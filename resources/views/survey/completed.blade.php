@extends('layouts.default')

@section('styles')
<style type="text/css">
	.wrapper {
		min-height: 80vh;
	}
	.panel-body {
		height: 80vh;
	}
	h2 {
		margin-top: 50px;
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
								<div class="row">
									<div class="col-sm-12">
										<h2 align="center">{{ $message }}</h2>
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
	<script type="text/javascript" src="{{ asset('assets/js/sparkline.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/bic_calendar.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/widgets.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>
@endsection