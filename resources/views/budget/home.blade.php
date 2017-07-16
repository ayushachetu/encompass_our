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

		<!-- Page Header -->
		<div class="page_header">
			<div class="pull-left">
				<i class="icon ti-briefcase page_header_icon"></i>
				<span class="main-text">Budget</span>
			</div>
		</div>
		<!-- /pageheader -->  
		<div class="main-content">
			
			<!--row-->
			<div class="row">
				<div class="col-md-4 col-xs-12 col-sm-6 ">
					<div class="filter-box">
						<div class="text-center">
							<h3>Set Months Range</h3>
						</div>
						<div id="wrapper-dates">
							<div class="text-center"><strong>From:</strong></div>
							<select class="form-control" id="ini_month">
								<option value="1" {{($month_ini==1)?'selected="selected"':''}}>January</option>
							    <option value="2" {{($month_ini==2)?'selected="selected"':''}}>February</option>
							    <option value="3" {{($month_ini==3)?'selected="selected"':''}}>March</option>
							    <option value="4" {{($month_ini==4)?'selected="selected"':''}}>April</option>
							    <option value="5" {{($month_ini==5)?'selected="selected"':''}}>May</option>
							    <option value="6" {{($month_ini==6)?'selected="selected"':''}}>June</option>
							    <option value="7" {{($month_ini==7)?'selected="selected"':''}}>July</option>
							    <option value="8" {{($month_ini==8)?'selected="selected"':''}}>August</option>
							    <option value="9" {{($month_ini==9)?'selected="selected"':''}}>September</option>
							    <option value="10" {{($month_ini==10)?'selected="selected"':''}}>October</option>
							    <option value="11" {{($month_ini==11)?'selected="selected"':''}}>November</option>
							    <option value="12" {{($month_ini==12)?'selected="selected"':''}}>December</option>
							</select>
							<div class="text-center"><strong>To:</strong></div>
							<select class="form-control" id="end_month">
								<option value="1" {{($month_end==1)?'selected="selected"':''}}>January</option>
							    <option value="2" {{($month_end==2)?'selected="selected"':''}}>February</option>
							    <option value="3" {{($month_end==3)?'selected="selected"':''}}>March</option>
							    <option value="4" {{($month_end==4)?'selected="selected"':''}}>April</option>
							    <option value="5" {{($month_end==5)?'selected="selected"':''}}>May</option>
							    <option value="6" {{($month_end==6)?'selected="selected"':''}}>June</option>
							    <option value="7" {{($month_end==7)?'selected="selected"':''}}>July</option>
							    <option value="8" {{($month_end==8)?'selected="selected"':''}}>August</option>
							    <option value="9" {{($month_end==9)?'selected="selected"':''}}>September</option>
							    <option value="10" {{($month_end==10)?'selected="selected"':''}}>October</option>
							    <option value="11" {{($month_end==11)?'selected="selected"':''}}>November</option>
							    <option value="12" {{($month_end==12)?'selected="selected"':''}}>December</option>
							</select>
						</div>
						<div id="wrapper-dates-loader" style="display: none;" class="text-center">
							<h1>Saving...</h1>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-6 ">
					<div class="info-five primarybg-info">
						<div class="logo"><i class="ti-layers"></i></div>
						<p>Report</p>
						<br/>
						<p>
							<a href="/budget/report" class="btn btn-warning">View</a>
						</p>
					</div>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-6 ">
					<div class="info-five redbg-info">
						<div class="logo"><i class="ti-image"></i></div>
						<p>Graphs</p>
						<br/>
						<p>
							<a href="/budget/graphs" class="btn btn-warning">View</a>
						</p>
					</div>
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
  <script type="text/javascript">
  	$('#ini_month').on('change', function (e) {        
      var value_month=$('#ini_month').val();
      $('#wrapper-dates').hide();
      $('#wrapper-dates-loader').show();
      $.ajax({
        url:  '/budget/change-month/1/'+value_month,
        type: "get",
        success: function(dataResponse){
          $('#wrapper-dates').show();
          $('#wrapper-dates-loader').hide();
        },
        error: function(data){
          
        },
      });
  	});

  	$('#end_month').on('change', function (e) {        
      var value_month=$('#end_month').val();
      $('#wrapper-dates').hide();
      $('#wrapper-dates-loader').show();
      $.ajax({
        url:  '/budget/change-month/2/'+value_month,
        type: "get",
        success: function(dataResponse){
          $('#wrapper-dates').show();
          $('#wrapper-dates-loader').hide();
        },
        error: function(data){
          
        },
      });
      
  	});
  </script>
@endsection
