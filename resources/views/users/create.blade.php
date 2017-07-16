@extends('layouts.default')
@section('styles')

@endsection
@section('content')	
	<div class="wrapper ">
	@include('includes.sidebar')
	<div class="content" id="content">
	<div class="overlay"></div>				
	@include('includes.topbar')
		<!-- Page Header -->
		<div class="page_header">
			<div class="pull-left">
				<i class="icon ti-user page_header_icon"></i>
				<span class="main-text">New User</span>
				
			</div>
		</div>
		<!-- /pageheader -->
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
					<form class="form" method="post">
						{!! csrf_field() !!}
						<div class="col-md-6">
							<!--Input Form-->
							<div class="form-group">
								<label class="control-label">First Name:</label>
								<input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ old('first_name') }}">
							</div>
							<!--Input Form-->
							<!--Input Form-->
							<div class="form-group">
								<label class="control-label">Middle Name:</label>
								<input type="text" name="middle_name" class="form-control" placeholder="Middle Name" value="{{ old('middle_name') }}">
							</div>
							<!--Input Form-->
							<!--Input Form-->
							<div class="form-group">
								<label class="control-label">Last Name:</label>
								<input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ old('last_name') }}">
							</div>
							<!--Input Form-->

							<!--Input Form-->
							<div class="form-group">
								<label class="control-label">Email:</label>
								<input type="text" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
							</div>
							<!--Input Form-->
							<!--Input Form-->
							<div class="form-group">
								<label class="control-label">Employee Number:</label>
								<input type="text" name="employee_number" class="form-control" placeholder="Employee Number" value="{{ old('employee_number') }}">
							</div>
							<!--Input Form-->

							<!--Input Form-->
							<div class="form-group">
								<label class="control-label">Primary Job(Supervisor):</label>
								<input type="text" name="primary_job" class="form-control" placeholder="Primary Job" value="{{ old('primary_job') }}">
							</div>
							<!--Input Form-->
							
						</div>

						<div class="col-md-6">
							<!-- xselectize form   -->
							<div class="form-group">
								<label class="control-label">Permission Type:</label>
								<select class="name_search form-control" name="role">
									<option value="0"> Select  </option>
									<option value="1"> Administrator </option>
									<option value="7"> Dashboard Manager </option>
									<option value="2"> Financial </option>
									<option value="3"> User </option>
									<option value="4"> Dir PS </option>
									<option value="6"> Area Manager </option>
									<option value="5"> Area Supervisor </option>
									<option value="8"> Supervisor </option>
									<option value="9"> Employee </option>
								</select>
							</div>
							<!-- xselect form   -->
							<!--Input Form-->
							<div class="form-group">
								<label class="control-label">Manager ID:</label>
								<input type="text" name="manager_id" class="form-control" placeholder="Manager ID" value="{{ old('manager_id') }}">
							</div>
							<!--Input Form-->
							<!--Default Form with password-->
							<div class="form-group">
								<label class="control-label">Password:</label>
								<input type="password" name="password" class="form-control" placeholder="Password">
							</div>
							<!--Default Form with password-->

							<!--Default Form with password-->
							<div class="form-group">
								<label class="control-label">Password Confirmation:</label>
								<input type="password" name="password_confirmation" class="form-control" placeholder="Password Confirmation">
							</div>
							<!--Default Form with password-->
							<!--Textarea Form-->
							<div class="form-group">
								<label class="control-label">Note:</label>
								<textarea placeholder="Note" rows="12" cols="30" class="form-control text-area" name="note">{{ old('note') }}</textarea>
							</div>
							<!--Textarea Form-->
							
						</div>
						<div class="col-md-12">
							<div class="text-right">
								<a href="/users">Cancel</a>
								<button class="btn btn-primary">Save</button>
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
