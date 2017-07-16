function attach_scheduling_events() {
	$('#scheduler-filter-wrapper').hide(1);

	$('#scheduling-dropdown ul li:first-child').click(function () {
		$('#scheduler-filter-wrapper').show(700);
	});

	$('#is-recursive').change(function () {
		this.style.setProperty('color', '#444', 'important');
		$('#frequency').prop('disabled', false);
		if (this.value == 'Once') {
			$('#frequency').val('Once').change();
			$('#frequency').prop('disabled', true);
		}
	});

	$('#frequency').change(function () {
		this.style.setProperty('color', '#444', 'important');
		$('#send-on').prop('disabled', false);
		$('#send-on').datepicker('remove');
		switch(this.value) {
			case 'Weekly':
				$('#send-on').datepicker({ format: 'DD', autoclose: true });
			case 'Monthly':
				$('#send-on').datepicker({ format: 'd', autoclose: true });
			case 'Yearly':
				$('#send-on').datepicker({ format: 'd-MM', autoclose: true });
			case 'Once':
				$('#send-on').datepicker({ format: 'd-MM-yyyy', autoclose: true });
		}
	});

	$('#recipients-by-roles').select2({
		placeholder: "Select recipients by roles"
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

	$('input[name="create-scheduler"]').click(function () {
		validate_scheduling_filters();
	});
}

function validate_scheduling_filters() {
	var data = {};

	data.is_recursive = $('#is-recursive').val();
	if (data.is_recursive == undefined || data.is_recursive == null) {
		sweetAlert("Oops...", "Kindly select applicable: Recursive / Once", "error");
		$('#is-recursive').addClass("bdr-red");
		return false;
	} else {
		$('#is-recursive').removeClass("bdr-red");
	}

	data.frequency = $('#frequency').val();
	if (data.frequency == undefined || data.frequency == null) {
		sweetAlert("Oops...", "Kindly select the frequency", "error");
		$('#frequency').addClass("bdr-red");
		return false;
	} else {
		$('#frequency').removeClass("bdr-red");
	}

	data.send_on = $('#send-on').val();
	if (data.send_on == "") {
		sweetAlert("Oops...", "Kindly select day/date for sending emails", "error");
		$('#send-on').addClass("bdr-red");
		return false;
	} else {
		$('#send-on').removeClass("bdr-red");
	}

	var flag = 0;
	data.recipients_by_roles = $('#recipients-by-roles').val();
	if (data.recipients_by_roles != null)
		flag = 1;

	data.custom_recipients = $('#custom-recipients').val();
	if (data.custom_recipients != null)
		flag = 1;

	if (flag === 0) {
		sweetAlert("Oops...", "Kindly select or add atleast one recipient to send emails", "error");
		$('span[aria-owns="select2-recipients-by-roles-results"]').addClass("bdr-red");
		$('span[aria-owns="select2-custom-recipients-results"]').addClass("bdr-red");
		return false;
	} else {
		$('span[aria-owns="select2-recipients-by-roles-results"]').removeClass("bdr-red");
		$('span[aria-owns="select2-custom-recipients-results"]').removeClass("bdr-red");
	}

	data.custom_message  = $('#custom-message').val();
	if (data.custom_message != "") {
		create_scheduler(data);
	} else {
		swal({
			title: "Are you sure?",
			text: "Send emails without message!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, send it!",
			cancelButtonText: "No, Add message!",
			closeOnConfirm: true,
			closeOnCancel: true
		}, function(isConfirm) {
			if (isConfirm) {
				$('#custom-message').removeClass("bdr-red");
				create_scheduler(data);
			} else {
				$('#custom-message').addClass("bdr-red");
			}
		});
	}
}

function create_scheduler(data) {
	data._token = $('input[name="_token"]').val();
	data.report_type = 'client-cumulative';
	$.ajax({
		url: '/scheduled-jobs/create',
		type: 'post',
		data: data,
		success: function (response) {
			swal("Done", "Job is scheduled successfully", "success");
			$('#scheduler-filter-wrapper').hide(1);
		},
		error: function (response) {
			if (response.status == 422) {
				sweetAlert("Oops...", response.responseJSON.message, "error");
			}
			else
				sweetAlert("Oops...", "Something went wrong! Try again.", "error");
		}
	});
}