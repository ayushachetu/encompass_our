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
                    
                    <h1 class="heading">Exit Interview</h1>              
                    <form method="POST" action="/exit-interview-form" id="manager-form">
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
                        <h4>Manager</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <label><small>Name(*)</small></label>
                                <input class="form-control" name="name" id="" type="text" placeholder="" value="{{$name}}" data-validation="required">
                            </div>
                            <div class="col-md-6">
                                <label><small>Email(*)</small></label>
                                <input class="form-control" name="email" id="" type="text" placeholder="" value="{{$email}}" data-validation="required email">
                            </div>
                        </div>   
                        <h4>Employee</h4>
                        <div>
                            <div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label><small>Name(*)</small></label>
                                        <input class="form-control employee-name-input" name="employee_name" id="" type="text" placeholder="" value="" data-validation="required">
                                    </div>
                                    <div class="col-md-6">
                                        <label><small>Number(*)</small></label>
                                        <input class="form-control employee-number-input" name="employee_number" id="" type="text" placeholder="" value="" data-validation="required number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <h4>Questions</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small>1. Why have you decided to leave Encompass Onsite?</small></label>
                                <textarea class="form-control" name="question_1" style="line-height: 120%; height: 60px;"></textarea> 
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small>2. What did you like about your job?</small></label>
                                <textarea class="form-control" name="question_2" style="line-height: 120%; height: 60px;"></textarea> 
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small>3. What didn't you like about your job?</small></label>
                                <textarea class="form-control" name="question_3" style="line-height: 120%; height: 60px;"></textarea> 
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small>4. What was the most satisfying part of your job?</small></label>
                                <textarea class="form-control" name="question_4" style="line-height: 120%; height: 60px;"></textarea> 
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small>5. What caused you frustration at work?</small></label>
                                <textarea class="form-control" name="question_5" style="line-height: 120%; height: 60px;"></textarea> 
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small>6. Describe your relationship with your supervisor.</small></label>
                                <textarea class="form-control" name="question_6" style="line-height: 120%; height: 60px;"></textarea> 
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small>7. Have you accepted another position? If yes, with what company?</small></label>
                                <textarea class="form-control" name="question_7" style="line-height: 120%; height: 60px;"></textarea> 
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small>8. Is there anything we can do to give you a reason to stay?</small></label>
                                <textarea class="form-control" name="question_8" style="line-height: 120%; height: 60px;"></textarea> 
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small>9. Will you recommend Encompass Onsite to a friend as a good place to work?</small></label>
                                <textarea class="form-control" name="question_9" style="line-height: 120%; height: 60px;"></textarea> 
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small>10. Do you have anything else you would like to discuss?</small></label>
                                <textarea class="form-control" name="question_10" style="line-height: 120%; height: 60px;"></textarea> 
                            </div>
                        </div>
                        <hr/>
                        <label><small>Additional Comments:</small></label>
                        <textarea class="form-control" name="comment" style="line-height: 120%; height: 80px;"></textarea> 
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
<script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.maskedinput.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/app.form.js') }}"></script>
@endsection

