$(document).ready(function () {
	$('#edit-q-matrix-options').select2({
		placeholder: "Select Options",
		dropdownParent: $("#edit-modal")
	});

	$('#es-edit-q-matrix-options').select2({
		placeholder: "Seleccione opciones",
		dropdownParent: $("#edit-modal")
	});

	$('#edit-q-matrix').change(function () {
		if ($(this).is(':checked'))
			$('#es-edit-q-matrix').prop('checked', true);
		else
			$('#es-edit-q-matrix').prop('checked', false);
	});

	$('#es-edit-q-matrix').change(function () {
		if ($(this).is(':checked'))
			$('#edit-q-matrix').prop('checked', true);
		else
			$('#edit-q-matrix').prop('checked', false);
	});

	$('#edit-q-image').change(function () {
		if ($(this).is(':checked'))
			$('#es-edit-q-image').prop('checked', true);
		else
			$('#es-edit-q-image').prop('checked', false);
	});

	$('#es-edit-q-image').change(function () {
		if ($(this).is(':checked'))
			$('#edit-q-image').prop('checked', true);
		else
			$('#edit-q-image').prop('checked', false);
	});

	$('#edit-q-comment').change(function () {
		if ($(this).is(':checked'))
			$('#es-edit-q-comment').prop('checked', true);
		else
			$('#es-edit-q-comment').prop('checked', false);
	});

	$('#es-edit-q-comment').change(function () {
		if ($(this).is(':checked'))
			$('#edit-q-comment').prop('checked', true);
		else
			$('#edit-q-comment').prop('checked', false);
	});

	$('#edit-q-matrix-options').change(function () {
		var en_options = $('#edit-q-matrix-options').val();
		var current_es_options = $('#es-edit-q-matrix-options').val();
		if (en_options === null && current_es_options === null)
			return;
		var new_es_options = [];
		for (var index in en_options)
			new_es_options.push(en_options[index]);
		var is_same = (current_es_options === null) ? false : (new_es_options.length == current_es_options.length) && new_es_options.every(function (element, index) {
			return element === current_es_options[index];
		});
		if (!is_same)
			$('#es-edit-q-matrix-options').val(new_es_options).trigger("change");
	});

	$('#es-edit-q-matrix-options').change(function () {
		var es_options = $('#es-edit-q-matrix-options').val();
		var current_en_options = $('#edit-q-matrix-options').val();
		if (es_options === null && current_en_options === null)
			return;
		var new_en_options = [];
		for (var index in es_options)
			new_en_options.push(es_options[index]);
		var is_same = (current_en_options === null) ? false : (new_en_options.length == current_en_options.length) && new_en_options.every(function (element, index) {
			return element === current_en_options[index];
		});
		if (!is_same)
			$('#edit-q-matrix-options').val(new_en_options).trigger("change");
	});

	$('#edit-ques-btn').click(function () {
		var q_data = {};
		q_data._token = $('input[name="_token"]').val();

		q_data.id = $('#edit-q-form').attr('data-id');
		q_data.name = $('#edit-q-name').val();
		if (q_data.name == "") {
			sweetAlert("Oops...", "Question name is required!", "error");
			$('#edit-q-name').addClass('bdr-red');
			return false;
		} else {
			$('#edit-q-name').removeClass('bdr-red');
		}

		q_data.es_name = $('#es-edit-q-name').val();
		if (q_data.es_name == "") {
			sweetAlert("Oops...", "Spanish question name is required!", "error");
			$('#es-edit-q-name').addClass('bdr-red');
			return false;
		} else {
			$('#es-edit-q-name').removeClass('bdr-red');
		}

		var q_flows = {};
		var flows_check = false;
		$('input[name="edit-q-flows[]"]').each(function () {
			if ($(this).is(':checked')) {
				q_flows[$(this).val()] = true;
				flows_check = true;
			} else {
				q_flows[$(this).val()] = false;
			}
		});

		if (flows_check === false) {
			sweetAlert("Oops...", "Select atleast one flow for question!", "error");
			$('label[for="edit-q-matrix"]').css('color', '#f00');
			$('label[for="edit-q-image"]').css('color', '#f00');
			$('label[for="edit-q-comment"]').css('color', '#f00');
			return false;
		} else {
			$('label[for="edit-q-matrix"]').css('color', 'initial');
			$('label[for="edit-q-image"]').css('color', 'initial');
			$('label[for="edit-q-comment"]').css('color', 'initial');
		}

		q_data.matrix = {};
		q_data.es_matrix = {};
		if (q_flows.matrix === true) {
			q_data.matrix.flow = true;
			q_data.matrix.label = $('#edit-q-matrix-label').val();
			if (q_data.matrix.label == "") {
				sweetAlert("Oops...", "Matrix flow label is required!", "error");
				$('#edit-q-matrix-label').addClass('bdr-red');
				return false;
			} else {
				$('#edit-q-matrix-label').removeClass('bdr-red');
			}
			q_data.matrix.options = $('#edit-q-matrix-options').val();
			if (q_data.matrix.options === null) {
				sweetAlert("Oops...", "Select atleast one row option for matrix flow!", "error");
				$('#edit-q-matrix-options').addClass('bdr-red');
				return false;
			} else {
				$('#edit-q-matrix-options').removeClass('bdr-red');
			}

			$('#es-edit-q-matrix').prop('checked', true);
			q_data.es_matrix.flow = true;
			q_data.es_matrix.label = $('#es-edit-q-matrix-label').val();
			if (q_data.es_matrix.label == "") {
				sweetAlert("Oops...", "Spanish Matrix flow label is required!", "error");
				$('#es-edit-q-matrix-label').addClass('bdr-red');
				return false;
			} else {
				$('#es-edit-q-matrix-label').removeClass('bdr-red');
			}
			q_data.es_matrix.options = $('#es-edit-q-matrix-options').val();
			if (q_data.es_matrix.options === null) {
				sweetAlert("Oops...", "Select atleast one option for Spanish matrix flow!", "error");
				$('#es-edit-q-matrix-options').addClass('bdr-red');
				return false;
			} else {
				$('#es-edit-q-matrix-options').removeClass('bdr-red');
			}
		} else {
			q_data.matrix.flow = false;
			q_data.es_matrix.flow = false;
		}

		q_data.image = {};
		q_data.es_image = {};
		if (q_flows.image === true) {
			q_data.image.flow = true;
			q_data.image.label = $('#edit-q-image-label').val();
			if (q_data.image.label == "") {
				sweetAlert("Oops...", "Image flow label is required!", "error");
				$('#edit-q-image-label').addClass('bdr-red');
				return false;
			} else {
				$('#edit-q-image-label').removeClass('bdr-red');
			}

			$('#es-edit-q-image').prop('checked', true);
			q_data.es_image.flow = true;
			q_data.es_image.label = $('#es-edit-q-image-label').val();
			if (q_data.es_image.label == "") {
				sweetAlert("Oops...", "Spanish Image flow label is required!", "error");
				$('#es-edit-q-image-label').addClass('bdr-red');
				return false;
			} else {
				$('#es-edit-q-image-label').removeClass('bdr-red');
			}
		} else {
			q_data.image.flow = false;
			q_data.es_image.flow = false;
		}

		q_data.comment = {};
		q_data.es_comment = {};
		if (q_flows.comment === true) {
			q_data.comment.flow = true;
			q_data.comment.label = $('#edit-q-comment-label').val();
			if (q_data.comment.label == "") {
				sweetAlert("Oops...", "Comment flow label is required!", "error");
				$('#edit-q-comment-label').addClass('bdr-red');
				return false;
			} else {
				$('#edit-q-comment-label').removeClass('bdr-red');
			}

			$('#es-edit-q-comment').prop('checked', true);
			q_data.es_comment.flow = true;
			q_data.es_comment.label = $('#es-edit-q-comment-label').val();
			if (q_data.es_comment.label == "") {
				sweetAlert("Oops...", "Spanish Comment flow label is required!", "error");
				$('#es-edit-q-comment-label').addClass('bdr-red');
				return false;
			} else {
				$('#es-edit-q-comment-label').removeClass('bdr-red');
			}
		} else {
			q_data.comment.flow = false;
			q_data.es_comment.flow = false;
		}

		$.ajax({
			url: 'edit-question',
			type: 'post',
			data: q_data,
			success: function (response) {
				console.log(response);
				window.location = 'questions';
			},
			error: function (response) {
				var err_msg = '';
				for (var field in response.responseJSON)
					err_msg += ' ' + field + ',';
				err_msg = err_msg.replace(/.$/, "");
				if (err_msg.includes('already exists'))
					sweetAlert("Oops...", err_msg, "error");
				else
					sweetAlert("Oops...", err_msg + " are required!", "error");
			}
		});
	});
});