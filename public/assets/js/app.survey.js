$(document).ajaxStart(function () {
	$(".piluku-preloader").removeClass('hidden');
});

$(document).ajaxComplete(function () {
	$(".piluku-preloader").addClass('hidden');
});

$(document).ready(function () {
	$('#s-table').DataTable({
		"paging": false,
		"oLanguage": { "sSearch": "" }
	});

	$('#s-table_filter').find('input').attr('placeholder', 'Search');

	$('#s-table_wrapper').addClass('table-responsive');
	$('#s-table_wrapper').css('padding', '15px');

	$('#new-survey-btn').click(function () {
		$('#add-survey-modal').modal('show');
	});

	$('#survey-type').change(function () {
		$(this).css('color', '#333');

		if ($(this).val() == 'Quality') {
			$('#include-express').parents('.row').removeClass('hidden');
		} else {
			$('#include-express').parents('.row').addClass('hidden');
			$('#include-express').prop('checked', false);
		}
	});

	$('#survey-jobs option').click(function (e) {
		e.stopPropagation();
	});

	$('#survey-jobs optgroup').click(function () {
		switch($(this).attr('label')) {
			case 'All':
				$('#survey-jobs option').prop('selected', true);
				break;
			case 'Healthcare':
				$('#survey-jobs optgroup[label="Healthcare"] option').prop('selected', true);
				break;
			case 'Education':
				$('#survey-jobs optgroup[label="Education"] option').prop('selected', true);
				break;
			case 'Commercial':
				$('#survey-jobs optgroup[label="Commercial"] option').prop('selected', true);
				break;
			case 'Hospitality':
				$('#survey-jobs optgroup[label="Hospitality"] option').prop('selected', true);
				break;
			case 'Government':
				$('#survey-jobs optgroup[label="Government"] option').prop('selected', true);
				break;
			case 'Public Venue':
				$('#survey-jobs optgroup[label="Public Venue"] option').prop('selected', true);
				break;
			case 'Retail':
				$('#survey-jobs optgroup[label="Retail"] option').prop('selected', true);
				break;
			case 'Industrial':
				$('#survey-jobs optgroup[label="Industrial"] option').prop('selected', true);
				break;
		}
	});

	$('.survey-link').click(function () {
		var survey_id = $(this).attr('data-id');
		window.location = '/survey/'+survey_id;
	});

	$('#create-survey-btn').click(function () {
		var data = {};
		data._token = $('input[name="_token"]').val();

		data.name = $('#survey-name').val();
		if (data.name == "") {
			sweetAlert("Oops...", "Survey name is required!", "error");
			$('#survey-name').addClass('bdr-red');
			return false;
		} else {
			$('#survey-name').removeClass('bdr-red');
		}

		data.description = $('#survey-description').val();
		if (data.description == "") {
			sweetAlert("Oops...", "Survey description is required!", "error");
			$('#survey-description').addClass('bdr-red');
			return false;
		} else {
			$('#survey-description').removeClass('bdr-red');
		}

		data.type = $('#survey-type').val();
		if (data.type == null) {
			sweetAlert("Oops...", "Survey type is required!", "error");
			$('#survey-type').addClass('bdr-red');
			return false;
		} else {
			$('#survey-type').removeClass('bdr-red');
		}

		if ($('#include-express').is(':checked'))
			data.has_express = 1;
		else
			data.has_express = 0;

		data.jobs = $('#survey-jobs').val();
		if (data.jobs == null) {
			sweetAlert("Oops...", "Assign survey to atleast one job!", "error");
			$('#survey-jobs').addClass('bdr-red');
			return false;
		} else {
			$('#survey-jobs').removeClass('bdr-red');
		}

		data.bgcolor = document.getElementById('survey-btn-color').value;

		$.ajax({
			type: 'post',
			url: 'survey/store',
			data: data,
			success: function (response) {
				if ('id' in response)
					window.location = '/survey/'+response['id'];
			},
			error: function (response) {
				var err_msg = '';
				for (var field in response.responseJSON)
					err_msg += ' ' + field + ',';
				err_msg = err_msg.replace(/.$/, "");
				sweetAlert("Oops...", "Survey " + err_msg + " required!", "error");
			}
		});
	});

	$('#s-table').on('click', 'input[name="Launch"]', function () {
		var element = $(this);
		var tr = element.parents('tr');
		var s_text = tr.find('td:first-child').text();
		swal({
			title: "Are you sure?",
			text: "Really launch " + s_text,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, launch it!",
			closeOnConfirm: false
		},
		function () {
			var data = {};
			data._token = $('input[name="_token"]').val();

			$.ajax({
				type: 'post',
				url: 'survey/launch',
				data: data,
				success: function (response) {
					swal("Launched!", s_text + " has been activated.", "success");
					element.addClass('hidden');
					element.siblings('input[name="Hold"]').removeClass('hidden');
				},
				error: function (response) {
					console.log(response.responseJSON);
					sweetAlert("Oops...", "Something went wrong!", "error");
				}
			});
		});
	});

	$('#s-table').on('click', 'input[name="Hold"]', function () {
		var element = $(this);
		var tr = element.parents('tr');
		var s_text = tr.find('td:first-child').text();
		swal({
			title: "Are you sure?",
			text: "Really hold " + s_text,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, hold it!",
			closeOnConfirm: false
		},
		function () {
			var data = {};
			data._token = $('input[name="_token"]').val();

			$.ajax({
				type: 'post',
				url: 'survey/hold',
				data: data,
				success: function (response) {
					swal("Done!", s_text + " has been put on hold.", "success");
					element.addClass('hidden');
					element.siblings('input[name="Launch"]').removeClass('hidden');
				},
				error: function (response) {
					console.log(response.responseJSON);
					sweetAlert("Oops...", "Something went wrong!", "error");
				}
			});
		});
	});

	$('#s-table').on('click', 'input[name="Delete"]', function () {
		var tr = $(this).parents('tr');
		var s_text = tr.find('td:first-child').text();
		swal({
			title: "Are you sure?",
			text: "Really remove " + s_text,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: false
		},
		function () {
			var data = {};
			data._token = $('input[name="_token"]').val();
			data.id = tr.find('td:first-child').attr('data-id');

			$.ajax({
				type: 'post',
				url: 'survey/delete',
				data: data,
				success: function (response) {
					tr.remove();
					swal("Deleted!", s_text + " has been deleted.", "success");
				},
				error: function (response) {
					console.log(response.responseJSON);
					sweetAlert("Oops...", "Something went wrong!", "error");
				}
			});
		});
	});
});