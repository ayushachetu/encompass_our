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
						<h1>Single Account</h1>
						<?php 
							
							foreach ($xml->{'invoice-lines'}->{'invoice-line'} as $item) {
								echo "::".$item->{'account'}->{'segment-2'}."--"."<br/>";
							}
						?>
						<h1>Multiple Accounts</h1>
						<?php 
						//echo $contents;
						//echo $xml->{'invoice-number'}."-here";


						//var_dump($xml->{'invoice-lines'}->{'invoice-line'}->{'account-allocations'});

						foreach ($xml->{'invoice-lines'}->{'invoice-line'} as $element) {
							//var_dump($element->{'account'});
							//echo '<br/><br/>';
							foreach ($element->{'account-allocations'} as $item_inner) {
                            foreach ($item_inner as $item_i) {
                                //echo "Amount:".($item_i->{'amount'})."<br/>";   
                                var_dump($item_i->{'account'});
                             }
                         	}
							/*foreach ($element->{'account-allocations'} as $item) {
								foreach ($item as $item_i) {
									echo "Amount:".($item_i->{'amount'})."<br/>";	
									echo ($item_i->{'account'}->{'segment-2'})."<br/>";	
								}	
							}*/
							
						}
						?>
						
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
