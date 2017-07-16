function get_score_agg(scores) {
	var total = 0;
	var len = scores.length;

	for (var i = 0; i < len; i++)
		total += scores[i].y;

	return total / len;
}

function get_color(per) {
	var color;

	if (per >= 95) color = '#1b5e20';
	else if (per >= 90) color = '#4caf50';
	else if (per >= 80) color = '#ffc107';
	else if (per >= 70) color = '#f7941d';
	else color = '#ef5350';

	return color;
}

$(document).ajaxStart(function() {
	$(".piluku-preloader").removeClass('hidden');
});

$(document).ajaxComplete(function(event, xhr, settings) {
	$(".piluku-preloader").addClass('hidden');
});

$(document).ready(function () {
	$(function () {
		var start = moment().subtract(89, 'days');
		var end = moment();

		function cb(start, end) {
			$('#report-range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		}

		$('#report-range').daterangepicker({
			startDate: start,
			endDate: end,
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'Last 90 Days': [moment().subtract(89, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
			}
		}, cb);

		cb(start, end);
	});

	$("#primary-filter").select2();

	$('#primary-filter').change(function () {
		var data = {};
		data._token = $('input[name="_token"]').val();
		data.report_range = $('#report-range span').text();
		data.primary_filter = $(this).val();

		$.ajax({
			url: '/survey-reports/client-cumulative/primary-filter',
			type: 'post',
			data: data,
			success: function (response) {
				if (response.data.length > 0) {
					$('.filters-container .col-sm-4:last-child').remove();
					$('.filters-container .col-sm-6').addClass('col-sm-4');
					$('.filters-container .col-sm-6').removeClass('col-sm-6');
					var placeholder;
					var html = '<div class="col-sm-4">'+
											'<select class="form-control" id="secondary-filter" multiple>';
					switch(data.primary_filter) {
						case 'Job':
							placeholder = "Select Job";
							for (var i = 0; i < response.data.length; i++)
								html += '<option value="#'+response.data[i].job_number+' - '+response.data[i].job_description+'">#'+response.data[i].job_number+' - '+response.data[i].job_description+'</option>';
							break;

						case 'Manager':
							placeholder = "Select Manager";
							for (var i = 0; i < response.data.length; i++)
								html += '<option value="'+response.data[i].manager_id+'">'+response.data[i].first_name+' '+response.data[i].last_name+'</option>';
							break;

						case 'Industry':
							placeholder = "Select Industry";
							for (var i = 0; i < response.data.length; i++)
								html += '<option value="'+response.data[i].industry+'">'+response.data[i].industry+'</option>';
							break;

						case 'Major Account':
							placeholder = "Select Major Account";
							for (var i = 0; i < response.data.length; i++)
								html += '<option value="'+response.data[i].major_account_id+'">'+response.data[i].name+'</option>';
					}
					html +=   '</select>'+
									'</div>';

					$('.filters-container').append(html);
					$('#secondary-filter').select2({
						placeholder: placeholder
					});
					$('input[name="get-report"]').prop("disabled", false);
				}
			},
			error: function (response) {
				var fields = { 'report_range': 'Report Range', 'primary_filter': 'Primary Filter' };
				var err_msg = '';
				for (var field in response.responseJSON)
					err_msg += ' ' + fields[field] + ',';
				err_msg = err_msg.replace(/.$/, "");
				sweetAlert("Oops...", err_msg + " required!", "error");
			}
		});
	});

	$('input[name="get-report"]').click(function () {
		var data = {};
		data._token = $('input[name="_token"]').val();
		data.secondary_filter = $("#secondary-filter").val();
		if (data.secondary_filter == null || data.secondary_filter == undefined) {
			sweetAlert("Secondary filter required!", "Kindly select atleast one value.", "error");
			return false;
		}

		$.ajax({
			url: '/survey-reports/client-cumulative/secondary-filter',
			type: 'post',
			data: data,
			success: function (response) {
				if (response.success !== undefined)
					window.location.reload();
			},
			error: function (response) {
				var fields = { 'secondary_filter': 'Secondary Filter' };
				var err_msg = '';
				for (var field in response.responseJSON)
					err_msg += ' ' + fields[field] + ',';
				err_msg = err_msg.replace(/.$/, "");
				sweetAlert("Oops...", err_msg + " required!", "error");
			}
		});
	});
});