@extends('layouts.default')
@section('styles')
<link href="{{ asset('assets/css/maps-custom.css') }}" rel="stylesheet" type="text/css" >
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
								<h3>
                  <i class="icon ti-location-arrow"></i>Job Map
                  @if($industry!=0)
                    <a href="/map" class="btn btn-default btn-sm pull-right">All Industries</a>
                  @endif
                </h3>	
								<hr/>
                <div>
                  @foreach ($list_industry as $key => $item) 
                    <a href="/map/{{$key}}"><img src="{{$list_industry_icon[$key]}}"></a> = 
                    @if($industry==$key)
                    <strong>{{$item}}</strong>
                    @else
                      {{$item}}
                    @endif
                    &nbsp;&nbsp;
                  @endforeach
                </div>
                <br/>
								<div class="map-wrapper">
									<div id="map" class="large"></div>	
								</div>
                <br>
                
                <h4>Jobs Pending Locations</h4>
                <div>
                  <table class="table table-bordered">
                     <tr>
                       <th>Job Number</th>
                       <th>Name</th>
                       <th>Longitude</th>
                       <th>Latitude</th>
                     </tr> 
                     @foreach($job_list_pending as $i_pending)
                      <tr>
                        <td>{{$i_pending->job_number}}</td>  
                        <td>{{$i_pending->job_description}}</td>
                        <td>{{$i_pending->longitude}}</td>
                        <td>{{$i_pending->latitude}}</td>
                      </tr>
                     @endforeach
                  </table>
                </div>
                
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
  <script type="text/javascript" src="{{ asset('assets/js/sparkline.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/bic_calendar.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/widgets.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>


  <script type="text/javascript" src="{{ asset('assets/js/jquery.countTo.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
  <script type="text/javascript">
  	function initMap() {
        var myLatLng = {lat: 26.208089, lng: -80.156038};
        

        // Create a map object and specify the DOM element for display.
        var map = new google.maps.Map(document.getElementById('map'), {
          center: myLatLng,
          scrollwheel: false,
          zoom: 8,

        });

        // Create a marker and set its position.
        
        @foreach ($job_list as $job_item)
          @if($job_item->longitude!=0 && $job_item->longitude!=null && $job_item->latitude!=0 && $job_item->latitude!=null)
            var marker = new google.maps.Marker({
              map: map,
              position: {lat: {{$job_item->latitude}}, lng: {{$job_item->longitude}} },
              title: '{{$job_item->job_number}} - {{$job_item->job_description}}',
              icon: '{{(isset($list_industry_icon[$job_item->division])?$list_industry_icon[$job_item->division]:"http://maps.google.com/mapfiles/kml/shapes/road_shield3.png")}}'
            });            
          @endif
        @endforeach
      }
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCD_IGTwrQ3QBI31eVX75NY3YrWyWb-vPw&callback=initMap" async defer></script>
  
@endsection
