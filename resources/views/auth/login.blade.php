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
                    <div class="sign-alert"></div>
                    <h1 class="heading">EncompassOnSite</h1>              
                    <form method="POST" action="/admin" id="sign-form">
                        {!! csrf_field() !!}
                        <div class="login-option">
                            <a class="btn btn-success" href="javascript:void(0);" id="employeenumbertab" ><i class="ion ion-android-person"></i> Employee Number</a>
                            <a class="btn btn-default" href="javascript:void(0);" id="emailtab"><i class="ion ion-email"></i> Email</a>
                        </div>

                        <div  class="" id="emailtabcontent" style="display:none;"><input class="form-control" name="email" id="emailInput" type="text" placeholder="user@mail.com" value="">  </div>
                        <div  class="active" id="employeenumbertabcontent"><input class="form-control" name="employee_number" id="employeeInput" type="text" placeholder="#Number" value=""></div>
                                         
                        <input type="password" name="password" id="passwordInput"  class="form-control" placeholder="Password" value="">                 
                        <div class="text-right" style="margin-top: 10px;">
                            <input type="checkbox" id="remember" name="remember" value="1">
                            <label for="remember" style="font-weight: bold; color:#000; font-size: 1.3em;"><span></span>Keep me logged in</label>
                        </div>
                        <div class="clearfix"></div>
                        <button type="submit" class="btn btn-primary btn-block sign">Sign in</buttom>                   
                    </form>
                </div>
            </div>
            <div class="back">
                <!-- front content -->              
                <div class="holder">
                    <div class="avatar_holder">
                        <img src="assets/images/avatar/Encompass_halo_shadow.png" alt="">
                    </div>                      
                    <div class="log_msg">Logging in...</div>                   
                </div>
            </div>
        </div>
        <div class="load_pulse"></div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="forgot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="ion-android-settings"></i> First Login</h4>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <label>Employee Number</label>
                        <input type="text" class="form-control" placeholder="Enter your employee number">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-red" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Request</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('assets/js/lockscreen.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
@endsection

