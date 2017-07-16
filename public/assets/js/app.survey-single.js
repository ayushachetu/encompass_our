$(document).ajaxStart(function () {
	$(".piluku-preloader").removeClass('hidden');
});

$(document).ajaxComplete(function () {
	$(".piluku-preloader").addClass('hidden');
});

$(document).ready(function () {
	$('#import-ques-btn').click(function () {
		$('#add-question-modal').modal('show');
	});

	var questions_list_table = $('#question-list-table').DataTable({
		"ordering": false,
		"pageLength": 5,
		"oLanguage": {
			"sSearch": ""
		}
	});

	$('#question-list-table_filter').find('input').attr('placeholder', 'Search');

	$('#show-edit-survey').click(function () {
		$('#edit-survey-modal').modal('show');
	});

	$('#edit-survey-type').change(function () {
		if ($(this).val() == 'Quality') {
			$('#edit-include-express').parents('.row').removeClass('hidden');
		} else {
			$('#edit-include-express').parents('.row').addClass('hidden');
			$('#edit-include-express').prop('checked', false);
		}
	});

	$('#edit-survey-jobs option').click(function (e) {
		e.stopPropagation();
	});

	$('#edit-survey-jobs optgroup').click(function () {
		switch($(this).attr('label')) {
			case 'All':
				$('#edit-survey-jobs option').prop('selected', true);
				break;
			case 'Healthcare':
				$('#edit-survey-jobs optgroup[label="Healthcare"] option').prop('selected', true);
				break;
			case 'Education':
				$('#edit-survey-jobs optgroup[label="Education"] option').prop('selected', true);
				break;
			case 'Commercial':
				$('#edit-survey-jobs optgroup[label="Commercial"] option').prop('selected', true);
				break;
			case 'Hospitality':
				$('#edit-survey-jobs optgroup[label="Hospitality"] option').prop('selected', true);
				break;
			case 'Government':
				$('#edit-survey-jobs optgroup[label="Government"] option').prop('selected', true);
				break;
			case 'Public Venue':
				$('#edit-survey-jobs optgroup[label="Public Venue"] option').prop('selected', true);
				break;
			case 'Retail':
				$('#edit-survey-jobs optgroup[label="Retail"] option').prop('selected', true);
				break;
			case 'Industrial':
				$('#edit-survey-jobs optgroup[label="Industrial"] option').prop('selected', true);
				break;
		}
	});

	$('#filter-industry-question').change(function () {
		var data = {};
		data._token = $('input[name="_token"]').val();
		data.industry = $(this).val();

		$.ajax({
			type: 'post',
			url: '/survey/get-industry-question',
			data: data,
			success: function (response) {
				data = response['data'];
				if (data.length !== 0) {
					var html = '<thead>'+
											'<tr>'+
												'<th></th>'+
												'<th></th>'+
											'</tr>'+
										'</thead>'+
										'<tbody>';
					for (var i = 0; i < data.length; i++) {
						html += '<tr>'+
											'<td>'+
												'<input type="checkbox" id="'+data[i].id+'" name="questions-to-import[]" value="'+data[i].id+'">'+
												'<label for="'+data[i].id+'" class="f-16"><span></span> '+data[i].name+'</label>'+
											'</td>'+
											'<td q-data=\''+JSON.stringify(data[i])+'\'>'+
												'<input type="button" class="btn btn-sm btn-primary preview-survey-ques" value="Preview">'+
											'</td>'+
										'</tr>';
					}
					html += '</tbody>';

					$('#question-list-table').html(html);
					questions_list_table = $('#question-list-table').DataTable({
						"bDestroy": true,
						"ordering": false,
						"pageLength": 5,
						"oLanguage": {
							"sSearch": ""
						}
					});
				} else {
					sweetAlert("Oops...", "No record found for your query!", "error");
				}
			},
			error: function (response) {
				console.log(response.responseJSON);
				sweetAlert("Oops...", "Something went wrong!", "error");
			}
		});
	});

	$('#add-questions-btn').click(function () {
		var questions = [];
		questions_list_table.rows().nodes().to$().find('input[name="questions-to-import[]"]').each(function () {
			if ($(this).is(':checked'))
				questions.push($(this).val());
		});
		if (questions.length === 0) {
			sweetAlert("Oops...", "Select atleast one question to add in survey!", "error");
			return false;
		}

		var data = {};
		data._token = $('input[name="_token"]').val();
		data.questions = questions;

		$.ajax({
			type: 'post',
			url: 'add-industry-question',
			data: data,
			success: function (response) {
				window.location.reload();
			},
			error: function (response) {
				console.log(response.responseJSON);
				sweetAlert("Oops...", "Something went wrong!", "error");
			}
		});
	});

	$('#survey-questions-list').on('click', '.fa-trash', function () {
		var tr = $(this).parents('tr');
		var q_text = tr.find('td:first-child').text();
		swal({
			title: "Are you sure?",
			text: "Really remove " + q_text,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: false
		},
		function () {
			var data = {};
			data._token = $('input[name="_token"]').val();
			data.question = tr.attr('data-id');

			$.ajax({
				type: 'post',
				url: 'delete-industry-question',
				data: data,
				success: function (response) {
					tr.remove();
					swal("Deleted!", q_text + " has been removed.", "success");
				},
				error: function (response) {
					console.log(response.responseJSON);
					sweetAlert("Oops...", "Something went wrong!", "error");
				}
			});
		});
	});

	$('#edit-survey-btn').click(function () {
		var data = {};
		data._token = $('input[name="_token"]').val();

		data.name = $('#edit-survey-name').val();
		if (data.name == "") {
			sweetAlert("Oops...", "Survey name is required!", "error");
			$('#edit-survey-name').addClass('bdr-red');
			return false;
		} else {
			$('#edit-survey-name').removeClass('bdr-red');
		}

		data.description = $('#edit-survey-description').val();
		if (data.description == "") {
			sweetAlert("Oops...", "Survey description is required!", "error");
			$('#edit-survey-description').addClass('bdr-red');
			return false;
		} else {
			$('#edit-survey-description').removeClass('bdr-red');
		}

		data.type = $('#edit-survey-type').val();
		if (data.type == null) {
			sweetAlert("Oops...", "Survey type is required!", "error");
			$('#edit-survey-type').addClass('bdr-red');
			return false;
		} else {
			$('#edit-survey-type').removeClass('bdr-red');
		}

		if ($('#edit-include-express').is(':checked'))
			data.has_express = 1;
		else
			data.has_express = 0;

		data.jobs = $('#edit-survey-jobs').val();
		if (data.jobs == null) {
			sweetAlert("Oops...", "Assign survey to atleast one job!", "error");
			$('#edit-survey-jobs').addClass('bdr-red');
			return false;
		} else {
			$('#edit-survey-jobs').removeClass('bdr-red');
		}

		data.bgcolor = document.getElementById('survey-btn-color').value;

		$.ajax({
			type: 'post',
			url: 'edit',
			data: data,
			success: function (response) {
				window.location.reload();
			},
			error: function (response) {
				var err_msg = '';
				for (var field in response.responseJSON)
					err_msg += ' ' + field + ',';
				err_msg = err_msg.replace(/.$/, "");
				if (err_msg != '')
					sweetAlert("Oops...", "Survey " + err_msg + " required!", "error");
				else
					sweetAlert("Oops...", "Something went wrong!", "error");
			}
		});
	});

	$('#question-list-table').on('click', '.preview-survey-ques', function () {
		var question_data = JSON.parse($(this).parent().attr('q-data'));

		question_data.matrix = JSON.parse(question_data.matrix);
		if (question_data.matrix.flow == "true") {
			$('#preview-question-matrix-label').text(question_data.matrix.label);
			$('#preview-question-matrix-label').removeClass('hidden');
			$('#preview-question-matrix-container').removeClass('hidden');

			var options = options_data(question_data.matrix.options);
			var html = '';
			for (var option in options) {
				html += '<tr>'+
									'<td>'+options[option]+'</td>'+
									'<td>'+
										'<input type="radio" name="radio" id="'+option+'" value="Poor">'+
										'<label for="'+option+'"><span></span></label>'+
									'</td>'+
									'<td>'+
										'<input type="radio" name="radio" id="'+option+'" value="Acceptable">'+
										'<label for="'+option+'"><span></span></label>'+
									'</td>'+
									'<td>'+
										'<input type="radio" name="radio" id="'+option+'" value="Good">'+
										'<label for="'+option+'"><span></span></label>'+
									'</td>'+
									'<td>'+
										'<input type="radio" name="radio" id="'+option+'" value="N/A">'+
										'<label for="'+option+'"><span></span></label>'+
									'</td>'+
								'</tr>';
			}
			$('#preview-question-matrix-container table tbody').html(html);
		} else {
			$('#preview-question-matrix-label').addClass('hidden');
			$('#preview-question-matrix-container').addClass('hidden');
		}

		question_data.image = JSON.parse(question_data.image);
		if (question_data.image.flow == "true") {
			$('#preview-question-image-label').text(question_data.image.label);
			$('#preview-question-image-label').removeClass('hidden');
			$('#preview-question-image-option').removeClass('hidden');
		} else {
			$('#preview-question-image-label').addClass('hidden');
			$('#preview-question-image-option').addClass('hidden');
		}

		question_data.comment = JSON.parse(question_data.comment);
		if (question_data.comment.flow == "true") {
			$('#preview-question-comment-label').text(question_data.comment.label);
			$('#preview-question-comment-label').removeClass('hidden');
			$('#preview-question-comment-option').removeClass('hidden');
		} else {
			$('#preview-question-comment-label').addClass('hidden');
			$('#preview-question-comment-option').addClass('hidden');
		}

		$('#preview-question-modal').modal('show');
	});
});