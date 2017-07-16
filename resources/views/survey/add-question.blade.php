<div class="modal fade" tabindex="-1" role="dialog" id="add-question-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Questions</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<select class="form-control" id="filter-industry-question">
							<option disabled selected value="">Select Industry</option>
							<option>All</option>
							@foreach ($industries as $industry)
								<option value="{{ $industry->id }}">{{ $industry->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<table class="table table-hover" id="question-list-table">
							<thead>
								<th></th>
								<th></th>
							</thead>
							<tbody>
								@foreach ($questions as $question)
									<tr>
										<td>
											<input type="checkbox" id="{{ $question->id }}" name="questions-to-import[]" value="{{ $question->id }}">
											<label for="{{ $question->id }}" class="f-16"><span></span> {{ $question->name }}</label>
										</td>
										<td q-data="{{ json_encode($question) }}">
											<input type="button" class="btn btn-sm btn-primary preview-survey-ques" value="Preview">
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="add-questions-btn">Add Questions</button>
			</div>
		</div>
	</div>
</div>