@extends('layouts.default')
@section('styles')
<link href="{{ asset('assets/css/lock-screen2.css') }}" rel="stylesheet" type="text/css" >
@endsection
@section('content')
<!-- resources/views/auth/register.blade.php -->
    <div class="flip-container">
        <div class="flipper">
            <div class="front">
                <!-- front content -->
                <div class="holder">
                    
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
                    
                    <h1 class="heading">Register</h1>
                    <form method="POST" action="/auth/register">
                        {!! csrf_field() !!}
                        <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Name">
                        <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Email">
                        <input class="form-control" type="password" name="password" placeholder="Password">
                        <input class="form-control" type="password" name="password_confirmation" placeholder="Password Confirmation">                        
                        <div class="clearfix"></div>
                        <div>
                            <button class="btn btn-primary btn-block sign" type="submit">Register</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>    
</form>
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('assets/js/lockscreen.js') }}"></script>
@endsection