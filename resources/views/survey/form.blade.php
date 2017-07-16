@extends('layouts.default')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sidebar-style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/questions.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/survey.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/use_less.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/survey-form.css') }}">
<style type="text/css">
	#content {
		margin-left: initial;
	}
	.modal-content {
		padding-right: 15px;
	}
	.main-content {
		padding: 10px !important;
	}
	#import-ques-btn {
		margin-top: 0;
	}
	#import-ques-btn:hover {
		background: #eee;
	}
	canvas {
		box-shadow: 0 0 1px 1px #ccc;
	}
	.signature-flow div {
		text-align: initial;
		width: 400px;
		margin: auto;
	}
	.back {
		cursor: pointer;
		font-size: 18px;
	}
	.back:hover {
		text-decoration: underline;
	}

	#images-preview span {
		display: inline-block; 
	}
</style>
@endsection

@section('content')
	@include('survey.form-images')
	@include('survey.add-question')
	@include('survey.preview-question')
	<div class="piluku-preloader text-center">
		<div class="loader">Loading...</div>
	</div>
	<div class="wrapper ">
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
									<div class="col-sm-2">
										<div class="back hidden">Back</div>
										<div class="relative-container">
											<div>
												<a href="#" id="navShow" class="sidebar-open">
													<div class="bar1"></div>
													<div class="bar2"></div>
													<div class="bar3"></div>
												</a>
											</div>
											<div id="navigation-box" class="navigation-box">
												<a href="#" id="navHide" class="sidebar-close">
													<span class="nav-close-icon"></span>
												</a>
												<div class="clear"></div>
												<div>
													<div class="navigation-heading">Survey Content</div>
													<ul class="navigation-menu">
														@for ($i = 0; $i < count($questions_list); $i++)
															@if ($questions_list[$i]['status'] == 'ended')
																<li class="sidenav-link question-navigation bg-green" data-index="{{ $i }}"><a href="#">{{ $questions_list[$i]['name'] }}</a></li>
															@elseif ($questions_list[$i]['status'] == 'pending')
																<li class="sidenav-link question-navigation bg-red" data-index="{{ $i }}"><a href="#">{{ $questions_list[$i]['name'] }}</a></li>
															@else
																<li class="sidenav-link question-navigation bg-orange" data-index="{{ $i }}"><a href="#">{{ $questions_list[$i]['name'] }}</a></li>
															@endif
														@endfor
														<li>
															<a href="#" id="import-ques-btn">
																<i class="fa fa-plus" aria-hidden="true"></i> Add Question
															</a>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-8">
										<div class="question">
											<div class="question-flow-outer">
												@if ($question['matrix']->flow == 'true')
													<div class="matrix-flow">
														<h3>{{ $question['matrix']->label }}</h3>
														<table class="table table-hover">
															<thead>
																<tr>
																	<th> </th>
																	@foreach ($scores as $score)
																		<th>{{ $score }}</th>
																	@endforeach
																</tr>
															</thead>
															<tbody>
																@foreach ($question['matrix']->options as $option)
																	@php
																		$option_details = $matrix_options->$option;
																	@endphp
																	<tr>
																		<td class="option">{{ $option_details['en_option'] }}</td>
																		@foreach ($scores as $score)
																			@if (isset($current_question['matrix']) && $score == $current_question['matrix']->$option_details['en_option'])
																				<td>
																					<input type="radio" name="{{ str_replace(' ', '-', $option_details['en_option']) }}" id="{{ 'radio-'.$score.$option }}" value="{{ $score }}" checked>
																					<label for="{{ 'radio-'.$score.$option }}"><span></span></label>
																				</td>
																			@else
																				<td>
																					<input type="radio" name="{{ str_replace(' ', '-', $option_details['en_option']) }}" id="{{ 'radio-'.$score.$option }}" value="{{ $score }}">
																					<label for="{{ 'radio-'.$score.$option }}"><span></span></label>
																				</td>
																			@endif
																		@endforeach
																	</tr>
																@endforeach
															</tbody>
														</table>
													</div>
													<hr class="flow-separator">
												@endif

												@if ($question['image']->flow == 'true')
													<div class="image-flow">
														<h3>{{ $question['image']->label }}</h3>
														<input type="file" class="hidden" name="files[]" id="files" multiple>
														<p class="img-upload-btn" class="text-center">Click here to upload image</p>
														@if (!isset($current_question['images']))
															<div id="images-preview">
															</div>
															<input type="button" class="hidden btn btn-default" value="Update Comments" name="preview-btn">
														@else
															<div id="images-preview">
																@foreach ($current_question['images'] as $image)
																	@foreach ($image as $path => $comments)
																		<span>
																			<img class="thumb" src="{{ url('assets\survey-images\original\\'.$path) }}">
																			<p>{{ substr($comments, 0, 20) }}</p>
																		</span>
																	@endforeach
																@endforeach
															</div>
															<input type="button" class="btn btn-default" value="Update Comments" name="preview-btn">
														@endif
													</div>
													<hr class="flow-separator">
												@endif

												@if ($question['comment']->flow == 'true')
													<div class="comment-flow">
														<h3>{{ $question['comment']->label }}</h3>
														@php
															$comments = isset($current_question['comments']) ? $current_question['comments'] : '' ;
														@endphp
														<textarea class="form-control" rows="2" placeholder="Write you comments here">{{ $comments }}</textarea>
													</div>
													<hr class="flow-separator">
												@endif

												<div class="rating-flow">
													<h3>APPA Levels (Select One)</h3>
													@for ($i = 1; $i <= 5; $i++)
														@if (isset($current_question['ratings']) && $current_question['ratings'] == $i)
															<input type="radio" name="rating-level" id="{{ 'rating-level'.$i }}" value="{{ $i }}" checked>
														@else
															<input type="radio" name="rating-level" id="{{ 'rating-level'.$i }}" value="{{ $i }}">
														@endif
														<label for="{{ 'rating-level'.$i }}"><span></span></label>
														<span class="level">{{ $i }}</span>
													@endfor
												</div>
												<hr class="flow-separator">

												<div class="signature-flow text-center hidden">
													<h3 align="center">Signature</h3>
													<div>
														<a href="#colors_sketch" data-tool="marker">Marker</a>
														<a href="#colors_sketch" data-tool="eraser">Eraser</a>
													</div>
													<canvas id="colors_sketch" width="400" height="150"></canvas>
												</div>

												<div class="page-navigation-container">
													<div class="row">
														<div class="col-sm-6 col-xs-6">
															@if ($question['index'] != 0)
																<input type="button" class="btn btn-default question-navigation previous-ques" value="<<" data-index="{{ $question['index'] - 1 }}">
															@endif
														</div>
														<div class="col-sm-6 col-xs-6 text-right">
															<input type="button" class="btn btn-default question-navigation next-ques" value=">>" data-index="{{ $question['index'] + 1 }}">
														</div>
														<div class="col-sm-12 text-center hidden">
															<input type="button" class="btn btn-default complete-survey" name="complete-survey" value="Submit">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<select class="lang">
											<option value="en" selected>EN</option>
											<option value="es">ES</option>
										</select>
									</div>
								</div>
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

	<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

	<script type="text/javascript" src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/sketch.js-master/js/sketch.min.js') }}"></script>

	<script type="text/javascript" src="{{ asset('assets/js/app.survey-single.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/app.survey-form.js') }}"></script>

	<script type="text/javascript">
		function options_data(options) {
			var options_data = {!! $options !!};
			var data = {};
			for (var i = 0; i < options.length; i++)
				data[options[i]] = options_data[options[i]];
			return data;
		}

		function es_to_en_options(option) {
			var data = {!! json_encode($es_to_en_options) !!};

			return data[option];
		}

		function en_to_es_options(option) {
			var data = {!! json_encode($en_to_es_options) !!};

			return data[option];
		}

		function question() {
			return {!! json_encode($question) !!};
		}

		function es_to_en_ques(ques) {
			var data = {!! json_encode($es_to_en_ques) !!};

			return data[ques];
		}

		function en_to_es_ques(ques) {
			var data = {!! json_encode($en_to_es_ques) !!};

			return data[ques];
		}
	</script>
@endsection