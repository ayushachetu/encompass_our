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
				<span class="main-text">Delete {{ $user->first_name}} </span>
				
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
                        <strong>Oops! Something went wrong : </strong>
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
								<input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ $user->first_name}}">
							</div>
							<!--Input Form-->
							<!--Input Form-->
							<div class="form-group">
								<label class="control-label">Middle Name:</label>
								<input type="text" name="middle_name" class="form-control" placeholder="Middle Name" value="{{ $user->middle_name}}">
							</div>
							<!--Input Form-->
							<!--Input Form-->
							<div class="form-group">
								<label class="control-label">Last Name:</label>
								<input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ $user->last_name}}">
							</div>
							<!--Input Form-->

							<!--Input Form-->
							<div class="form-group">
								<label class="control-label">Email:</label>
								<input type="text" name="email" class="form-control" placeholder="Email" value="{{ $user->email}}" disabled>
							</div>
							<!--Input Form-->
						</div>

						<div class="col-md-6">
							<a href="/users">Cancel</a>
							<button class="btn btn-danger">Delete</button>
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
@endsection
