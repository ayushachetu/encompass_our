$(document).ajaxStart(function() {
	$(".piluku-preloader").removeClass('hidden');
});

$(document).ajaxComplete(function(event, xhr, settings) {
	$(".piluku-preloader").addClass('hidden');
});

$(document).ready(function () {
	hide_angles();

	$('#q-table').on('change', '.industry-question-chk', function () {
		var data = {};
		data.industry = $(this).attr('data-industry');
		data.question = $(this).parents('tr').attr('data-id');
		data._token = $('input[name="_token"]').val();

		var req_url;
		if ($(this).is(':checked'))
			req_url = 'store-industry-question';
		else
			req_url = 'delete-industry-question';
		$.ajax({
			url: req_url,
			type: 'post',
			data: data,
			success: function (response) {
				console.log(response);
			},
			error: function (response) {
				for (var field in response.responseJSON) {
					console.log(response.responseJSON[field]);
				}
				sweetAlert("Oops...", "Something went wrong!", "error");
			}
		});
	});

	$('#q-table').on('click', '.fa-angle-up', function () {
		var data = {};
		data.priority = $(this).parents('tr').attr('data-priority');
		data.id = $(this).parents('tr').attr('data-id');
		data.sibling_id = $(this).parents('tr').prev().attr('data-id');
		data.sibling_priority = $(this).parents('tr').prev().attr('data-priority');
		data._token = $('input[name="_token"]').val();
		var tr = $(this).parents("tr");

		$.ajax({
			url: 'update-priority',
			type: 'post',
			data: data,
			success: function (response) {
				console.log(response);
				tr.attr('data-priority', data.sibling_priority);
				tr.prev().attr('data-priority', data.priority);
				tr.insertBefore(tr.prev());
				hide_angles();
			},
			error: function (response) {
				for (var field in response.responseJSON) {
					console.log(response.responseJSON[field]);
				}
				sweetAlert("Oops...", "Something went wrong!", "error");
			}
		});
	});

	$('#q-table').on('click', '.fa-angle-down', function () {
		var data = {};
		data.priority = $(this).parents('tr').attr('data-priority');
		data.id = $(this).parents('tr').attr('data-id');
		data.sibling_id = $(this).parents('tr').next().attr('data-id');
		data.sibling_priority = $(this).parents('tr').next().attr('data-priority');
		data._token = $('input[name="_token"]').val();
		var tr = $(this).parents("tr");

		$.ajax({
			url: 'update-priority',
			type: 'post',
			data: data,
			success: function (response) {
				console.log(response);
				tr.attr('data-priority', data.sibling_priority);
				tr.next().attr('data-priority', data.priority);
				tr.insertAfter(tr.next());
				hide_angles();
			},
			error: function (response) {
				for (var field in response.responseJSON) {
					console.log(response.responseJSON[field]);
				}
				sweetAlert("Oops...", "Something went wrong!", "error");
			}
		});
	});

	$('#q-table').on('click', '.fa-trash', function () {
		var tr = $(this).parents('tr');
		var q_text = $(this).parents('td').next().text();
		swal({
			title: "Are you sure?",
			text: "Really delete " + q_text,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: false
		},
		function () {
			var data = {}
			data.id = tr.attr('data-id');
			data._token = $('input[name="_token"]').val();
			$.ajax({
				url: 'delete-question',
				type: 'post',
				data: data,
				success: function (response) {
					console.log(response);
					tr.remove();
					hide_angles();
					swal("Deleted!", q_text + " has been deleted.", "success");
				},
				error: function (response) {
					for (var field in response.responseJSON) {
						console.log(response.responseJSON[field]);
					}
					sweetAlert("Oops...", "Something went wrong!", "error");
				}
			});
		});
	});

	$('#q-table').on('click', '.fa-pencil', function () {
		var data = {};
		data.id = $(this).parents('tr').attr('data-id');
		data._token = $('input[name="_token"]').val();
		$.ajax({
			url: 'question-data',
			type: 'post',
			data: data,
			success: function (response) {
				response = response[0];

				$('#edit-q-name').val(response.name);

				var matrix = JSON.parse(response.matrix);
				if (matrix.flow == "true") {
					$('#edit-q-matrix').prop('checked', true);
					$('#edit-q-matrix-label').val(matrix.label);

					for (var index in matrix.options) {
						if ($('#edit-q-matrix-options option[value="'+matrix.options[index]+'"]').length < 1) {
							$('#edit-q-matrix-options')
								.append('<option value="'+matrix.options[index]+'">'+deleted_options(matrix.options[index])[0]+'</option>');
							$('#es-edit-q-matrix-options')
								.append('<option value="'+matrix.options[index]+'">'+deleted_options(matrix.options[index])[1]+'</option>');
						}
					}
					$('#edit-q-matrix-options').val(matrix.options).trigger("change");
				}

				var image = JSON.parse(response.image);
				if (image.flow == "true") {
					$('#edit-q-image').prop('checked', true);
					$('#edit-q-image-label').val(image.label);
				}

				var comment = JSON.parse(response.comment);
				if (comment.flow == "true") {
					$('#edit-q-comment').prop('checked', true);
					$('#edit-q-comment-label').val(comment.label);
				}

				$('#es-edit-q-name').val(response.es_name);

				matrix = JSON.parse(response.es_matrix);
				if (matrix.flow == "true") {
					$('#es-edit-q-matrix').prop('checked', true);
					$('#es-edit-q-matrix-label').val(matrix.label);
					$('#es-edit-q-matrix-options').val(matrix.options).trigger("change");
				}

				image = JSON.parse(response.es_image);
				if (image.flow == "true") {
					$('#es-edit-q-image').prop('checked', true);
					$('#es-edit-q-image-label').val(image.label);
				}

				comment = JSON.parse(response.es_comment);
				if (comment.flow == "true") {
					$('#es-edit-q-comment').prop('checked', true);
					$('#es-edit-q-comment-label').val(comment.label);
				}

				$('#edit-q-form').attr('data-id', response.id);

				$('#edit-q-form').parents('.modal').modal('show');
			},
			error: function (response) {
				for (var field in response.responseJSON) {
					console.log(response.responseJSON[field]);
				}
				sweetAlert("Oops...", "Something went wrong!", "error");
			}
		});
	});

	$('#new-ques-btn').click(function () {
		$('#add-q-form').parents('.modal').modal('show');
	});
});

function hide_angles() {
	$('.fa-angle-up').removeClass('hidden');
	$('.fa-angle-down').removeClass('hidden');

	$('.fa-angle-up').first().addClass('hidden');
	$('.fa-angle-down').last().addClass('hidden');
}