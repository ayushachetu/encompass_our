<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use Mail;

use App\Http\Controllers\SurveyReportsController;

class ScheduledReports extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'scheduled-reports';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate and send scheduled reports';


	protected $survey_reports;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->survey_reports = new SurveyReportsController;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$day = date('l');
		$date = date('j');
		$date_month = date('j-F');
		$date_month_year = date('j-F-Y');
		$scheduled_jobs = DB::table('scheduled_jobs')
											->whereIn('send_on', [$day, $date, $date_month, $date_month_year])
											->where('is_active', 1)
											->get();
		if (count($scheduled_jobs) > 0) {
			foreach ($scheduled_jobs as $scheduled_job) {
				if ($scheduled_job->report_type == 'client-cumulative') {
					$from_ts = strtotime(date('d-m-Y') . ' -' . $scheduled_job->report_range . ' days');
					$to_ts = strtotime(date('d-m-Y'));
					$secondary_filter = $scheduled_job->secondary_filter;
					switch ($scheduled_job->primary_filter) {
						case 'Job':
							$col = "job";
							$jobs_list = [];
							$secondary_filter = explode(', ', $secondary_filter);
							foreach ($secondary_filter as $job_details)
								$jobs_list[] = str_replace('#', '', explode(' - ', $job_details)[0]);
							$list = implode(", ", $jobs_list);
							$report_data = $this->survey_reports->getReportData($col, $list, $from_ts, $to_ts, $secondary_filter);
							break;
						case 'Manager':
							$col = "manager";
							$list = $secondary_filter;
							$secondary_filter = explode(',', $secondary_filter);
							$report_data = $this->survey_reports->getReportData($col, $list, $from_ts, $to_ts, $secondary_filter);
							break;
						case 'Industry':
							$col = "industry";
							$list = '';
							$secondary_filter = explode(', ', $secondary_filter);
							foreach($secondary_filter as $filter)
								$list .= ",'".$filter."'";
							$list = trim($list, ',');
							$report_data = $this->survey_reports->getReportData($col, $list, $from_ts, $to_ts, $secondary_filter);
							break;
						case 'Major Account':
							$secondary_filter = explode(', ', $secondary_filter);
							$report_data = $this->survey_reports->getMajorAccountReportData($secondary_filter, $from_ts, $to_ts);
							break;
					}
					$report_body = $this->getReportBody($report_data);

					$this->sendReport($report_body, $scheduled_job->recipients_by_roles, $scheduled_job->custom_recipients, $report_data['survey_scores']);
				}
			}
		}
	}

	private function getReportBody($report_data) {
		$body = [];
		$options = $this->getServiceStandardsChartOptions($report_data['standard']);
		$html = '<hr>
						<div class="row">
							<div class="col-sm-12">
								<img src="'.$this->getChartImage($options).'">
							</div>
						</div>';
		$body['standard_chart'] = $html;

		$options = $this->getFilterScoresChartOptions($report_data['filter_scores']);
		$html = '<hr>
						<div class="row">
							<div class="col-sm-12">
								<img src="'.$this->getChartImage($options).'">
							</div>
						</div>';
		$body['filter_chart'] = $html;

		$options = $this->getSurveyScoresChartOptions($report_data['survey_scores']);
		$html = '<hr>
						<div class="row">
							<div class="col-sm-12">
								<img src="'.$this->getChartImage($options).'">
							</div>
						</div>';
		$body['survey_chart'] = $html;

		foreach ($report_data['survey_area_scores'] as $survey => $area_scores) {
			$survey_name = strtolower(str_replace("/", "-", str_replace(" ", "-", $survey)));
			$score_agg = $this->getScoreAgg($area_scores['scores']);
			$options = $this->getSurveyAreaScoresChartsOptions($survey, $area_scores['areas'], $area_scores['scores']);
			$html = '<div class="panel panel-default panel-black">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" href="#'.$survey_name.'">'.$survey.'</a>
									</h4>
									<span class="agre">'.$score_agg.'</span>
								</div>
								<div id="'.$survey_name.'" class="panel-collapse collapse in">
									<div class="panel-body">
										<div class="row">
											<div class="col-sm-12">
												<img src="'.$this->getChartImage($options).'">
											</div>
										</div>
									</div>
								</div>
							</div>';
			$body['per_survey_cahrt'][$survey] = $html;
		}

		$options = $this->getAreaRatingsChartOptions($report_data['area_ratings']['areas'], $report_data['area_ratings']['areas']);
		$html = '<div class="panel panel-default panel-black">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" href="#appa-by-area">APPA BY AREA</a>
								</h4>
								<span class="agre">'.$report_data['area_ratings_agg'].'</span>
							</div>
							<div id="appa-by-area" class="panel-collapse collapse in">
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-12">
											<img src="'.$this->getChartImage($options).'">
										</div>
									</div>
								</div>
							</div>
						</div>';
		$body['rating_chart'] = $html;

		$html = '<div class="panel panel-default panel-black">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" href="#emp-data">Survey Count By Employee</a>
								</h4>
								<span class="agre">'.$report_data['emp_surveys_total']['total'].'</span>
							</div>
							<div id="emp-data" class="panel-collapse collapse in">
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-12">';
		for ($i = 0; $i < count($report_data['emp_surveys']); $i++) {
			$html .= '<div class="emp-count-details">
									<div class="par-circle">'.$report_data['emp_surveys'][$i]->count.'</div>
									<p>#'.$report_data['emp_surveys'][$i]->user_id.'</p>
								</div>';
		}
		$html .=					'</div>
										</div>
									</div>
								</div>
							</div>';
		$body['emp_count'] = $html;

		return $body;
	}

	private function getServiceStandardsChartOptions($standard) {
		return "{
			chart: {plotBackgroundColor: null,plotBorderWidth: 0,plotShadow: false},
			title: {text: '',align: 'center',verticalAlign: 'middle',y: 40},
			tooltip: {pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'},
			plotOptions: {
				pie: {dataLabels: {enabled: true,distance: -50,style: {fontWeight: 'bold',color: 'white'}},startAngle: -90,endAngle: 90,center: ['50%', '75%']}
			},
			series: [{
				size: '150%',
				type: 'pie',
				name: 'Service Standards',
				innerSize: '50%',
				colors: ['".$this->survey_reports->get_color($standard)."', '#ddd'],
				data: [['Service Standards', ".$standard."],{name: 'None',y: 100 - ".$standard.",dataLabels: {enabled: false}}]
			}]
		}";
	}

	private function getFilterScoresChartOptions($filter_scores) {
		$categories = [];
		$data = [];

		foreach ($filter_scores as $filter => $scores) {
			$categories[] = $filter;
			$score = $scores['total'] / $scores['count'];
			$data[] = ['y' => $score, 'color' => $this->survey_reports->get_color($score)];
		}
		$categories = json_encode($categories);
		$data = json_encode($data);

		return "{
			chart: {type: 'column'},
			title: {text: 'Filter Aggregate'},
			xAxis: {categories: $categories},
			yAxis: {title: {text: 'Percentage'}},
			series: [{name: 'Filter Aggregate',data: $data}]
		}";
	}

	private function getSurveyScoresChartOptions($survey_scores) {
		$categories = [];
		$data = [];

		foreach ($survey_scores as $survey => $scores) {
			$categories[] = $survey;
			$score = $scores['total'] / $scores['count'];
			$data[] = ['y' => $score, 'color' => $this->survey_reports->get_color($score)];
		}
		$categories = json_encode($categories);
		$data = json_encode($data);

		return "{
			chart: {type: 'column'},
			title: {text: 'Survey Aggregate'},
			xAxis: {categories: $categories},
			yAxis: {title: {text: 'Percentage'}},
			series: [{name: 'Survey Aggregate',data: $data}]
		}";
	}

	private function getSurveyAreaScoresChartsOptions($survey, $areas, $scores) {
		$survey = json_encode($survey);
		$areas = json_encode($areas);
		$scores = json_encode($scores);

		return "{
			chart: {type: 'bar'},
			title: {text: $survey},
			xAxis: {categories: $areas},
			yAxis: {title: {text: 'Percentage'}},
			series: [{name: $survey,data: $scores}]
		}";
	}

	private function getAreaRatingsChartOptions($areas, $ratings) {
		$areas = json_encode($areas);
		$ratings = json_encode($ratings);

		return "{
			chart: {type: 'bar'},
			title: {text: 'APPA Aggregate'},
			xAxis: {categories: $areas},
			yAxis: {title: {text: 'Percentage'}},
			series: [{name: 'APPA Aggregate',data: $ratings}]
		}";
	}

	private function getChartImage($options) {
		$url = "http://export.highcharts.com/";
		$headers = [
			"Accept-Encoding"	=>	"gzip, deflate",
			"Accept-Language"	=> "en-US,en;q=0.8",
			"Cache-Control"		=> "no-cache",
			"Connection"			=> "keep-alive",
			"Content-Length"	=> "173",
			"Content-Type"		=> "application/x-www-form-urlencoded; charset=UTF-8",
			"Host"						=> "export.highcharts.com",
			"Pragma"					=> "no-cache"
		];

		$user_agent = "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36";

		$fields = [
			"async"		=> urlencode("true"),
			"type"		=> urlencode("jpeg"),
			"width"		=> urlencode("600"),
			"options"	=> urlencode($options),
		];

		$fields_string = '';
		foreach($fields as $key=>$value)
			$fields_string .= $key.'='.$value.'&';
		rtrim($fields_string, '&');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		curl_close($ch);

		return $url . $response;
	}

	private function getScoreAgg($scores) {
		$total = 0;
		$len = count($scores);

		for ($i = 0; $i < $len; $i++)
			$total += $scores[$i]['y'];

		return $total / $len;
	}

	private function sendReport($body, $recipients_by_roles, $custom_recipients, $survey_scores) {
		$custom_emails = explode(',', $custom_recipients);

		Mail::send(['html' => 'emails.reports'], ['body' => $body], function ($message) use ($custom_emails) {
			$message->from('no-repoly@encompassonsite.com', 'EncompassOnsite');
			$message->bcc($custom_emails);
			$message->subject('EncompassOnsite Survey Reports');
		});

		$all_surveys = array_keys($survey_scores);

		$role_emails = DB::select("SELECT CONVERT(role_id, CHAR(10)) AS role_id,
																GROUP_CONCAT(email SEPARATOR ',') AS emails
															FROM users
															WHERE role_id IN($recipients_by_roles)
															OR manager_parent <> 0
															GROUP BY role_id");

		$emails_by_role = [];
		foreach ($role_emails as $index => $data)
			$emails_by_role[$data->role_id] = explode(',', $data->emails);

		$role_surveys = DB::select("SELECT CONVERT(role_survey.role_id, CHAR(10)) AS role_id,
																	GROUP_CONCAT(surveys.name SEPARATOR ',') AS surveys
																FROM role_survey
																LEFT JOIN surveys
																ON surveys.id = role_survey.survey_id
																WHERE role_survey.role_id IN($recipients_by_roles)
																GROUP BY role_survey.role_id");

		foreach ($role_surveys as $index => $data) {
			$emails = $emails_by_role[$data->role_id];
			$survey_diff = array_diff($all_surveys, explode(',', $data->surveys));

			if (!count($survey_diff)) {
				Mail::send(['html' => 'emails.reports'], ['body' => $body], function ($message) use ($emails) {
					$message->from('no-repoly@encompassonsite.com', 'EncompassOnsite');
					$message->bcc($emails);
					$message->subject('EncompassOnsite Survey Reports');
				});
			} else {
				$new_body = $body;
				foreach ($survey_diff as $survey) {
					unset($survey_scores[$survey]);
					unset($new_body['per_survey_cahrt'][$survey]);
				}

				$options = $this->getSurveyScoresChartOptions($survey_scores);
				$new_body['survey_chart'] = '<hr>
																		<div class="row">
																			<div class="col-sm-12">
																				<img src="'.$this->getChartImage($options).'">
																			</div>
																		</div>';

				Mail::send(['html' => 'emails.reports'], ['body' => $new_body], function ($message) use ($emails) {
					$message->from('no-repoly@encompassonsite.com', 'EncompassOnsite');
					$message->bcc($emails);
					$message->subject('EncompassOnsite Survey Reports');
				});
			}
		}
	}
}