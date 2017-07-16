<div class="modal fade" tabindex="-1" role="dialog" id="images" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Comments for uploaded images</h4>
			</div>
			<div class="modal-body" id="modal-images-preview">
				@if (isset($current_question['images']))
					@foreach ($current_question['images'] as $image)
						@foreach ($image as $path => $comments)
							<div>
								<div class="row">
									<div class="col-sm-3">
										<img src="{{ url('assets\survey-images\original\\'.$path) }}" title="{{ $path }}">
									</div>
									<div class="col-sm-9">
										<textarea class="form-control img-desc" placeholder="Comments" data-image="{{ $path }}">{{ $comments }}</textarea>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-sm-12">
									</div>
								</div>
							</div>
						@endforeach
					@endforeach
				@endif
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel-image-upload">Cancel</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
			</div>
		</div>
	</div>
</div>