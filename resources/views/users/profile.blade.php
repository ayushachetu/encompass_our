@extends('layouts.default')
@section('styles')

@endsection
@section('content')	
	<div class="wrapper ">
	@include('includes.sidebar')
	<div class="content" id="content">
	<div class="overlay"></div>				
	@include('includes.topbar')
		<!--  *** Profile cover ***-->
		<div class="profile-heading">
			<div class="profile-img">
				<img src="/assets/images/avatar/one.png" alt="">
			</div>
			<div class="profile-name">
				{{ $user->first_name}} {{ $user->last_name}} <br/>
				# {{ $user->employee_number}}
			</div>
		</div>
		<!--*** /Profile cover ***-->

		<!-- main content -->
		<div class="main-content">
			<!--theme panel-->
			<div class="panel">
				<div class="panel-body">
					@if (Session::has('errors'))
                    <div class="alert alert-danger" role="alert">
                    <ul>
                        <strong>Error Message : </strong>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if (Session::has('status'))
	                    <div class="alert bg-info text-white" role="alert">
	                    	<strong>{{ Session::get('status') }}</strong>
	                    </div>
                    @endif
					<form class="form" method="post">
						{!! csrf_field() !!}
						<div class="row">
							<div class="col-md-6">
								<!--Input Form-->
								<div class="form-group">
									<label class="control-label">First Name:</label>
									<input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ $user->first_name}}">
								</div>
								<!--Input Form-->	
							</div>
							<div class="col-md-6">
								<!--Input Form-->
								<div class="form-group">
									<label class="control-label">Last Name:</label>
									<input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ $user->last_name}}">
								</div>
								<!--Input Form-->
							</div>
							
						</div>

						<div class="row">
							<div class="col-md-12">
								<!--Input Form-->
								<div class="form-group">
									<label class="control-label">Email:</label>
									<input type="text" name="email" class="form-control" placeholder="Email" value="{{ $user->email}}" disabled>
								</div>
								<!--Input Form-->	
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<!--Default Form with password-->
								<div class="form-group">
									<label class="control-label">Password:</label>
									<input type="password" name="password" class="form-control" placeholder="Password">
								</div>
								<!--Default Form with password-->	
							</div>
							<div class="col-md-6">
								<!--Default Form with password-->
								<div class="form-group">
									<label class="control-label">Password Confirmation:</label>
									<input type="password" name="password_confirmation" class="form-control" placeholder="Password Confirmation">
								</div>
								<!--Default Form with password-->		
							</div>
							
						</div>
						<div class="col-md-12">
							<div class="text-right">
								<a href="/dashboard">Cancel</a>
								<button class="btn btn-orange">Update</button>
							</div>
						</div>

					</form>
				</div>
			</div>
			<!--theme panel-->
		</div>
		<!-- /main content -->	
	</div>
</div>
@endsection

@section('scripts')
  <script type="text/javascript" src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/wow.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.loadmask.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.accordion.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/materialize.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/build/d3.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/nvd3/nv.d3.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.multi-select.js') }}"></script>	
  <script type="text/javascript" src="{{ asset('assets/js/bootstrap-datepicker.js') }}"></script>	
  <script type="text/javascript" src="{{ asset('assets/js/bootstrap-colorpicker.js') }}"></script>	
  <script type="text/javascript" src="{{ asset('assets/js/jquery.maskedinput.js') }}"></script>	
  <script type="text/javascript" src="{{ asset('assets/js/form-elements.js') }}"></script>
@endsection
