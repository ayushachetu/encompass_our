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
										
										<h1 class="heading">Talent Change Request</h1>              
										<form method="POST" action="/talent-form" id="talent-form" enctype="multipart/form-data">
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
												<div id="main-form">
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
														<div class="row">
																<div class="col-md-6">
																		<h4>Site Name(*)</h4>
																		<input class="form-control" name="site_name" id="" type="text" placeholder="" value="" data-validation="required">
																</div>
																<div class="col-md-6">
																		<h4>Site Account Number(*)</h4>
																		<input class="form-control" name="site_account_number" id="" type="text" placeholder="" value="" data-validation="required number">
																</div>
														</div>  

														<h4>Select an Action(*)</h4>

														<div class="row">
																<div class="col-md-12">
																		<select class="form-control" name="action_select" id="action-select" data-validation="required">
																				<option value="">Select an option</option>
																				<option value="1">Employee for New Position</option>
																				<option value="2">Terminate and Replace Position</option>
																				<option value="3">Change Status of Employee</option>
																		</select>
																</div>    
														</div>
												</div>  
												<div id="action-form-1" class="inside-form-option">
														<div class="row">
																<div class="col-md-4">
																		<label><small>Date Needed</small></label>
																		<input class="form-control date-format" name="form1_date_needed" type="text">
																		<span class="help-block">Date format: mm/dd/yyyy</span>
																</div>  
															 
														</div>
														<div class="row">
																<div class="col-md-6">
																		<label><small>Position Title</small></label>
																		<input class="form-control" name="form1_position_title" id="" type="text" placeholder="" value="">
																</div> 
																<div class="col-md-3">
																		<label><small>Position Rate</small></label>
																		<input class="form-control" name="form1_position_rate" id="" type="text" placeholder="" value="">
																</div> 
																<div class="col-md-3">
																		<div class="form-spacer"></div>
																		<select name="form1_measure_rate">
																				<option value="1">Hourly</option>
																				<option value="2">Yearly Salary</option>
																		</select>
																		
																</div>  

														</div>
														<div class="row">
																<div class="col-md-6">
																		<label><small>Position Job Code</small></label>
																		<select class="form-control" name="form1_position_job_code" id="form1_position_job_code">
																				<option value="">Select one</option>
																				<option value="101">101 Housekeeper</option>
																				<option value="102">102 Housekeeper/ Day Porter</option>
																				<option value="103">103 Housekeeper Supervisor</option>
																				<option value="201">201 Maintenance Supervisor</option>
																				<option value="202">202 Maintenance Day Porter</option>
																				<option value="301">301 Power Sweeper Operator</option> 
																				<option value="401">401 Grounds Maintenance Specialist</option> 
																				<option value="402">402 Grounds Maintenance Supervisor</option> 
																				<option value="501">501 MRO Services Handyman</option> 
																				<option value="502">502 MRO Services Supervisor</option> 
																				<option value="601">601 Floor Care Specialist</option> 
																				<option value="602">602 Floor Care Supervisor</option> 
																				<option value="701">701 Carpet Care Cleaning Specialist</option>      
																				<option value="801">801 Specialty Services</option>
																		</select>
																</div> 
																
														</div>
														<br/>
														<div class="row">
																<div class="col-md-6">
																		<label><small>Work Schedule</small></label>
																		<div>
																				<ul class="list-inline checkboxes-radio">
																						<li>
																								<small>Days:</small>
																						</li>
																						<li>
																								<input type="checkbox" id="mon" name="form1_work_schedule[]" value="mon" />
																								<label for="mon"><span></span>Mon</label>
																						</li>
																						<li>
																								<input type="checkbox" id="tue" name="form1_work_schedule[]" value="tue"/>
																								<label for="tue"><span></span>Tue</label>
																						</li>
																						<li>
																								<input type="checkbox" id="wed" name="form1_work_schedule[]" value="wed" />
																								<label for="wed"><span></span>Wed</label>
																						</li>
																						<li>
																								<input type="checkbox" id="thu" name="form1_work_schedule[]" value="thu" />
																								<label for="thu"><span></span>Thu</label>
																						</li>
																						<li>
																								<input type="checkbox" id="fri" name="form1_work_schedule[]" value="fri" />
																								<label for="fri"><span></span>Fri</label>
																						</li>
																						<li>
																								<input type="checkbox" id="sat" name="form1_work_schedule[]" value="sat" />
																								<label for="sat"><span></span>Sat</label>
																						</li>
																						<li>
																								<input type="checkbox" id="sun" name="form1_work_schedule[]" value="sun" />
																								<label for="sun"><span></span>Sun</label>
																						</li>
																				</ul>
																		</div>
																</div>
																<div class="col-md-3">
																		<label><small>Shift</small></label>
																		
																		<ul class="list-inline checkboxes-radio">
																						<li>
																								<input type="checkbox" id="form1_first" name="form1_shift[]" value="first" />
																								<label for="form1_first"><span></span>First</label>
																						</li>
																						<li>
																								<input type="checkbox" id="form1_second" name="form1_shift[]" value="second"/>
																								<label for="form1_second"><span></span>Second</label>
																						</li>
																						<li>
																								<input type="checkbox" id="form1_third" name="form1_shift[]" value="third" />
																								<label for="form1_third"><span></span>Third</label>
																						</li>
																				</ul>
																</div>
																<div class="col-md-3">
																		<label><small>Hours</small></label>
																		<div class="input-group demo-group">
																				<input type="text" name="form1_hours_per_week" class="form-control" style="margin:0;">
																				<span class="input-group-addon addon-right">per Week</span>
																		</div>
																</div>    
														</div>
														<label><small>Site Specific Work Requirements:</small></label>
														<textarea class="form-control" name="form1_site_specific"></textarea> 

												</div>

												<div id="action-form-2" class="inside-form-option">
														<h4>Person to be Terminated</h4>
														<div class="row">
																<div class="col-md-6">
																		<label><small>Employee Name(*)</small></label>
																		<input class="form-control" name="form2_employee_name" id="" type="text" placeholder="" value="" >
																</div>
																<div class="col-md-6">
																		<label><small>Employee Number(*)</small></label>
																		<input class="form-control" name="form2_employee_number" id="" type="text" placeholder="" value="" >
																</div>
														</div>  
														<div class="row">
																<div class="col-md-6">
																		<label><small>Effective Date(*)</small></label>
																		<input class="form-control date-format" name="form2_effective_date" id="" type="text" placeholder="" value="" >
																		<span class="help-block">Date format: mm/dd/yyyy</span>
																</div>
																<div class="col-md-6">
																		<label><small>Reason for Termination(*)</small></label>
																		<select name="form2_reason_termination" id="form2_reason_termination">
																				<option value="">Select one</option>
																				<option value="Voluntary">Voluntary</option>
																				<option value="Discharge">Discharge</option>
																				<option value="Retirement">Retirement</option>
																				<option value="Death">Death</option>
																		</select>
																</div>
														</div>  

														<label><small>Explanation for Termination(*):</small></label>
														<textarea class="form-control" name="form2_explanation_termination"></textarea> 
														<div class="row">
																<div class="col-md-12">
																		<label>Attach Files (optional)</label>
																		<input type="file" class="files" name="form2_termination_file">
																</div>
														</div>
														<input type="hidden" id="replace_position" name="form2_replace_position" value="0" />
														<a class="btn btn-primary btn-icon-primary btn-icon-block btn-icon-blockleft" id="btn_replace_position" style="margin-top:0;">
																<i class="ion-ios-plus-outline"></i>
																<span>Replace Position &nbsp;&nbsp;</span>
														</a>
														<br/><br/>
														<div id="container-add-person" style="display:none;">
																<div><label>Person to be Added</label></div>
																<div class="row">
																		<div class="col-md-6">
																				<label><small>Position Title</small></label>
																				
																				<input class="form-control" name="form2_add_position_title" id="" type="text" placeholder="" value="">
																		</div> 
																		<div class="col-md-3">
																				<label><small>Position Rate</small></label>
																				
																				<input class="form-control" name="form2_add_position_rate" id="" type="text" placeholder="" value="">
																		</div> 
																		<div class="col-md-3">
																				<div class="form-spacer"></div>
																				<select name="form2_add_measure_rate">
																						<option value="1">Hourly</option>
																						<option value="2">Yearly Salary</option>
																				</select>
																				
																		</div>  

																</div>
																<div class="row">
																		<div class="col-md-6">
																				<label><small>Position Job Code</small></label>
																				<select class="form-control" name="form2_add_position_job_code" id="form2_add_position_job_code">
																						<option value="">Select one</option>
																						<option value="101">101 Housekeeper</option>
																						<option value="102">102 Housekeeper/ Day Porter</option>
																						<option value="103">103 Housekeeper Supervisor</option>
																						<option value="201">201 Maintenance Supervisor</option>
																						<option value="202">202 Maintenance Day Porter</option>
																						<option value="301">301 Power Sweeper Operator</option> 
																						<option value="401">401 Grounds Maintenance Specialist</option> 
																						<option value="402">402 Grounds Maintenance Supervisor</option> 
																						<option value="501">501 MRO Services Handyman</option> 
																						<option value="502">502 MRO Services Supervisor</option> 
																						<option value="601">601 Floor Care Specialist</option> 
																						<option value="602">602 Floor Care Supervisor</option> 
																						<option value="701">701 Carpet Care Cleaning Specialist</option>      
																						<option value="801">801 Specialty Services</option>
																				</select>
																		</div> 
																		
																</div>
																<br/>
																<div class="row">
																		<div class="col-md-6">
																				<label><small>Work Schedule</small></label>
																				<div>
																						<ul class="list-inline checkboxes-radio">
																								<li>
																										<small>Days:</small>
																								</li>
																								<li>
																										<input type="checkbox" id="form2_add_mon" name="form2_add_work_schedule[]" value="mon" />
																										<label for="form2_add_mon"><span></span>Mon</label>
																								</li>
																								<li>
																										<input type="checkbox" id="form2_add_tue" name="form2_add_work_schedule[]" value="tue"/>
																										<label for="form2_add_tue"><span></span>Tue</label>
																								</li>
																								<li>
																										<input type="checkbox" id="form2_add_wed" name="form2_add_work_schedule[]" value="wed" />
																										<label for="form2_add_wed"><span></span>Wed</label>
																								</li>
																								<li>
																										<input type="checkbox" id="form2_add_thu" name="form2_add_work_schedule[]" value="thu" />
																										<label for="form2_add_thu"><span></span>Thu</label>
																								</li>
																								<li>
																										<input type="checkbox" id="form2_add_fri" name="form2_add_work_schedule[]" value="fri" />
																										<label for="form2_add_fri"><span></span>Fri</label>
																								</li>
																								<li>
																										<input type="checkbox" id="form2_add_sat" name="form2_add_work_schedule[]" value="sat" />
																										<label for="form2_add_sat"><span></span>Sat</label>
																								</li>
																								<li>
																										<input type="checkbox" id="form2_add_sun" name="form2_add_work_schedule[]" value="sun" />
																										<label for="form2_add_sun"><span></span>Sun</label>
																								</li>
																						</ul>
																				</div>
																		</div>
																		<div class="col-md-3">
																				<label><small>Shift</small></label>
																				
																				<ul class="list-inline checkboxes-radio">
																								<li>
																										<input type="checkbox" id="form2_add_first" name="form2_add_shift[]" value="first" />
																										<label for="form2_add_first"><span></span>First</label>
																								</li>
																								<li>
																										<input type="checkbox" id="form2_add_second" name="form2_add_shift[]" value="second"/>
																										<label for="form2_add_second"><span></span>Second</label>
																								</li>
																								<li>
																										<input type="checkbox" id="form2_add_third" name="form2_add_shift[]" value="third" />
																										<label for="form2_add_third"><span></span>Third</label>
																								</li>
																						</ul>
																		</div>
																		<div class="col-md-3">
																				<label><small>Hours</small></label>
																				<div class="input-group demo-group">
																						<input type="text" class="form-control" name="form2_add_hours_per_week" style="margin:0;">
																						<span class="input-group-addon addon-right">per Week</span>
																				</div>
																		</div>    
																</div>
																<label><small>Site Specific Work Requirements:</small></label>
																<textarea class="form-control" name="form2_add_site_specific"></textarea> 
														</div>
												
												</div>

												<div id="action-form-3" class="inside-form-option">
														<div class="row">
																<div class="col-md-6">
																		<label><small>Employee Name(*)</small></label>
																		<input class="form-control" name="form3_employee_name" id="" type="text" placeholder="" value="" >
																</div>
																<div class="col-md-6">
																		<label><small>Employee Number(*)</small></label>
																		<input class="form-control" name="form3_employee_number" id="" type="text" placeholder="" value="" >
																</div>
														</div>  
														<div class="row">
																<div class="col-md-6">
																		<label><small>Effective Date(*)</small></label>
																		<input class="form-control date-format" name="form3_effective_date" id="" type="text" placeholder="" value="" >
																		<span class="help-block">Date format: mm/dd/yyyy</span>
																</div>
																<div class="col-md-6">
																		<label><small>Change Requested(*)</small></label>
																		<select name="form3_change_requested" id="form3_change_requested">
																				<option value="">Select one</option>
																				<option value="Rehire (Less than 6-month break)">Rehire (Less than 6-month break)</option>
																				<option value="Transfer">Transfer</option>
																				<option value="Promotion">Promotion</option>
																				<option value="Unpaid Leave">Unpaid Leave</option>
																				<option value="FMLA (HR Approved)">FMLA (HR Approved)</option>
																				<option value="Pay Adjustment">Pay Adjustment</option>
																				<option value="Terminate">Terminate</option>
																		</select>
																</div>
														</div>
														<label><small>Explanation for Change(*):</small></label>
														<textarea class="form-control" name="form3_explanation_change"></textarea> 
														<div class="row">
																<div class="col-md-12">
																		<label>Attach Files (optional)</label>
																		<input type="file" class="files" name="form3_change_file">
																</div>
														</div>  
														<div class="row">
																<div class="col-md-6">
																		<label><small>Change in Work Schedule</small></label>
																		<div>
																				<ul class="list-inline checkboxes-radio">
																						<li>
																								<small>Days:</small>
																						</li>
																						<li>
																								<input type="checkbox" id="form3_mon" name="form3_work_schedule[]" value="mon" />
																								<label for="form3_mon"><span></span>Mon</label>
																						</li>
																						<li>
																								<input type="checkbox" id="form3_tue" name="form3_work_schedule[]" value="tue"/>
																								<label for="form3_tue"><span></span>Tue</label>
																						</li>
																						<li>
																								<input type="checkbox" id="form3_wed" name="form3_work_schedule[]" value="wed" />
																								<label for="form3_wed"><span></span>Wed</label>
																						</li>
																						<li>
																								<input type="checkbox" id="form3_thu" name="form3_work_schedule[]" value="thu" />
																								<label for="form3_thu"><span></span>Thu</label>
																						</li>
																						<li>
																								<input type="checkbox" id="form3_fri" name="form3_work_schedule[]" value="fri" />
																								<label for="form3_fri"><span></span>Fri</label>
																						</li>
																						<li>
																								<input type="checkbox" id="form3_sat" name="form3_work_schedule[]" value="sat" />
																								<label for="form3_sat"><span></span>Sat</label>
																						</li>
																						<li>
																								<input type="checkbox" id="form3_sun" name="form3_work_schedule[]" value="sun" />
																								<label for="form3_sun"><span></span>Sun</label>
																						</li>
																				</ul>
																		</div>
																</div>
																<div class="col-md-3">
																		<label><small>Shift</small></label>
																		
																		<ul class="list-inline checkboxes-radio">
																						<li>
																								<input type="checkbox" id="form3_first" name="form3_shift[]" value="first" />
																								<label for="form3_first"><span></span>First</label>
																						</li>
																						<li>
																								<input type="checkbox" id="form3_second" name="form3_shift[]" value="second"/>
																								<label for="form3_second"><span></span>Second</label>
																						</li>
																						<li>
																								<input type="checkbox" id="form3_third" name="form3_shift[]" value="third" />
																								<label for="form3_third"><span></span>Third</label>
																						</li>
																				</ul>
																</div>
																<div class="col-md-3">
																		<label><small>Hours</small></label>
																		<div class="input-group demo-group">
																				<input type="text" class="form-control" name="form3_hours_per_week" style="margin:0;">
																				<span class="input-group-addon addon-right">per Week</span>
																		</div>
																</div>    
														</div>
														<div><label>Change in Pay Rate</label></div>
														<div class="row">
																		<div class="col-md-3">
																				<label><small>Current Rate</small></label>
																				<input class="form-control" name="form3_current_rate" id="" type="text" placeholder="" value="">
																		</div> 
																		<div class="col-md-3">
																				<div class="form-spacer"></div>
																				<select name="form3_current_measure_rate">
																						<option value="1">Hourly(non-exempt)</option>
																						<option value="2">Yearly Salary(exempt)</option>
																				</select>
																		</div> 
																		<div class="col-md-3">
																				<label><small>New Rate</small></label>
																				<input class="form-control" name="form3_new_rate" id="" type="text" placeholder="" value="">
																		</div> 
																		<div class="col-md-3">
																				<div class="form-spacer"></div>
																				<select name="form3_new_measure_rate">
																						<option value="1">Hourly(non-exempt)</option>
																						<option value="2">Yearly Salary(exempt)</option>
																				</select>
																		</div>  
														</div>
														<div class="row">
																<div class="col-md-6">
																		<label><small>Change in Job Title</small></label>
																		<input class="form-control" name="form3_position_title" id="" type="text" placeholder="" value="">
																</div> 
														</div>
														<div class="row">
																<div class="col-md-6">
																		<label><small>Change in Position Job Code</small></label>
																		<select class="form-control" name="form3_position_job_code" id="form3_position_job_code">
																				<option value="">Select one</option>
																				<option value="101">101 Housekeeper</option>
																				<option value="102">102 Housekeeper/ Day Porter</option>
																				<option value="103">103 Housekeeper Supervisor</option>
																				<option value="201">201 Maintenance Supervisor</option>
																				<option value="202">202 Maintenance Day Porter</option>
																				<option value="301">301 Power Sweeper Operator</option> 
																				<option value="401">401 Grounds Maintenance Specialist</option> 
																				<option value="402">402 Grounds Maintenance Supervisor</option> 
																				<option value="501">501 MRO Services Handyman</option> 
																				<option value="502">502 MRO Services Supervisor</option> 
																				<option value="601">601 Floor Care Specialist</option> 
																				<option value="602">602 Floor Care Supervisor</option> 
																				<option value="701">701 Carpet Care Cleaning Specialist</option>      
																				<option value="801">801 Specialty Services</option>
																		</select>
																</div> 
																
														</div>
														<br/>
														<label>Describe any additional changes needed<small>(technology, training, other)</small></label>
														<textarea class="form-control" name="form3_additional_changes"></textarea> 
												</div>

												<div class="clearfix"></div>
												<div class="row">
														<div class="col-md-6 col-md-offset-3 col-sm-12">
																<button id="btn-submit" type="submit" class="btn btn-success btn-block">Submit</buttom>    
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

