@extends('layouts.default')
@section('styles')
<link href="{{ asset('assets/css/lock-screen2.css') }}" rel="stylesheet" type="text/css" >
@endsection
@section('content')
<div class="flip-container form-container">
        <div class="flipper">
            <div class="front">
                <!-- front content -->
                <div class="holder">
                    
                    <h1 class="heading">Job Request</h1>              
                    <form method="POST" action="/manager-form" id="manager-form">
                        {!! csrf_field() !!}
                        <div class="sign-alert">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <h4>Your Email(*)</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <input class="form-control" name="email" id="" type="text" placeholder="" value="{{$email}}" data-validation="required email">
                            </div>
                        </div>  
                        <h4>Account (Job)</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <label><small>Number(*)</small></label>
                                <input class="form-control" name="account_number" id="" type="text" placeholder="" value="" data-validation="required number">
                            </div>
                            <div class="col-md-6">
                                <label><small>Name(*)</small></label>
                                <input class="form-control" name="account_name" id="" type="text" placeholder="" value="" data-validation="required">
                            </div>
                        </div>  

                        <h4>Customer requesting this job</h4>

                        <div class="row">
                            <div class="col-md-4">
                                <label><small>Name(*)</small></label>
                                <input class="form-control" name="customer_name" id="" type="text" placeholder="" value="" data-validation="required">
                            </div>
                            <div class="col-md-5">
                                <label><small>Email(*)</small></label>
                                <input class="form-control" name="customer_email" id="" type="text" placeholder="" value="" data-validation="required email">
                            </div>
                            <div class="col-md-3">
                                <label><small>Cellphone(*)</small></label>
                                <input class="form-control" name="customer_cellphone" id="" type="text" placeholder="" value="" data-validation="required">
                            </div>
                        </div>  

                        <h4>Scope of work <small>(Define customer expectation and process to be used if necessary)</small></h4>
                        <textarea class="form-control" name="scope_work"></textarea>

                        <div class="row">
                            <div class="col-md-6">
                                <h4>Job location</h4>
                                <input class="form-control" name="job_location" id="" type="text" placeholder="" value="">
                            </div>
                            <div class="col-md-6">
                                <h4>Target Start date and time</h4>
                                <input class="form-control" name="target_start" id="" type="text" placeholder="" value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h4>Total labor hours needed <small>(if known)</small></h4>
                                <input class="form-control" name="labor_hours" id="" type="text" placeholder="" value="">
                            </div>
                            <div class="col-md-6">
                                <h4>Employee pay rate <small>(if known)</small></h4>
                                <input class="form-control" name="employee_pay_rate" id="" type="text" placeholder="" value="">
                            </div>
                        </div>

                        

                        <h4>Material cost <small>(if known)</small></h4>
                        <input class="form-control" name="material_cost" id="" type="text" placeholder="" value="">

                        <h4>Sub-contractor to be used <small>(if known)</small></h4>
                        <input class="form-control" name="sub_contractor" id="" type="text" placeholder="" value="">

                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-sm-12">
                                <button type="submit" class="btn btn-success btn-block">Submit</buttom>    
                            </div>
                                                   
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
        <div class="load_pulse"></div>
    </div>
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('assets/js/form-validation/jquery.form-validator.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/form-validation.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/app.form.js') }}"></script>
@endsection

