$(document).ready(function () {
	$('#add-q-matrix-options').select2({
		placeholder: "Select Options",
		dropdownParent: $("#add-modal")
	});

	$('#es-add-q-matrix-options').select2({
		placeholder: "Seleccione opciones",
		dropdownParent: $("#add-modal")
	});

	$('#add-q-matrix').change(function () {
		if ($(this).is(':checked'))
			$('#es-add-q-matrix').prop('checked', true);
		else
			$('#es-add-q-matrix').prop('checked', false);
	});

	$('#es-add-q-matrix').change(function () {
		if ($(this).is(':checked'))
			$('#add-q-matrix').prop('checked', true);
		else
			$('#add-q-matrix').prop('checked', false);
	});

	$('#add-q-image').change(function () {
		if ($(this).is(':checked'))
			$('#es-add-q-image').prop('checked', true);
		else
			$('#es-add-q-image').prop('checked', false);
	});

	$('#es-add-q-image').change(function () {
		if ($(this).is(':checked'))
			$('#add-q-image').prop('checked', true);
		else
			$('#add-q-image').prop('checked', false);
	});

	$('#add-q-comment').change(function () {
		if ($(this).is(':checked'))
			$('#es-add-q-comment').prop('checked', true);
		else
			$('#es-add-q-comment').prop('checked', false);
	});

	$('#es-add-q-comment').change(function () {
		if ($(this).is(':checked'))
			$('#add-q-comment').prop('checked', true);
		else
			$('#add-q-comment').prop('checked', false);
	});

	$('#add-q-matrix-options').change(function () {
		var en_options = $('#add-q-matrix-options').val();
		var current_es_options = $('#es-add-q-matrix-options').val();
		if (en_options === null && current_es_options === null)
			return;
		var new_es_options = [];
		for (var index in en_options)
			new_es_options.push(en_options[index]);
		var is_same = (current_es_options === null) ? false : (new_es_options.length == current_es_options.length) && new_es_options.every(function (element, index) {
			return element === current_es_options[index];
		});
		if (!is_same)
			$('#es-add-q-matrix-options').val(new_es_options).trigger("change");
	});

	$('#es-add-q-matrix-options').change(function () {
		var es_options = $('#es-add-q-matrix-options').val();
		var current_en_options = $('#add-q-matrix-options').val();
		if (es_options === null && current_en_options === null)
			return;
		var new_en_options = [];
		for (var index in es_options)
			new_en_options.push(es_options[index]);
		var is_same = (current_en_options === null) ? false : (new_en_options.length == current_en_options.length) && new_en_options.every(function (element, index) {
			return element === current_en_options[index];
		});
		if (!is_same)
			$('#add-q-matrix-options').val(new_en_options).trigger("change");
	});

	$('#add-ques-btn').click(function () {
		var q_data = {};
		q_data._token = $('input[name="_token"]').val();

		q_data.name = $('#add-q-name').val();
		if (q_data.name == "") {
			sweetAlert("Oops...", "Question name is required!", "error");
			$('#add-q-name').addClass('bdr-red');
			return false;
		} else {
			$('#add-q-name').removeClass('bdr-red');
		}

		q_data.es_name = $('#es-add-q-name').val();
		if (q_data.es_name == "") {
			sweetAlert("Oops...", "El nombre de la pregunta en español es obligatorio!", "error");
			$('#es-add-q-name').addClass('bdr-red');
			return false;
		} else {
			$('#es-add-q-name').removeClass('bdr-red');
		}

		var q_flows = {};
		var flows_check = false;
		$('input[name="add-q-flows[]"]').each(function () {
			if ($(this).is(':checked')) {
				q_flows[$(this).val()] = true;
				flows_check = true;
			} else {
				q_flows[$(this).val()] = false;
			}
		});

		if (flows_check === false) {
			sweetAlert("Oops...", "Select atleast one flow for question!", "error");
			$('label[for="add-q-matrix"]').css('color', '#f00');
			$('label[for="add-q-image"]').css('color', '#f00');
			$('label[for="add-q-comment"]').css('color', '#f00');
			return false;
		} else {
			$('label[for="add-q-matrix"]').css('color', 'initial');
			$('label[for="add-q-image"]').css('color', 'initial');
			$('label[for="add-q-comment"]').css('color', 'initial');
		}

		q_data.matrix = {};
		q_data.es_matrix = {};
		if (q_flows.matrix === true) {
			q_data.matrix.flow = true;
			q_data.matrix.label = $('#add-q-matrix-label').val();
			if (q_data.matrix.label == "") {
				sweetAlert("Oops...", "Matrix flow label is required!", "error");
				$('#add-q-matrix-label').addClass('bdr-red');
				return false;
			} else {
				$('#add-q-matrix-label').removeClass('bdr-red');
			}
			q_data.matrix.options = $('#add-q-matrix-options').val();
			if (q_data.matrix.options === null) {
				sweetAlert("Oops...", "Select atleast one option for matrix flow!", "error");
				$('#add-q-matrix-options').siblings('.select2').find(' .select2-selection__rendered').addClass('bdr-red');
				return false;
			} else {
				$('#add-q-matrix-options').siblings('.select2').find(' .select2-selection__rendered').removeClass('bdr-red');
			}

			$('#es-add-q-matrix').prop('checked', true);
			q_data.es_matrix.flow = true;
			q_data.es_matrix.label = $('#es-add-q-matrix-label').val();
			if (q_data.es_matrix.label == "") {
				sweetAlert("Oops...", "Se requiere etiqueta de flujo Matrix en Español!", "error");
				$('#es-add-q-matrix-label').addClass('bdr-red');
				return false;
			} else {
				$('#es-add-q-matrix-label').removeClass('bdr-red');
			}
			q_data.es_matrix.options = $('#es-add-q-matrix-options').val();
			if (q_data.es_matrix.options === null) {
				sweetAlert("Oops...", "Seleccione al menos una opción para el flujo de matriz español!", "error");
				$('#es-add-q-matrix-options').siblings('.select2').find(' .select2-selection__rendered').addClass('bdr-red');
				return false;
			} else {
				$('#es-add-q-matrix-options').siblings('.select2').find('.select2-selection__rendered').removeClass('bdr-red');
			}
		} else {
			q_data.matrix.flow = false;
			q_data.es_matrix.flow = false;
		}

		q_data.image = {};
		q_data.es_image = {};
		if (q_flows.image === true) {
			q_data.image.flow = true;
			q_data.image.label = $('#add-q-image-label').val();
			if (q_data.image.label == "") {
				sweetAlert("Oops...", "Image flow label is required!", "error");
				$('#add-q-image-label').addClass('bdr-red');
				return false;
			} else {
				$('#add-q-image-label').removeClass('bdr-red');
			}

			$('#es-add-q-image').prop('checked', true);
			q_data.es_image.flow = true;
			q_data.es_image.label = $('#es-add-q-image-label').val();
			if (q_data.es_image.label == "") {
				sweetAlert("Oops...", "Español Se requiere etiqueta de flujo de imagen!", "error");
				$('#es-add-q-image-label').addClass('bdr-red');
				return false;
			} else {
				$('#es-add-q-image-label').removeClass('bdr-red');
			}
		} else {
			q_data.image.flow = false;
			q_data.es_image.flow = false;
		}

		q_data.comment = {};
		q_data.es_comment = {};
		if (q_flows.comment === true) {
			q_data.comment.flow = true;
			q_data.comment.label = $('#add-q-comment-label').val();
			if (q_data.comment.label == "") {
				sweetAlert("Oops...", "Comment flow label is required!", "error");
				$('#add-q-comment-label').addClass('bdr-red');
				return false;
			} else {
				$('#add-q-comment-label').removeClass('bdr-red');
			}

			$('#es-add-q-comment').prop('checked', true);
			q_data.es_comment.flow = true;
			q_data.es_comment.label = $('#es-add-q-comment-label').val();
			if (q_data.es_comment.label == "") {
				sweetAlert("Oops...", "Spanish Comment flow label is required!", "error");
				$('#es-add-q-comment-label').addClass('bdr-red');
				return false;
			} else {
				$('#es-add-q-comment-label').removeClass('bdr-red');
			}
		} else {
			q_data.comment.flow = false;
			q_data.es_comment.flow = false;
		}

		$.ajax({
			url: 'add-question',
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