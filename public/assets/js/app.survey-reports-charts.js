function chart_service_standards(standard) {
	var html = '<hr>\
							<div class="row">\
								<div class="col-sm-12">\
									<div>\
										<p class="service-standarts-text">Service Standards: '+standard+'%</p>\
									</div>\
									<div id="chart-standard" style="height: 400px; width: 100%;">\
									</div>\
								</div>\
							</div>';
	$('.report-data').append(html);

	Highcharts.chart('chart-standard', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: 0,
			plotShadow: false
		},
		title: {
			text: '',
			align: 'center',
			verticalAlign: 'middle',
			y: 40
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		plotOptions: {
			pie: {
				dataLabels: {
					enabled: true,
					distance: -50,
					style: {
						fontWeight: 'bold',
						color: 'white'
					}
				},
				startAngle: -90,
				endAngle: 90,
				center: ['50%', '75%']
			}
		},
		series: [{
			size: '150%',
			type: 'pie',
			name: 'Service Standards',
			innerSize: '50%',
			colors: [get_color(standard), '#ddd'],
			data: [
				['Service Standards', standard],
				{
					name: 'None',
					y: 100 - standard,
					dataLabels: {
						enabled: false
					}
				}
			]
		}]
	});
}

function chart_filter_scores(filter_scores) {
	filter_scores = JSON.parse(filter_scores.replace(/&quot;/g,'"'));
	var categories = [];
	var data = [];
	var score;

	for (var filter in filter_scores) {
		categories.push(filter);
		score = filter_scores[filter].total / filter_scores[filter].count;
		data.push({y: score, color: get_color(score)});
	}

	html = '<hr>\
					<div class="row">\
						<div class="col-sm-12">\
							<div id="chart-filter-agg" style="height: 300px;">\
							</div>\
						</div>\
					</div>';
	$('.report-data').append(html);

	Highcharts.chart('chart-filter-agg', {
		chart: {
			type: 'column'
		},
		title: {
			text: 'Filter Aggregate'
		},
		xAxis: {
			categories: categories
		},
		yAxis: {
			title: {
				text: 'Percentage'
			}
		},
		series: [{
			name: 'Filter Aggregate',
			data: data
		}]
	});
}

function chart_survey_scores(survey_scores) {
	survey_scores = JSON.parse(survey_scores.replace(/&quot;/g,'"'));
	var categories = [];
	var data = [];
	var score;

	for (var survey in survey_scores) {
		categories.push(survey);

		score = survey_scores[survey].total / survey_scores[survey].count;
		data.push({y: score, color: get_color(score)});
	}

	html = '<hr>\
					<div class="row">\
						<div class="col-sm-12">\
							<div id="chart-survey-agg" style="height: 300px;">\
							</div>\
						</div>\
					</div>';
	$('.report-data').append(html);

	Highcharts.chart('chart-survey-agg', {
		chart: {
			type: 'column'
		},
		title: {
			text: 'Survey Aggregate'
		},
		xAxis: {
			categories: categories
		},
		yAxis: {
			title: {
				text: 'Percentage'
			}
		},
		series: [{
			name: 'Survey Aggregate',
			data: data
		}]
	});
}

function chart_survey_area_scores(survey_area_scores) {
	survey_area_scores = JSON.parse(survey_area_scores.replace(/&quot;/g,'"'));
	var survey_name;
	var score_agg;

	for (var survey in survey_area_scores) {
		survey_name = survey.replace(/ /g, '-').replace('/', '-').toLowerCase();
		score_agg = get_score_agg(survey_area_scores[survey].scores);

		html = '<div class="panel panel-default panel-black">\
							<div class="panel-heading">\
								<h4 class="panel-title">\
									<a data-toggle="collapse" href="#'+survey_name+'">'+survey+'</a>\
								</h4>\
								<span class="agre">'+score_agg+'</span>\
							</div>\
							<div id="'+survey_name+'" class="panel-collapse collapse in">\
								<div class="panel-body">\
									<div class="row">\
										<div class="col-sm-12">\
											<div id="chart-'+survey_name+'" style="height: 200px; width: 100%;">\
											</div>\
										</div>\
									</div>\
								</div>\
							</div>\
						</div>';
		$('.report-data').append(html);

		Highcharts.chart(survey_name, {
			chart: {
				type: 'bar'
			},
			title: {
				text: survey
			},
			xAxis: {
				categories: survey_area_scores[survey].areas
			},
			yAxis: {
				title: {
					text: 'Percentage'
				}
			},
			series: [{
				name: survey,
				data: survey_area_scores[survey].scores
			}]
		});
	}
}

function chart_area_ratings(area_ratings, area_ratings_agg) {
	area_ratings = JSON.parse(area_ratings.replace(/&quot;/g,'"'));

	var html = '<div class="panel panel-default panel-black">\
								<div class="panel-heading">\
									<h4 class="panel-title">\
										<a data-toggle="collapse" href="#appa-by-area">APPA BY AREA</a>\
									</h4>\
									<span class="agre">'+area_ratings_agg+'</span>\
								</div>\
								<div id="appa-by-area" class="panel-collapse collapse in">\
									<div class="panel-body">\
										<div class="row">\
											<div class="col-sm-12">\
												<div id="chart-appa-agg" style="height: 300px; width: 100%;">\
												</div>\
											</div>\
										</div>\
									</div>\
								</div>\
							</div>';
	$('.report-data').append(html);

	Highcharts.chart('chart-appa-agg', {
		chart: {
			type: 'bar'
		},
		title: {
			text: 'APPA Aggregate'
		},
		xAxis: {
			categories: area_ratings.areas
		},
		yAxis: {
			title: {
				text: 'Percentage'
			}
		},
		series: [{
			name: 'APPA Aggregate',
			data: area_ratings.ratings
		}]
	});
}

function chart_respondents(emp_surveys, total) {
	emp_surveys = JSON.parse(emp_surveys.replace(/&quot;/g,'"'));

	var html = '<div class="panel panel-default panel-black">\
								<div class="panel-heading">\
									<h4 class="panel-title">\
										<a data-toggle="collapse" href="#emp-data">Survey Count By Employee</a>\
									</h4>\
									<span class="agre">'+total+'</span>\
								</div>\
								<div id="emp-data" class="panel-collapse collapse in">\
									<div class="panel-body">\
										<div class="row">\
											<div class="col-sm-12">';
	for (var i = 0; i < emp_surveys.length; i++) {
		html += '<div class="emp-count-details">\
							<div class="par-circle">'+emp_surveys[i].count+'</div>\
							<p>#'+emp_surveys[i].user_id+'</p>\
						</div>';
	}
	html +=							'</div>\
										</div>\
									</div>\
								</div>\
							</div>';
	$('.report-data').append(html);
}