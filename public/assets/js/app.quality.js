$(document).ajaxStart(function () {
	$(".piluku-preloader").removeClass('hidden');
});

$(document).ajaxComplete(function (event, xhr, settings) {
	$(".piluku-preloader").addClass('hidden');
});

$(document).ready(function () {
	$(".job_search").select2({
		placeholder: "Select User"
	});

	$('.job_search').on('change', function (e) {
		if($(this).val()!=0) {
			$('.quality-item').hide();
			$('.quality-'+$(this).val()).show();
		} else {
			$('.quality-item').show();
		}
	});

	$('.input-date').datepicker({
		format: "mm-yyyy",
		daysOfWeekDisabled: "1,5",
		startView: "months",
		minViewMode: "months"
	});

	$('.row.quality-item a').click(function (e) {
		e.preventDefault();

		var data = {};
		data._token = $('input[name="_token"]').val();
		data.parameters = $(this).attr('data-parameters');

		$.ajax({
			url: '/survey/form',
			type: 'post',
			data: data,
			success: function (response) {
				console.log(response);
			},
			error: function (response) {
				console.log(response);
			}
		});
	});

	$('.job-surveys-list').click( function () {
		var collapse = '#' + $(this).attr('aria-controls');
		$(collapse).find('.question_list_wrapper').addClass('hidden');
		$(collapse).find('.survey_list_wrapper').removeClass('hidden');
	});

	$('.job-questions-list').click( function () {
		var collapse = '#' + $(this).attr('aria-controls');
		$(collapse).find('.survey_list_wrapper').addClass('hidden');
		$(collapse).find('.question_list_wrapper').removeClass('hidden');
	});

	$('.question-name-div').click( function () {
		var question = $(this).text();
		var job_number = $(this).attr('data-job');
		var manager = $(this).attr('data-manager');
		var industry = $(this).attr('data-industry');

		var job_surveys_data = job_surveys(job_number);

		var html = '';
		for (var i = 0; i < job_surveys_data.length; i++)
			html += '<div class="job-surveys-list-item">'+
								job_surveys_data[i].name+
								'<input type="radio" name="survey-for-question" data-survey="'+job_surveys_data[i].random_id+'" data-industry="'+industry+'" data-manager="'+manager+'" data-job="'+job_number+'" data-question="'+question+'">'+
							'</div>';

		$('#select-survey-modal .modal-header h2').text('Job Number: '+job_number);
		$('#select-survey-modal .modal-header h4 span').text(question);
		$('#select-survey-modal #job-surveys-list-container').html(html);

		$('#select-survey-modal').modal('show');

		$('.job-surveys-list-item').click( function () {
			$('.job-surveys-list-item').removeClass('selected');
			$(this).addClass('selected');
			$(this).find('input[name="survey-for-question"]').prop("checked", true);
		});

		$('#start-survey').click( function () {
			$(".piluku-preloader").removeClass('hidden');
			if (!$('input[name="survey-for-question"]:checked').length) {
				sweetAlert("Oops...", "Kindly select a survey!", "error");
				return false;
			}
			var data = {};
			data._token = $('input[name="_token"]').val();
			data.question = $('input[name="survey-for-question"]:checked').attr('data-question');
			data.industry = $('input[name="survey-for-question"]:checked').attr('data-industry');
			data.manager = $('input[name="survey-for-question"]:checked').attr('data-manager');
			data.survey = $('input[name="survey-for-question"]:checked').attr('data-survey');
			data.job = $('input[name="survey-for-question"]:checked').attr('data-job');
			$.ajax({
				url: '/quality/get-url',
				type: 'post',
				data: data,
				success: function (response) {
					if (response.success)
						window.location = response.link;
					else
						sweetAlert("Oops...", "Something went wrong!", "error");
				},
				error: function (response) {
					console.log(response);
					sweetAlert("Oops...", "Something went wrong!", "error");
				}
			});
		});
	});
});