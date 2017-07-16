$(document).ajaxStart(function() {
	$(".piluku-preloader").removeClass('hidden');
});

$(document).ajaxComplete(function(event, xhr, settings) {
	$(".piluku-preloader").addClass('hidden');
});

$(document).ready(function () {
	$('#new-opt-div').hide(10);

	$('#new-opt-close').click(function () {
		$('#new-opt-div').hide(800);
	});

	$('#new-opt-btn').click(function () {
		$('#new-opt-div').show(800);
	});

	$('#deleted-opt-table').hide(10);

	$('#show-deleted-options').click(function () {
		$('#deleted-opt-table').show(800);
	});

	$('#add-opt-btn').click(function () {
		var data = {};
		data._token = $('input[name="_token"]').val();

		data.en_option = $('#new-en-option').val();
		if (data.en_option == '') {
			sweetAlert("Oops...", "English Option is required!", "error");
			$('#new-en-option').addClass('bdr-red');
			return false;
		} else {
			$('#new-en-option').removeClass('bdr-red');
		}

		data.es_option = $('#new-es-option').val();
		if (data.es_option == '') {
			sweetAlert("Oops...", "Se requiere opción española!", "error");
			$('#new-es-option').addClass('bdr-red');
			return false;
		} else {
			$('#new-es-option').removeClass('bdr-red');
		}

		$.ajax({
			url: 'add-matrix-option',
			type: 'post',
			data: data,
			success: function (response) {
				console.log(response);
				window.location = 'matrix-options';
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

	$('#opt-table').Tabledit({
		columns: {
			identifier: [0, 'id'],
			editable: [[1, 'en_option'], [2, 'es_option']]
		},
		url: 'edit-matrix-option',
		hideIdentifier: true,
		restoreButton: false,
		deleteButton: false,
		onAjax: function (action, serialize) {
			console.log(action, serialize);
			return;
		},
		onFail: function (response, textStatus, errorThrown) {
			if (errorThrown == "Unprocessable Entity") {
				var err_msg = '';
				for (var field in response.responseJSON)
					err_msg += ' ' + field + ',';
				err_msg = err_msg.replace(/.$/, "");
				if (err_msg.includes('already exists'))
					sweetAlert("Oops...", err_msg, "error");
				else
					sweetAlert("Oops...", err_msg + " are required!", "error");
			} else {
				sweetAlert("Oops...", "Something went wrong!", "error");
			}
		}
	});

	var delete_button_html = '<button type="button" class="btn btn-sm btn-default delete-opt" style="float: none;">'+
														'<span class="glyphicon glyphicon-trash"></span>'+
													 '</button>';
	$('#opt-table tbody tr td:last-child div.btn-group').append(delete_button_html);

	$('#opt-table').on('click', '.delete-opt', function () {
		var tr = $(this).parents('tr');
		var opt_text = tr.find('td:nth-of-type(2)').text();
		swal({
			title: "Are you sure?",
			text: "Really delete " + opt_text,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: false
		},
		function () {
			var data = {};
			data._token = $('input[name="_token"]').val();
			data.id = tr.find('td:first-child').text();
			$.ajax({
				url: 'delete-matrix-option',
				type: 'post',
				data: data,
				success: function (response) {
					console.log(response);
					var html = '<tr data-id="'+data.id+'">'+
												'<td>'+opt_text+'</td>'+
												'<td>'+tr.find('td:nth-of-type(3)').text()+'</td>'+
												'<td><i class="fa fa-reply" aria-hidden="true"></i></td>'+
										 '</tr>';
					$('#deleted-opt-table').append(html);
					tr.remove();
					$('#deleted-opt-table').find('tr#no-records').remove();
					swal("Deleted!", opt_text + " has been deleted.", "success");
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

	$('#deleted-opt-table').on('click', '.fa-reply', function () {
		var tr = $(this).parents('tr');
		var data = {};
		data._token = $('input[name="_token"]').val();
		data.id = tr.attr('data-id');
		$.ajax({
			url: 'restore-matrix-option',
			type: 'post',
			data: data,
			success: function (response) {
				console.log(response);
				var en_option = tr.find('td:nth-of-type(1)').text();
				var es_option = tr.find('td:nth-of-type(2)').text();
				var html = '<tr id="'+data.id+'">'+
											'<td style="display: none;">'+
												'<span class="tabledit-span tabledit-identifier">'+data.id+'</span>'+
												'<input class="tabledit-input tabledit-identifier" type="hidden" name="id" value="'+data.id+'" disabled="">'+
											'</td>'+
											'<td class="tabledit-view-mode">'+
												'<span class="tabledit-span">'+en_option+'</span>'+
												'<input class="tabledit-input form-control input-sm" type="text" name="en_option" value="'+en_option+'" style="display: none;" disabled="">'+
											'</td>'+
											'<td class="tabledit-view-mode">'+
												'<span class="tabledit-span">'+es_option+'</span>'+
												'<input class="tabledit-input form-control input-sm" type="text" name="es_option" value="'+es_option+'" style="display: none;" disabled="">'+
											'</td>'+
											'<td style="white-space: nowrap; width: 1%;">'+
												'<div class="tabledit-toolbar btn-toolbar" style="text-align: left;">'+
													'<div class="btn-group btn-group-sm" style="float: none;">'+
														'<button type="button" class="tabledit-edit-button btn btn-sm btn-default" style="float: none;">'+
															'<span class="glyphicon glyphicon-pencil"></span>'+
														'</button>'+
														'<button type="button" class="btn btn-sm btn-default delete-opt" style="float: none;">'+
															'<span class="glyphicon glyphicon-trash"></span>'+
														'</button>'+
													'</div>'+
													'<button type="button" class="tabledit-save-button btn btn-sm btn-success" style="display: none; float: none;">Save</button>'+
												'</div>'+
											'</td>'+
										'</tr>';
				$('#opt-table').append(html);
				tr.remove();
				if ($('#deleted-opt-table tr').length === 1) {
					html = '<tr id="no-records">'+
										'<td colspan="3">No Records Found</td>'+
								 '</tr>';
					$('#deleted-opt-table').append(html);
				}
				swal("Restored!", en_option + " has been restored.", "success");
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