<div class="modal fade" tabindex="-1" role="dialog" id="add-survey-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">New Survey</h4>
			</div>
			<div class="modal-body">
				<form method="POST" id="add-survey-form" enctype="multipart/form-data" class="f-c-5">
					<div class="row">
						<div class="col-sm-12">
							<input class="form-control" id="survey-name" type="text" placeholder="* Survey Name">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<textarea class="form-control" id="survey-description" rows="2" placeholder="* Survey Description"></textarea>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<select class="form-control" id="survey-type">
								<option disabled selected value="">* Survey Type</option>
								<option>Quality</option>
								<option>Task List</option>
							</select>
						</div>
					</div>
					<div class="row hidden">
						<div class="col-sm-12">
							<input type="checkbox" id="include-express" name="include-express">
							<label for="include-express"><span></span>Include Express</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<select id="survey-jobs" class="form-control" multiple>
								<optgroup label="All" ><b>All</b></optgroup>
								<optgroup label="Healthcare">
								@foreach ($health_jobs as $job)
									<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
								@endforeach
								</optgroup>
								<optgroup label="Education">
								@foreach ($education_jobs as $job)
									<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
								@endforeach
								</optgroup>
								<optgroup label="Commercial">
								@foreach ($commercial_jobs as $job)
									<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
								@endforeach
								</optgroup>
								<optgroup label="Hospitality">
								@foreach ($hospitality_jobs as $job)
									<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
								@endforeach
								</optgroup>
								<optgroup label="Government">
								@foreach ($government_jobs as $job)
									<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
								@endforeach
								</optgroup>
								<optgroup label="Public Venue">
								@foreach ($publicvenue_jobs as $job)
									<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
								@endforeach
								</optgroup>
								<optgroup label="Retail">
								@foreach ($retail_jobs as $job)
									<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
								@endforeach
								</optgroup>
								<optgroup label="Industrial">
								@foreach ($industrial_jobs as $job)
									<option value="{{ $job->job_number}}"> #{{$job->job_number}} - {{$job->job_description}}</option>
								@endforeach
								</optgroup>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<label for="survey-btn-color">Select survey button color: </label>
							<input type="color" id="survey-btn-color" value="#449d44">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="create-survey-btn">Create Survey</button>
			</div>
		</div>
	</div>
</div>