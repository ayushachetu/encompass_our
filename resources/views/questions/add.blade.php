<div class="modal fade" tabindex="-1" role="dialog" id="add-modal">
	<div class="modal-dialog" role="document" style="width: 99%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">New Question (Nueva Pregunta)</h4>
			</div>
			<div class="modal-body">
				<form method="POST" id="add-q-form" enctype="multipart/form-data" class="f-c-5">
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							<div id="en-form-container">
								<div class="row">
									<div class="col-sm-12">
										<input class="form-control" id="add-q-name" type="text" placeholder="* Question Name">
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<input type="checkbox" id="add-q-matrix" name="add-q-flows[]" value="matrix">
										<label for="add-q-matrix" class="f-16"><span></span>Matrix</label>
									</div>
								</div>
								<div class="row p-t-0">
									<div class="col-sm-2">
										<span>Label</span>
									</div>
									<div class="col-sm-10 sm-p-l-0">
										<input type="text" id="add-q-matrix-label" class="form-control h-30" placeholder="Name">
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 sm-p-l-25">
										<select class="form-control" multiple="multiple" id="add-q-matrix-options">
											@foreach ($matrix_options as $id => $option)
												<option value="{{ $option['id'] }}">{{ $option['en_option'] }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<input type="checkbox" id="add-q-image" name="add-q-flows[]" value="image">
										<label for="add-q-image" class="f-16"><span></span>Image</label>
									</div>
								</div>
								<div class="row p-t-0">
									<div class="col-sm-2">
										<span>Label</span>
									</div>
									<div class="col-sm-10 sm-p-l-0">
										<input type="text" id="add-q-image-label" class="form-control h-30" placeholder="Default Image">
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<input type="checkbox" id="add-q-comment" name="add-q-flows[]" value="comment">
										<label for="add-q-comment" class="f-16"><span></span>Comment</label>
									</div>
								</div>
								<div class="row p-t-0">
									<div class="col-sm-2">
										<span>Label</span>
									</div>
									<div class="col-sm-10 sm-p-l-0">
										<input type="text" id="add-q-comment-label" class="form-control h-30" placeholder="Default Comments">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6" id="es-form-outer-container">
							<div id="es-form-container">
								<div class="row">
									<div class="col-sm-12">
										<input class="form-control" id="es-add-q-name" type="text" placeholder="* Nombre de la pregunta">
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<input type="checkbox" id="es-add-q-matrix" name="es-add-q-flows[]" value="matrix">
										<label for="es-add-q-matrix" class="f-16"><span></span>Matriz</label>
									</div>
								</div>
								<div class="row p-t-0">
									<div class="col-sm-2">
										<span>Etiqueta</span>
									</div>
									<div class="col-sm-10 sm-p-l-0">
										<input type="text" id="es-add-q-matrix-label" class="form-control h-30" placeholder="Nombre">
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 sm-p-l-25">
										<select class="form-control" multiple="multiple" id="es-add-q-matrix-options">
											@foreach ($matrix_options as $id => $option)
												<option value="{{ $option['id'] }}">{{ $option['es_option'] }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<input type="checkbox" id="es-add-q-image" name="es-add-q-flows[]" value="image">
										<label for="es-add-q-image" class="f-16"><span></span>Imagen</label>
									</div>
								</div>
								<div class="row p-t-0">
									<div class="col-sm-2">
										<span>Etiqueta</span>
									</div>
									<div class="col-sm-10 sm-p-l-0">
										<input type="text" id="es-add-q-image-label" class="form-control h-30" placeholder="Imagen predeterminada">
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<input type="checkbox" id="es-add-q-comment" name="es-add-q-flows[]" value="comment">
										<label for="es-add-q-comment" class="f-16"><span></span>Comentario</label>
									</div>
								</div>
								<div class="row p-t-0">
									<div class="col-sm-2">
										<span>Etiqueta</span>
									</div>
									<div class="col-sm-10 sm-p-l-0">
										<input type="text" id="es-add-q-comment-label" class="form-control h-30" placeholder="Predeterminado">
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="add-ques-btn">Add Question (Añadir pregunta)</button>
			</div>
		</div>
	</div>
</div>