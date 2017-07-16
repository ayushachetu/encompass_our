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
								<h3><i class="icon  ion-ios-keypad-outline"></i> Exit Interview</h3>	
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
										<td class="text-right">Manager Name:</td>
										<td>{{ $item->name}}</td>
									</tr>
									<tr class="table-row">
										<td class="text-right">Manager Email:</td>
										<td>{{ $item->email}}</td>
									</tr>
									<tr class="table-row">
										<td class="text-right">Employee Name:</td>
										<td>{{ $item->employee_name}}</td>
									</tr>
									<tr class="table-row">
										<td class="text-right">Employee Number:</td>
										<td>{{ $item->employee_number}}</td>
									</tr>
									<tr>
										<td colspan="2"><strong>Questions</strong></td>
									</tr>
									<tr>
										<td colspan="2">1. Why have you decided to leave Encompass Onsite?</td>
									</tr>
									<tr class="table-row">
										<td colspan="2">{{ $item->question_1}}</td>
									</tr>
									<tr>
										<td colspan="2">2. What did you like about your job?</td>
									</tr>
									<tr class="table-row">
										<td colspan="2">{{ $item->question_2}}</td>
									</tr>
									<tr>
										<td colspan="2">3. What didn't you like about your job?</td>
									</tr>
									<tr class="table-row">
										<td colspan="2">{{ $item->question_3}}</td>
									</tr>

									<tr>
										<td colspan="2">4. What was the most satisfying part of your job?</td>
									</tr>
									<tr class="table-row">
										<td colspan="2">{{ $item->question_4}}</td>
									</tr>

									<tr>
										<td colspan="2">5. What caused you frustration at work?</td>
									</tr>
									<tr class="table-row">
										<td colspan="2">{{ $item->question_5}}</td>
									</tr>

									<tr>
										<td colspan="2">6. Describe your relationship with your supervisor.</td>
									</tr>
									<tr class="table-row">
										<td colspan="2">{{ $item->question_6}}</td>
									</tr>
									<tr>
										<td colspan="2">7. Have you accepted another position? If yes, with what company?</td>
									</tr>
									<tr class="table-row">
										<td colspan="2">{{ $item->question_7}}</td>
									</tr>

									<tr>
										<td colspan="2">8. Is there anything we can do to give you a reason to stay?</td>
									</tr>
									<tr class="table-row">
										<td colspan="2">{{ $item->question_8}}</td>
									</tr>
									<tr>
										<td colspan="2">9. Will you recommend Encompass Onsite to a friend as a good place to work?</td>
									</tr>
									<tr class="table-row">
										<td colspan="2">{{ $item->question_9}}</td>
									</tr>

									<tr>
										<td colspan="2">10. Do you have anything else you would like to discuss?</td>
									</tr>
									<tr class="table-row">
										<td colspan="2">{{ $item->question_10}}</td>
									</tr>

									<tr>
										<td colspan="2">Additional Comments:</td>
									</tr>
									<tr class="table-row">
										<td colspan="2">{{ $item->comment}}</td>
									</tr>
									
									
								</tbody>
							</table>
						</div>
						<div class="text-left">
							<a href="/history-exit-interview">< Return to List</a>
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
