<div class="modal fade" tabindex="-1" role="dialog" id="edit-survey-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Edit Survey</h4>
			</div>
			<div class="modal-body">
				<form method="POST" id="edit-survey-form" enctype="multipart/form-data" class="f-c-5">
					<div class="row">
						<div class="col-sm-12">
							<input class="form-control" id="edit-survey-name" type="text" placeholder="* Survey Name" value="{{ $survey_data->name }}">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<textarea class="form-control" id="edit-survey-description" rows="2" placeholder="* Survey Description">{{ $survey_data->description }}</textarea>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<select class="form-control" id="edit-survey-type">
								@if ($survey_data->type == 'Quality')
									<option disabled value="">Survey Type</option>
									<option selected>Quality</option>
									<option>Task List</option>
								@else
									<option disabled value="">Survey Type</option>
									<option>Quality</option>
									<option selected>Task List</option>
								@endif
							</select>
						</div>
					</div>
					@if ($survey_data->type == 'Quality')
						<div class="row">
							<div class="col-sm-12">
								@if ($survey_data->has_express == 1)
									<input type="checkbox" id="edit-include-express" name="edit-include-express" checked="checked">
								@else
									<input type="checkbox" id="edit-include-express" name="edit-include-express">
								@endif
								<label for="edit-include-express"><span></span>Include Express</label>
							</div>
						</div>
					@else
						<div class="row hidden">
							<div class="col-sm-12">
								<input type="checkbox" id="edit-include-express" name="edit-include-express">
								<label for="edit-include-express"><span></span>Include Express</label>
							</div>
						</div>
					@endif
					<div class="row">
						<div class="col-md-12">
							<select id="edit-survey-jobs" class="form-control" multiple>
								<optgroup label="All" ><b>All</b></optgroup>
								<optgroup label="Healthcare">
								@foreach ($health_jobs as $job)
									@if (in_array($job->job_number, $survey_jobs))
										<option value="{{ $job->job_number}}" selected> #{{$job->job_number}} - {{$job->job_description}}</option>
									@else
										<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
									@endif
								@endforeach
								</optgroup>
								<optgroup label="Education">
								@foreach ($education_jobs as $job)
									@if (in_array($job->job_number, $survey_jobs))
										<option value="{{ $job->job_number}}" selected> #{{$job->job_number}} - {{$job->job_description}}</option>
									@else
										<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
									@endif
								@endforeach
								</optgroup>
								<optgroup label="Commercial">
								@foreach ($commercial_jobs as $job)
									@if (in_array($job->job_number, $survey_jobs))
										<option value="{{ $job->job_number}}" selected> #{{$job->job_number}} - {{$job->job_description}}</option>
									@else
										<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
									@endif
								@endforeach
								</optgroup>
								<optgroup label="Hospitality">
								@foreach ($hospitality_jobs as $job)
									@if (in_array($job->job_number, $survey_jobs))
										<option value="{{ $job->job_number}}" selected> #{{$job->job_number}} - {{$job->job_description}}</option>
									@else
										<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
									@endif
								@endforeach
								</optgroup>
								<optgroup label="Government">
								@foreach ($government_jobs as $job)
									@if (in_array($job->job_number, $survey_jobs))
										<option value="{{ $job->job_number}}" selected> #{{$job->job_number}} - {{$job->job_description}}</option>
									@else
										<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
									@endif
								@endforeach
								</optgroup>
								<optgroup label="Public Venue">
								@foreach ($publicvenue_jobs as $job)
									@if (in_array($job->job_number, $survey_jobs))
										<option value="{{ $job->job_number}}" selected> #{{$job->job_number}} - {{$job->job_description}}</option>
									@else
										<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
									@endif
								@endforeach
								</optgroup>
								<optgroup label="Retail">
								@foreach ($retail_jobs as $job)
									@if (in_array($job->job_number, $survey_jobs))
										<option value="{{ $job->job_number}}" selected> #{{$job->job_number}} - {{$job->job_description}}</option>
									@else
										<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
									@endif
								@endforeach
								</optgroup>
								<optgroup label="Industrial">
								@foreach ($industrial_jobs as $job)
									@if (in_array($job->job_number, $survey_jobs))
										<option value="{{ $job->job_number}}" selected> #{{$job->job_number}} - {{$job->job_description}}</option>
									@else
										<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
									@endif
								@endforeach
								</optgroup>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<label for="survey-btn-color">Select survey button color: </label>
							<input type="color" id="survey-btn-color" value="{{ $survey_data->bgcolor }}">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="edit-survey-btn">Edit Survey</button>
			</div>
		</div>
	</div>
</div>