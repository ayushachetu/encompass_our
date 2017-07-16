@extends('layouts.default')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/filter-dropdown.css') }}">
@endsection

@section('content')
<div class="piluku-preloader text-center">
	<div class="loader">Loading...</div>
</div>
<div class="wrapper">
	@include('includes.sidebar')
	@include('quality.select-survey')
	<div class="content" id="content">
		<div class="overlay"></div>			
		@include('includes.topbar')
		<div class="main-content">
			<div class="row grid">
				<div class="col-sm-12">
					<div class="panel panel-piluku">
						<div class="panel-body">
							{!! csrf_field() !!}
							<div class="row">
								<div class="col-sm-12">
									<h3><i class="icon  ti-bookmark-alt "></i> Quality Assurance</h3>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-8">
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon bg">
														<i class="ion-android-search"></i>
													</span>
													<select class="job_search form-control">
														<option value="0"> All</option>
														@foreach ($job_list_health as $item)
															<option value="{{ $item->job_number}}"> #{{$item->job_number}} - {{$item->job_description}}</option>
														@endforeach
														@foreach ($job_list_education as $item)
															<option value="{{ $item->job_number}}"> #{{$item->job_number}} - {{$item->job_description}}</option>
														@endforeach
														@foreach ($job_list_commercial as $item)
															<option value="{{ $item->job_number}}"> #{{$item->job_number}} - {{$item->job_description}}</option>
														@endforeach
														@foreach ($job_list_hospitality as $item)
															<option value="{{ $item->job_number}}"> #{{$item->job_number}} - {{$item->job_description}}</option>
														@endforeach
														@foreach ($job_list_government as $item)
															<option value="{{ $item->job_number}}"> #{{$item->job_number}} - {{$item->job_description}}</option>
														@endforeach
														@foreach ($job_list_publicvenue as $item)
															<option value="{{ $item->job_number}}"> #{{$item->job_number}} - {{$item->job_description}}</option>
														@endforeach
														@foreach ($job_list_retail as $item)
															<option value="{{ $item->job_number}}"> #{{$item->job_number}} - {{$item->job_description}}</option>
														@endforeach
														@foreach ($job_list_industrial as $item)
															<option value="{{ $item->job_number}}"> #{{$item->job_number}} - {{$item->job_description}}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div id="wrapper-quality">
								<div class="row quality-item">
									<div class="col-sm-12">
										<div class="evaluation-heading">
											<span class="name-employee">Healthcare</span>
										</div>
									</div>
								</div>
								@php ($accordion_num = 0)
								@forelse ($job_list_health as $item)
									@php ($accordion_num += 1)
									@php ($job_number = $item->job_number)
									<div class="panel-group" id="accordion{{ $accordion_num }}" role="tablist" aria-multiselectable="true">
										<div class="panel panel-default custom-panel">
											<div class="panel-heading" role="tab" id="headingOne">
												<h4 class="panel-title">
													<div class="pull-left title-text">#{{$job_number}} - {{$item->job_description}}</div>
													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-success m-l-10 job-surveys-list">Surveys List</a>

													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-primary job-questions-list">Questions List</a>
													<div class="clearfix"></div>
												</h4>
											</div>
											<div id="collapse{{ $accordion_num }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
												<div class="panel-body">
													@if (isset($job_survey->$job_number))
													<div class="survey_list_wrapper">
														@foreach ($job_survey->$job_number as $js)
															@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['1']."&Version=".$type_survey."&Job=".$job_number."&Random=0&Manager=".$item->manager."&Question=All"))
															<div class="row">
																<div class="col-sm-6">
																	<a target="_blank" href="{{ $link }}" class="survey-link" style="background: {{ $survey_bg[$js['random_id']] }}">{{ $js['name'].'-'.$label_survey }}</a>
																</div>
																@if ($js['express'] == 1)
																	@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['1']."&Version=".$type_survey."&Job=".$job_number."&Random=1&Manager=".$item->manager."&Question=All"))
																	<div class="col-sm-6">
																		<a target="_blank" href="{{ $link }}" class="survey-link" style="background: {{ $survey_bg[$js['random_id']] }}">{{ $js['name'].'-Express' }}</a>
																	</div>
																@endif
															</div>
														@endforeach
													</div>
													<div class="question_list_wrapper">
														<div class="row">
															@foreach ($industry_question['Healthcare'] as $health_ques)
																<div class="col-sm-4 col-xs-12">
																	<div class="question-name-div" data-job="{{ $job_number }}" data-industry="Healthcare" data-manager="{{ $item->manager }}">{{ $health_ques }}</div>
																</div>
															@endforeach
														</div>
													</div>
													@endif
												</div>
											</div>
										</div>
									</div>
								@empty
									<div class="quality-item">
										<h4>Healthcare jobs not assigned.</h4>
									</div>
								@endforelse

								<div class="row quality-item">
									<br/>
									<div class="col-md-12">
										<div class="evaluation-heading">
											<span class="name-employee">Education</span>
										</div>
									</div>
									<br/>
								</div>
								@forelse ($job_list_education as $item)
									@php ($accordion_num += 1)
									@php ($job_number = $item->job_number)
									<div class="panel-group" id="accordion{{ $accordion_num }}" role="tablist" aria-multiselectable="true">
										<div class="panel panel-default">
											<div class="panel-heading" role="tab" id="headingOne">
												<h4 class="panel-title">
													<div class="pull-left">#{{$job_number}} - {{$item->job_description}}</div>
													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-success pull-right m-l-10 job-surveys-list">Surveys List</a>

													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-primary pull-right job-questions-list">Questions List</a>
													<div class="clearfix"></div>
												</h4>
											</div>
											<div id="collapse{{ $accordion_num }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
												<div class="panel-body">
													@if (isset($job_survey->$job_number))
													<div class="survey_list_wrapper">
														@foreach ($job_survey->$job_number as $js)
															@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['3']."&Version=".$type_survey."&Job=".$job_number."&Random=0&Manager=".$item->manager."&Question=All"))
															<div class="row">
																<div class="col-sm-6">
																	<a target="_blank" href="{{ $link }}" class="survey-link" style="background: {{ $survey_bg[$js['random_id']] }}">{{ $js['name'].'-'.$label_survey }}</a>
																</div>
																@if ($js['express'] == 1)
																	@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['3']."&Version=".$type_survey."&Job=".$job_number."&Random=1&Manager=".$item->manager."&Question=All"))
																	<div class="col-sm-6">
																		<a target="_blank" href="{{ $link }}" class="survey-link">{{ $js['name'].'-Express' }}</a>
																	</div>
																@endif
															</div>
														@endforeach
													</div>
													<div class="question_list_wrapper">
														<div class="row">
															@foreach ($industry_question['Education'] as $health_ques)
																<div class="col-sm-4 col-xs-12">
																	<div class="question-name-div" data-job="{{ $job_number }}" data-industry="Education" data-manager="{{ $item->manager }}">{{ $health_ques }}</div>
																</div>
															@endforeach
														</div>
													</div>
													@endif
												</div>
											</div>
										</div>
									</div>
								@empty
									<div class="quality-item">
										<h4>Education jobs not assigned.</h4>
									</div>
								@endforelse


								<div class="row quality-item">
									<br/>
									<div class="col-md-12">
										<div class="evaluation-heading">
											<span class="name-employee">Commercial</span>
										</div>
									</div>
									<br/>
								</div>
								@forelse ($job_list_commercial as $item)
									@php ($accordion_num += 1)
									@php ($job_number = $item->job_number)
									<div class="panel-group" id="accordion{{ $accordion_num }}" role="tablist" aria-multiselectable="true">
										<div class="panel panel-default">
											<div class="panel-heading" role="tab" id="headingOne">
												<h4 class="panel-title">
													<div class="pull-left">#{{$job_number}} - {{$item->job_description}}</div>
													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-success pull-right m-l-10 job-surveys-list">Surveys List</a>

													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-primary pull-right job-questions-list">Questions List</a>
													<div class="clearfix"></div>
												</h4>
											</div>
											<div id="collapse{{ $accordion_num }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
												<div class="panel-body">
													@if (isset($job_survey->$job_number))
													<div class="survey_list_wrapper">
														@foreach ($job_survey->$job_number as $js)
															@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['4']."&Version=".$type_survey."&Job=".$job_number."&Random=0&Manager=".$item->manager."&Question=All"))
															<div class="row">
																<div class="col-sm-6">
																	<a target="_blank" href="{{ $link }}" class="survey-link" style="background: {{ $survey_bg[$js['random_id']] }}">{{ $js['name'].'-'.$label_survey }}</a>
																</div>
																@if ($js['express'] == 1)
																	@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['4']."&Version=".$type_survey."&Job=".$job_number."&Random=1&Manager=".$item->manager."&Question=All"))
																	<div class="col-sm-6">
																		<a target="_blank" href="{{ $link }}" class="survey-link">{{ $js['name'].'-Express' }}</a>
																	</div>
																@endif
															</div>
														@endforeach
													</div>
													<div class="question_list_wrapper">
														<div class="row">
															@foreach ($industry_question['Commercial'] as $health_ques)
																<div class="col-sm-4 col-xs-12">
																	<div class="question-name-div" data-job="{{ $job_number }}" data-industry="Commercial" data-manager="{{ $item->manager }}">{{ $health_ques }}</div>
																</div>
															@endforeach
														</div>
													</div>
													@endif
												</div>
											</div>
										</div>
									</div>
								@empty
									<div class="quality-item">
										<h4>Commercial jobs not assigned.</h4>
									</div>
								@endforelse

								<div class="row quality-item">
									<br/>
									<div class="col-md-12">
										<div class="evaluation-heading">
											<span class="name-employee">Hospitality</span>
										</div>
									</div>
									<br/>
								</div>
								@forelse ($job_list_hospitality as $item)
									@php ($accordion_num += 1)
									@php ($job_number = $item->job_number)
									<div class="panel-group" id="accordion{{ $accordion_num }}" role="tablist" aria-multiselectable="true">
										<div class="panel panel-default">
											<div class="panel-heading" role="tab" id="headingOne">
												<h4 class="panel-title">
													<div class="pull-left">#{{$job_number}} - {{$item->job_description}}</div>
													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-success pull-right m-l-10 job-surveys-list">Surveys List</a>

													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-primary pull-right job-questions-list">Questions List</a>
													<div class="clearfix"></div>
												</h4>
											</div>
											<div id="collapse{{ $accordion_num }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
												<div class="panel-body">
													@if (isset($job_survey->$job_number))
													<div class="survey_list_wrapper">
														@foreach ($job_survey->$job_number as $js)
															@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['5']."&Version=".$type_survey."&Job=".$job_number."&Random=0&Manager=".$item->manager."&Question=All"))
															<div class="row">
																<div class="col-sm-6">
																	<a target="_blank" href="{{ $link }}" class="survey-link" style="background: {{ $survey_bg[$js['random_id']] }}">{{ $js['name'].'-'.$label_survey }}</a>
																</div>
																@if ($js['express'] == 1)
																	@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['5']."&Version=".$type_survey."&Job=".$job_number."&Random=1&Manager=".$item->manager."&Question=All"))
																	<div class="col-sm-6">
																		<a target="_blank" href="{{ $link }}" class="survey-link">{{ $js['name'].'-Express' }}</a>
																	</div>
																@endif
															</div>
														@endforeach
													</div>
													<div class="question_list_wrapper">
														<div class="row">
															@foreach ($industry_question['Hospitality'] as $health_ques)
																<div class="col-sm-4 col-xs-12">
																	<div class="question-name-div" data-job="{{ $job_number }}" data-industry="Hospitality" data-manager="{{ $item->manager }}">{{ $health_ques }}</div>
																</div>
															@endforeach
														</div>
													</div>
													@endif
												</div>
											</div>
										</div>
									</div>
								@empty
									<div class="quality-item">
										<h4>Hospitality jobs not assigned.</h4>
									</div>
								@endforelse

								<div class="row quality-item">
									<br/>
									<div class="col-md-12">
										<div class="evaluation-heading">
											<span class="name-employee">Government</span>
										</div>
									</div>
									<br/>
								</div>
								@forelse ($job_list_government as $item)
									@php ($accordion_num += 1)
									@php ($job_number = $item->job_number)
									<div class="panel-group" id="accordion{{ $accordion_num }}" role="tablist" aria-multiselectable="true">
										<div class="panel panel-default">
											<div class="panel-heading" role="tab" id="headingOne">
												<h4 class="panel-title">
													<div class="pull-left">#{{$job_number}} - {{$item->job_description}}</div>
													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-success pull-right m-l-10 job-surveys-list">Surveys List</a>

													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-primary pull-right job-questions-list">Questions List</a>
													<div class="clearfix"></div>
												</h4>
											</div>
											<div id="collapse{{ $accordion_num }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
												<div class="panel-body">
													@if (isset($job_survey->$job_number))
													<div class="survey_list_wrapper">
														@foreach ($job_survey->$job_number as $js)
															@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['6']."&Version=".$type_survey."&Job=".$job_number."&Random=0&Manager=".$item->manager."&Question=All"))
															<div class="row">
																<div class="col-sm-6">
																	<a target="_blank" href="{{ $link }}" class="survey-link" style="background: {{ $survey_bg[$js['random_id']] }}">{{ $js['name'].'-'.$label_survey }}</a>
																</div>
																@if ($js['express'] == 1)
																	@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['6']."&Version=".$type_survey."&Job=".$job_number."&Random=1&Manager=".$item->manager."&Question=All"))
																	<div class="col-sm-6">
																		<a target="_blank" href="{{ $link }}" class="survey-link">{{ $js['name'].'-Express' }}</a>
																	</div>
																@endif
															</div>
														@endforeach
													</div>
													<div class="question_list_wrapper">
														<div class="row">
															@foreach ($industry_question['Government'] as $health_ques)
																<div class="col-sm-4 col-xs-12">
																	<div class="question-name-div" data-job="{{ $job_number }}" data-industry="Government" data-manager="{{ $item->manager }}">{{ $health_ques }}</div>
																</div>
															@endforeach
														</div>
													</div>
													@endif
												</div>
											</div>
										</div>
									</div>
								@empty
									<div class="quality-item">
										<h4>Government jobs not assigned.</h4>
									</div>
								@endforelse

								<div class="row quality-item">
									<br/>
									<div class="col-md-12">
										<div class="evaluation-heading">
											<span class="name-employee">Public Venue</span>
										</div>
									</div>
									<br/>
								</div>
								@forelse ($job_list_publicvenue as $item)
									@php ($accordion_num += 1)
									@php ($job_number = $item->job_number)
									<div class="panel-group" id="accordion{{ $accordion_num }}" role="tablist" aria-multiselectable="true">
										<div class="panel panel-default">
											<div class="panel-heading" role="tab" id="headingOne">
												<h4 class="panel-title">
													<div class="pull-left">#{{$job_number}} - {{$item->job_description}}</div>
													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-success pull-right m-l-10 job-surveys-list">Surveys List</a>

													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-primary pull-right job-questions-list">Questions List</a>
													<div class="clearfix"></div>
												</h4>
											</div>
											<div id="collapse{{ $accordion_num }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
												<div class="panel-body">
													@if (isset($job_survey->$job_number))
													<div class="survey_list_wrapper">
														@foreach ($job_survey->$job_number as $js)
															@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['7']."&Version=".$type_survey."&Job=".$job_number."&Random=0&Manager=".$item->manager."&Question=All"))
															<div class="row">
																<div class="col-sm-6">
																	<a target="_blank" href="{{ $link }}" class="survey-link" style="background: {{ $survey_bg[$js['random_id']] }}">{{ $js['name'].'-'.$label_survey }}</a>
																</div>
																@if ($js['express'] == 1)
																	@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['7']."&Version=".$type_survey."&Job=".$job_number."&Random=1&Manager=".$item->manager."&Question=All"))
																	<div class="col-sm-6">
																		<a target="_blank" href="{{ $link }}" class="survey-link">{{ $js['name'].'-Express' }}</a>
																	</div>
																@endif
															</div>
														@endforeach
													</div>
													<div class="question_list_wrapper">
														<div class="row">
															@foreach ($industry_question['PublicVenues'] as $health_ques)
																<div class="col-sm-4 col-xs-12">
																	<div class="question-name-div" data-job="{{ $job_number }}" data-industry="PublicVenues" data-manager="{{ $item->manager }}">{{ $health_ques }}</div>
																</div>
															@endforeach
														</div>
													</div>
													@endif
												</div>
											</div>
										</div>
									</div>
								@empty
									<div class="quality-item">
										<h4>Public Venues jobs not assigned.</h4>
									</div>
								@endforelse

								<div class="row quality-item">
									<br/>
									<div class="col-md-12">
										<div class="evaluation-heading">
											<span class="name-employee">Retail</span>
										</div>
									</div>
									<br/>
								</div>

								@forelse ($job_list_retail as $item)
									@php ($accordion_num += 1)
									@php ($job_number = $item->job_number)
									<div class="panel-group" id="accordion{{ $accordion_num }}" role="tablist" aria-multiselectable="true">
										<div class="panel panel-default custom-panel">
											<div class="panel-heading" role="tab" id="headingOne">
												<h4 class="panel-title">
													<div class="pull-left title-text">#{{$job_number}} - {{$item->job_description}}</div>
													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-success pull-right m-l-10 job-surveys-list">Surveys List</a>

													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-primary pull-right job-questions-list">Questions List</a>
													<div class="clearfix"></div>
												</h4>
											</div>
											<div id="collapse{{ $accordion_num }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
												<div class="panel-body">
													@if (isset($job_survey->$job_number))
													<div class="survey_list_wrapper">
														@foreach ($job_survey->$job_number as $js)
															@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['8']."&Version=".$type_survey."&Job=".$job_number."&Random=0&Manager=".$item->manager."&Question=All"))
															<div class="row">
																<div class="col-sm-6">
																	<a target="_blank" href="{{ $link }}" class="survey-link" style="background: {{ $survey_bg[$js['random_id']] }}">{{ $js['name'].'-'.$label_survey }}</a>
																</div>
																@if ($js['express'] == 1)
																	@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['8']."&Version=".$type_survey."&Job=".$job_number."&Random=1&Manager=".$item->manager."&Question=All"))
																	<div class="col-sm-6">
																		<a target="_blank" href="{{ $link }}" class="survey-link">{{ $js['name'].'-Express' }}</a>
																	</div>
																@endif
															</div>
														@endforeach
													</div>
													<div class="question_list_wrapper">
														<div class="row">
															@foreach ($industry_question['Retail'] as $health_ques)
																<div class="col-sm-4 col-xs-12">
																	<div class="question-name-div" data-job="{{ $job_number }}" data-industry="Retail" data-manager="{{ $item->manager }}">{{ $health_ques }}</div>
																</div>
															@endforeach
														</div>
													</div>
													@endif
												</div>
											</div>
										</div>
									</div>
								@empty
									<div class="quality-item">
										<h4>Retail jobs not assigned.</h4>
									</div>
								@endforelse

								<div class="row quality-item">
									<br/>
									<div class="col-md-12">
										<div class="evaluation-heading">
											<span class="name-employee">Industrial</span>
										</div>
									</div>
									<br/>
								</div>
								@forelse ($job_list_industrial as $item)
									@php ($accordion_num += 1)
									@php ($job_number = $item->job_number)
									<div class="panel-group" id="accordion{{ $accordion_num }}" role="tablist" aria-multiselectable="true">
										<div class="panel panel-default">
											<div class="panel-heading" role="tab" id="headingOne">
												<h4 class="panel-title">
													<div class="pull-left">#{{$job_number}} - {{$item->job_description}}</div>
													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-success pull-right m-l-10 job-surveys-list">Surveys List</a>

													<a role="button" data-toggle="collapse" data-parent="#accordion{{ $accordion_num }}" href="#collapse{{ $accordion_num }}" aria-expanded="true" aria-controls="collapse{{ $accordion_num }}"  class="btn btn-primary pull-right job-questions-list">Questions List</a>
													<div class="clearfix"></div>
												</h4>
											</div>
											<div id="collapse{{ $accordion_num }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
												<div class="panel-body">
													@if (isset($job_survey->$job_number))
													<div class="survey_list_wrapper">
														@foreach ($job_survey->$job_number as $js)
															@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['9']."&Version=".$type_survey."&Job=".$job_number."&Random=0&Manager=".$item->manager."&Question=All"))
															<div class="row">
																<div class="col-sm-6">
																	<a target="_blank" href="{{ $link }}" class="survey-link" style="background: {{ $survey_bg[$js['random_id']] }}">{{ $js['name'].'-'.$label_survey }}</a>
																</div>
																@if ($js['express'] == 1)
																	@php ($link = $survey_link.$js['random_id']."?data=".Crypt::encrypt("Industry=".$industry_list['9']."&Version=".$type_survey."&Job=".$job_number."&Random=1&Manager=".$item->manager."&Question=All"))
																	<div class="col-sm-6">
																		<a target="_blank" href="{{ $link }}" class="survey-link">{{ $js['name'].'-Express' }}</a>
																	</div>
																@endif
															</div>
														@endforeach
													</div>
													<div class="question_list_wrapper">
														<div class="row">
															@foreach ($industry_question['Industrial'] as $health_ques)
																<div class="col-sm-4 col-xs-12">
																	<div class="question-name-div" data-job="{{ $job_number }}" data-industry="Industrial" data-manager="{{ $item->manager }}">{{ $health_ques }}</div>
																</div>
															@endforeach
														</div>
													</div>
													@endif
												</div>
											</div>
										</div>
									</div>
								@empty
									<div class="quality-item">
										<h4>Industrial jobs not assigned.</h4>
									</div>
								@endforelse

							</div><!-- /wrapper -->
						</div>		
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
	jQuery(window).load(function () {
		$('.piluku-preloader').addClass('hidden');
	});
</script>

<script type="text/javascript" src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/wow.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.loadmask.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.accordion.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/materialize.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/build/d3.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/nvd3/nv.d3.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/sparkline.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/bic_calendar.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/widgets.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.countTo.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/js/app.quality.js') }}"></script>

<script>
function toggle_dropdown(element) {
	element.siblings('.job-dropdown').toggleClass("show");
}

function q_toggle_dropdown(element) {
	element.siblings('.questions-dropdown').toggleClass("show");
}

function survey_filter_func(element) {
	var filter, ul, li, a, i;
	filter = element.val().toUpperCase();
	a = element.parents("ul").find(".survey-link");
	for (i = 0; i < a.length; i++)
		if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1)
			a[i].style.display = "";
		else
			a[i].style.display = "none";
}

function job_surveys(job_number) {
	var job_surveys = {!! json_encode($job_survey) !!}

	return job_surveys[job_number];
}
</script>
@endsection