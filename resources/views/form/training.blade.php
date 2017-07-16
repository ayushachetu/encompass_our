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
                    
                    <h1 class="heading">Training Registration</h1>              
                    <form method="POST" action="/training-form" id="manager-form">
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
                        <div class="row">
                            <div class="col-md-12">
                                <div style="background: #f3f3f3;border:1px solid #d4d4d4;">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div style="border-right:1px solid #d4d4d4;">
                                                <h4 class="text-center" style="font-size: 22px;">Download Calendar</h4>        
                                            </div>
                                            
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="text-center">
                                                        <div><strong><small>This Month</small></strong></div>
                                                        <div><a href="https://www.dropbox.com/sh/gqcofbpik672frx/AAAL2MD1X9q0UniPWE7Rp4hKa?dl=0" target="_blank"><span class="ion-calendar" style="font-size: 2.5em;"></span></a></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="text-center">
                                                        <div><strong><small>Next Month</small></strong></div>
                                                        <div><a href="https://www.dropbox.com/sh/gqcofbpik672frx/AAAL2MD1X9q0UniPWE7Rp4hKa?dl=0" target="_blank"><span class="ion-calendar" style="font-size: 2.5em;"></span></a></div>
                                                    </div>
                                                </div>
                                            </div>    
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Your Name(*)</h4>
                                <input class="form-control" name="name" id="" type="text" placeholder="" value="{{$name}}" data-validation="required">
                            </div>
                            <div class="col-md-6">
                                <h4>Your Email(*)</h4>
                                <input class="form-control" name="email" id="" type="text" placeholder="" value="{{$email}}" data-validation="required email">
                            </div>
                        </div>   
                        <h4>Employee</h4>
                        <div id="line-elements-new">
                            <div id="item-1">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label><small>Name(*)</small></label>
                                        <input class="form-control employee-name-input" name="employee_name[]" id="" type="text" placeholder="" value="" data-validation="required">
                                    </div>
                                    <div class="col-md-4">
                                        <label><small>Employee Number(*)</small></label>
                                        <input class="form-control employee-number-input" name="employee_number[]" id="" type="text" placeholder="" value="" data-validation="required number">
                                    </div>
                                    <div class="col-md-4">
                                        <label><small>Account Number(*)</small></label>
                                        <input class="form-control account-number-input" name="account_number[]" id="" type="text" placeholder="" value="" data-validation="required number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a id="btn-add-new-line" href="javascript:void(0)" class="btn btn-gray btn-round" style="width:40px;"><span class="icon ion-android-add"></span></a>
                            </div>
                        </div>
                        <h4>Date for Training Registration(*)</h4>
                        <div class="row">
                            <div class="col-md-3">
                                <!--<input class="form-control" name="date_month_training" type="text" data-validation="required number">-->
                                <select class="form-control" name="date_month_training" data-validation="required number">
                                    <option value="01">January</value>
                                    <option value="02">February</value>
                                    <option value="03">March</value>
                                    <option value="04">April</value>
                                    <option value="05">May</value>
                                    <option value="06">June</value>
                                    <option value="07">July</value>
                                    <option value="08">August</value>
                                    <option value="09">September</value>
                                    <option value="10">Octuber</value>
                                    <option value="11">November</value>
                                    <option value="12">December</value>
                                </select>
                            </div>  
                            <div class="col-md-2">
                                <input class="form-control" name="date_day_training" type="text" data-validation="required number" placeholder="dd">
                            </div>  
                            <div class="col-md-3">

                                <!--<input class="form-control" name="date_year_training" type="text" data-validation="required number">-->
                                <select class="form-control" name="date_year_training" data-validation="required number">
                                    <?php for ($i=date('Y'); $i < date('Y')+5 ; $i++) {  ?>
                                            <option value="<?=$i?>"><?=$i?></option>
                                    <?php   } ?>
                                </select>
                            </div>  
                        </div> 
                        <div>
                            <span class="help-block">Date format: mm/dd/yyyy</span>
                        </div> 
                        <label><small>Comment:</small></label>
                        <textarea class="form-control" name="comment"></textarea> 
                        <br/>
                        <label><small>Send a copy of the registration to this emails:</small></label>
                        <div class="row">
                            <div class="col-md-12">
                                <input class="form-control" name="copy_emails" id="" type="text" placeholder="" value="" >
                            </div>
                        </div> 
                        <div>
                            <span class="help-block">Seperate multiple emails by comma ','</span>
                        </div> 
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
<!--<script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.maskedinput.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/app.form.js') }}"></script>-->
<script type="text/javascript">
    $(document).ready(function(){
        window.applyValidation(true, '#manager-form', 'top');
    });

     $( "#btn-add-new-line" ).click(function() {
    var new_element='<div class="row"><div class="col-md-4"><label><small>Name(*)</small></label><input class="form-control employee-name-input" name="employee_name[]" id="" type="text" placeholder="" value="" data-validation="required"></div><div class="col-md-4"><label><small>Employee Number(*)</small></label><input class="form-control employee-number-input" name="employee_number[]" id="" type="text" placeholder="" value="" data-validation="required number"></div><div class="col-md-4"><label><small>Account Number(*)</small></label><input class="form-control account-number-input" name="account_number[]" id="" type="text" placeholder="" value="" data-validation="required number"></div></div>';
    $('#line-elements-new').append(new_element);
    
  });
</script>
@endsection

