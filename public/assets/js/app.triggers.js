$(document).ready(function () {
	$('#recipients-by-roles').select2({
		placeholder: "Select recipients"
	});

	$('#on-action').select2({
		placeholder: "Select event"
	});

	$('#data-to-send').select2({
		placeholder: "Select data to send in email"
	});

	$('#jobs-list').select2({
		placeholder: "Select jobs"
	});

	$('#custom-recipients').select2({
		placeholder: "Enter recipients",
		tags: true,
		tokenSeparators: [",", " "],
		createTag: function (tag) {
			return {
				id: tag.term,
				text: tag.term,
				isNew : true
			};
		}
	}).on("select2:select", function (e) {
		if (e.params.data.isNew)
			$(this).find('[value="'+e.params.data.id+'"]').replaceWith('<option selected value="'+e.params.data.id+'">'+e.params.data.text+'</option>');
	}).on("select2:unselect", function (e) {
		$(this).find('[value="'+e.params.data.id+'"]').remove();
	});

	$('input[name="execution-radio"]').change(function () {
		if ($(this).val() == 'Custom') {
			$('#execution-time').removeClass('hidden');
			$('#execution-unit').removeClass('hidden');
		} else {
			$('#execution-time').addClass('hidden');
			$('#execution-unit').addClass('hidden');
		}
	});

	$('#create-trigger').click(function () {
		var data = {};
		data._token = $('input[name="_token"]').val();

		data.recipients_by_roles = $('#recipients-by-roles').val();
		data.custom_recipients = $('#custom-recipients').val();
		data.on_action = $('#on-action').val();

		if ($('input[name="execution-radio"]').val() == 'Custom') {
			data.execution_time = $('#execution-time').val();
			data.execution_unit = $('#execution-unit').val();
		} else {
			data.execution_time = 'Immediately';
			data.execution_unit = 'n/a';
		}

		data.data_to_send = $('#data-to-send').val();
		data.custom_message = $('#custom-message').val();
		data.jobs = $('#jobs-list').val();

		$.ajax({
			url: '/triggers/create',
			type: 'post',
			data: data,
			success: function (response) {
				// swal("Done", "Trigger is created successfully", "success");
				// window.location.reload();
			},
			error: function (response) {
				sweetAlert("Oops...", "Something went wrong! Try again.", "error");
			}
		});
	});
});