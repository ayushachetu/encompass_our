@extends('layouts.default')
@section('styles')
<link href="{{ asset('assets/css/lock-screen2.css') }}" rel="stylesheet" type="text/css" >

@endsection
@section('content')
<div class="flip-container">
        <div class="flipper">
            <div class="front">
                <!-- front content -->
                <div class="holder">
                    
                    <h1 class="heading">Training Registration</h1>   
                    <div class="text-center">
                        <img src="/assets/images/avatar/Encompass_halo_shadow.png">
                    </div>           
                    <br/><br/>
                    <div class="text-center">
                        <a class="btn btn-success btn-block" href="/training-form">New Entry</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="load_pulse"></div>
    </div>
@endsection
@section('scripts')

@endsection

