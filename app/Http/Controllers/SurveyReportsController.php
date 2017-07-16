<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;
use Validator;
use Response;

class SurveyReportsController extends Controller {
	/**
	 * Method index
	 * @param null
	 *
	 * @return null
	 **/
	public function index() {
		return view("survey-reports.page");
	}

	/**
	 * Method clientCumulative to render report on screen with report data on condition basis
	 * @param null
	 *
	 * @return null
	 **/

	public function clientCumulative() {
		if (session('client_cumulative') != null) {
			$client_cumulative = session('client_cumulative');

			if ($client_cumulative['show_data'] == 1) {
				$report_data = $client_cumulative['report_data'];

				$report_data['show_data'] = 1;
				$report_data['report_range'] = $client_cumulative['report_range'];
				$report_data['primary_filter'] = $client_cumulative['primary_filter'];
				
				$formated_data = $this->formatFiltersData($report_data['primary_filter'], $client_cumulative['secondary_filter'], $report_data['filter_scores']);
				$report_data['secondary_filter'] = $formated_data[0];
				$report_data['filter_scores'] = $formated_data[1];

				$report_data['recipients_by_role'] = DB::table('role')->select('id', 'name')->get();

				return view("survey-reports.client-cumulative", $report_data);
			}
		}

		return view("survey-reports.client-cumulative", [
			'show_data'	=> 0
		]);
	}

	/**
	 * Method formatFiltersData to format report data 
	 * @param $primary string
	 * @param $secondary array
	 * @param $scores array/object
	 *
	 * @return null
	 **/
	public function formatFiltersData($primary, $secondary, $scores) {
		if ($primary == 'Manager') {
			$manager_data = $this->getManagerForIds($secondary);

			$filter_scores = [];
			foreach ($scores as $filter => $value)
				$filter_scores[$manager_data[1][$filter]] = $value;

			return [$manager_data[0], $filter_scores];
		} elseif ($primary == 'Major Account') {
			$major_account_data = $this->getMajorAccountForIds($secondary);

			$filter_scores = [];
			foreach ($scores as $filter => $value)
				$filter_scores[$major_account_data[1][$filter]] = $value;

			return [$major_account_data[0], $filter_scores];
		} elseif ($primary == 'Job') {
			$job_names = $this->getJobNamesByJobNumber($secondary);
			$filter_scores = [];
			foreach ($scores as $filter => $value)
				$filter_scores[$job_names[$filter]] = $value;

			return [$secondary, $filter_scores];
		} else {
			return [$secondary, $scores];
		}
	}

	/**
	 * Method getJobNamesByJobNumber to return job names by job number 
	 * @param $job_details array
	 *
	 * @return null
	 **/
	public function getJobNamesByJobNumber($job_details) {
		$job_names = [];
		foreach ($job_details as $job) {
			preg_match("/(?<=#).*?(?= )/", $job, $match);
			$job_names[$match[0]] = $job;
		}

		return $job_names;
	}

	/**
	 * Method getJobNamesByJobNumber to return job names by job number 
	 * @param $job_details array
	 *
	 * @return null
	 **/
	public function getManagerForIds($ids) {
		$data =  DB::table('users')
							->select('first_name', 'last_name', 'manager_id')
							->whereIn('manager_id', $ids)
							->get();

		$id_name = [];
		foreach ($data as $d)
			$id_name[$d->manager_id] = $d->first_name . ' ' . $d->last_name;

		return [$data, $id_name];
	}

	/**
	 * Method getMajorAccountForIds to return Major Account names by major account id 
	 * @param $idsarray
	 *
	 * @return null
	 **/
	public function getMajorAccountForIds($ids) {
		$data =  DB::table('major_account')
							->select('name', 'major_account_id')
							->whereIn('major_account_id', $ids)
							->get();

		$id_name = [];
		foreach ($data as $d)
			$id_name[$d->major_account_id] = $d->name;

		return [$data, $id_name];
	}

	public function primaryFilter(Request $request) {
		$data = $request->all();

		$validator = Validator::make($data, [
			'report_range'		=> 'required',
			'primary_filter'	=> 'required'
		]);

		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}

		session(['client_cumulative' => ['report_range' => $data['report_range'], 'primary_filter' => $data['primary_filter']]]);

		$report_range = explode(' - ', $data['report_range']);
		$from_ts = strtotime($report_range[0]);
		$to_ts = strtotime($report_range[1]);

		switch ($data['primary_filter']) {
			case 'Job':
				$data = $this->jobsForDateRange($from_ts, $to_ts);
				break;

			case 'Manager':
				$data = $this->managersForDateRange($from_ts, $to_ts);
				break;

			case 'Industry':
				$data = $this->industryForDateRange($from_ts, $to_ts);
				break;

			case 'Major Account':
				$data = $this->majorAccountForDateRange($from_ts, $to_ts);
				break;
		}

		return Response::json([
			'data'	=> $data
		], 200);
	}

	public function jobsForDateRange($from_ts, $to_ts) {
		$sql = "SELECT DISTINCT( job ) 
						FROM submitted_surveys 
						WHERE timestamp >= $from_ts 
						AND timestamp <= $to_ts";

		$jobs_data = DB::select($sql);

		$jobs_list = [];
		foreach ($jobs_data as $job)
			$jobs_list[] = $job->job;

		$data = DB::table('job')
						->select('job_number', 'job_description')
						->whereIn('job_number', $jobs_list)
						->get();

		return $data;
	}

	public function managersForDateRange($from_ts, $to_ts) {
		$sql = "SELECT DISTINCT( manager ) 
						FROM submitted_surveys 
						WHERE timestamp >= $from_ts 
						AND timestamp <= $to_ts";

		$managers_data = DB::select($sql);

		$managers_list = [];
		foreach ($managers_data as $manager)
			$managers_list[] = $manager->manager;

		$data = DB::table('users')
							->select('first_name', 'last_name', 'manager_id')
							->whereIn('manager_id', $managers_list)
							->get();

		return $data;
	}

	public function industryForDateRange($from_ts, $to_ts) {
		$sql = "SELECT DISTINCT( industry ) 
						FROM submitted_surveys 
						WHERE timestamp >= $from_ts 
						AND timestamp <= $to_ts";

		$data = DB::select($sql);

		return $data;
	}

	public function majorAccountForDateRange($from_ts, $to_ts) {
		$sql = "SELECT DISTINCT( job )
						FROM submitted_surveys
						WHERE timestamp >= $from_ts 
						AND timestamp <= $to_ts";

		$data = DB::select($sql);

		$jobs_list = [];
		foreach ($data as $row)
			$jobs_list[] = $row->job;
		$jobs_list = implode(", ", $jobs_list);

		$sql = "SELECT DISTINCT( mayor_account )
						FROM job
						WHERE job_number
						IN ($jobs_list)";

		$jobs_data = DB::select($sql);
		
		$major_accounts = [];
		foreach ($jobs_data as $row)
			$major_accounts[] = $row->mayor_account;
		$major_accounts = implode(", ", $major_accounts);

		$sql = "SELECT major_account_id, name
						FROM major_account
						WHERE major_account_id
						IN ($major_accounts)";

		$data = DB::select($sql);

		return $data;
	}

	public function secondaryFilter(Request $request) {
		$data = $request->all();

		$validator = Validator::make($data, [
			'secondary_filter'	=> 'required'
		]);

		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}

		$client_cumulative = session('client_cumulative');
		$client_cumulative['secondary_filter'] = $data['secondary_filter'];
		session(['client_cumulative' => $client_cumulative]);

		$report_range = explode(' - ', $client_cumulative['report_range']);
		$from_ts = strtotime($report_range[0]);
		$to_ts = strtotime($report_range[1]);

		$secondary_filter = $data['secondary_filter'];

		switch ($client_cumulative['primary_filter']) {
			case 'Job':
				$col = "job";
				$jobs_list = [];
				foreach ($secondary_filter as $job_details)
					$jobs_list[] = str_replace('#', '', explode(' - ', $job_details)[0]);
				$list = implode(", ", $jobs_list);

				$report_data = $this->getReportData($col, $list, $from_ts, $to_ts, $secondary_filter);
				break;

			case 'Manager':
				$col = "manager";
				$list = implode(", ", $secondary_filter);

				$report_data = $this->getReportData($col, $list, $from_ts, $to_ts, $secondary_filter);
				break;

			case 'Industry':
				$col = "industry";
				$list = '';
				foreach($secondary_filter as $filter)
					$list .= ",'".$filter."'";
				$list = trim($list, ',');

				$report_data = $this->getReportData($col, $list, $from_ts, $to_ts, $secondary_filter);
				break;

			case 'Major Account':
				$report_data = $this->getMajorAccountReportData($secondary_filter, $from_ts, $to_ts);
				break;
		}

		$client_cumulative = session('client_cumulative');
		$client_cumulative['show_data'] = 1;
		$client_cumulative['report_data'] = $report_data;

		session(['client_cumulative' => $client_cumulative]);

		return Response::json([
			'success'	=> 1
		], 200);
	}

	public function getReportData($col, $list, $from_ts, $to_ts, $secondary_filter) {
		$sql = "SELECT sur.name, sur.bgcolor, sub_sur.id, sub_sur.$col, sub_sur_mat.question_name AS area, AVG(sub_sur_mat.ques_score / sub_sur_mat.total_score * 100) AS score 
						FROM surveys AS sur 
						LEFT JOIN submitted_surveys AS sub_sur 
						ON sur.random_id = sub_sur.survey_random_id 
						JOIN submitted_surveys_matrix AS sub_sur_mat 
						ON sub_sur_mat.submitted_survey_id = sub_sur.id 
						WHERE sub_sur.$col IN ($list) 
						AND sub_sur.timestamp >= $from_ts 
						AND sub_sur.timestamp <= $to_ts 
						GROUP BY sur.name, sub_sur_mat.question_name";

		$report_data = DB::select($sql);

		$survey_area_scores = [];
		$survey_scores = [];
		$filter_scores = [];
		$total = 0;
		$i = 0;
		foreach ($report_data as $data) {
			$survey_area_scores[$data->name]['areas'][] = $data->area;
			$survey_area_scores[$data->name]['scores'][] = ['y' => round($data->score), 'color' => $this->get_color($data->score)];

			if (isset($survey_scores[$data->name])) {
				$survey_scores[$data->name]['total'] += round($data->score);
				$survey_scores[$data->name]['count'] += 1;
			} else {
				$survey_scores[$data->name]['bgcolor'] = $data->bgcolor;
				$survey_scores[$data->name]['total'] = round($data->score);
				$survey_scores[$data->name]['count'] = 1;
				$survey_scores[$data->name]['id'] = strtolower(str_replace(' ', '-', $data->name));
			}

			if (isset($filter_scores[$data->$col])) {
				$filter_scores[$data->$col]['total'] += round($data->score);
				$filter_scores[$data->$col]['count'] += 1;
			} else {
				$filter_scores[$data->$col]['total'] = round($data->score);
				$filter_scores[$data->$col]['count'] = 1;
			}

			$total += round($data->score);
			$i += 1;
		}

		$standard = $total / $i;

		$submitted_surveys	= $this->getSubmiitedSurveys($from_ts, $to_ts, $secondary_filter, $col);

		$area_ratings				= $this->getAreaRatings($from_ts, $to_ts, $submitted_surveys);
		$area_ratings_agg		= $this->getAreaRatingsAggregate($area_ratings['ratings']);

		$emp_surveys				= $this->getEmpSurveys($from_ts, $to_ts, $submitted_surveys);
		$emp_surveys_total	= $this->getEmpSurveysTotal($emp_surveys);

		$report_data = [
			'show_data'					=> 1,
			'standard'					=> $standard,
			'survey_scores'			=> $survey_scores,
			'filter_scores'			=> $filter_scores,
			'survey_area_scores'=> $survey_area_scores,
			'area_ratings'			=> $area_ratings,
			'area_ratings_agg'	=> $area_ratings_agg,
			'emp_surveys'				=> $emp_surveys,
			'emp_surveys_total'	=> $emp_surveys_total
		];

		return $report_data;
	}

	public function getMajorAccountReportData($secondary_filter, $from_ts, $to_ts) {
		$secondary_filter = implode(", ", $secondary_filter);

		$sql = "SELECT mayor_account, GROUP_CONCAT( job_number SEPARATOR ',' ) AS jobs
						FROM job
						WHERE mayor_account
						IN ( $secondary_filter )
						GROUP BY mayor_account";

		$data = DB::select($sql);

		$jobs_list = '';
		$job_major_account = [];
		foreach ($data as $row) {
			$jobs_list .= $row->jobs . ',';

			$jobs = explode(",", $row->jobs);
			$jobs = array_fill_keys($jobs, $row->mayor_account);
			$job_major_account = $job_major_account + $jobs;
		}
		$list = rtrim($jobs_list, ',');
		$secondary_filter = explode(",", $jobs_list);
		$col = "job";

		$sql = "SELECT sur.name, sur.bgcolor, sub_sur.id, sub_sur.$col, sub_sur_mat.question_name AS area, AVG(sub_sur_mat.ques_score / sub_sur_mat.total_score * 100) AS score 
						FROM surveys AS sur 
						LEFT JOIN submitted_surveys AS sub_sur 
						ON sur.random_id = sub_sur.survey_random_id 
						JOIN submitted_surveys_matrix AS sub_sur_mat 
						ON sub_sur_mat.submitted_survey_id = sub_sur.id 
						WHERE sub_sur.$col IN ($list) 
						AND sub_sur.timestamp >= $from_ts 
						AND sub_sur.timestamp <= $to_ts 
						GROUP BY sur.name, sub_sur_mat.question_name";

		$report_data = DB::select($sql);

		$survey_area_scores = [];
		$survey_scores = [];
		$filter_scores = [];
		$total = 0;
		$i = 0;
		foreach ($report_data as $data) {
			$survey_area_scores[$data->name]['areas'][] = $data->area;
			$survey_area_scores[$data->name]['scores'][] = ['y' => round($data->score), 'color' => $this->get_color($data->score)];

			if (isset($survey_scores[$data->name])) {
				$survey_scores[$data->name]['total'] += round($data->score);
				$survey_scores[$data->name]['count'] += 1;
			} else {
				$survey_scores[$data->name]['bgcolor'] = $data->bgcolor;
				$survey_scores[$data->name]['total'] = round($data->score);
				$survey_scores[$data->name]['count'] = 1;
				$survey_scores[$data->name]['id'] = strtolower(str_replace(' ', '-', $data->name));
			}

			$filter = $job_major_account[$data->$col];
			if (isset($filter_scores[$filter])) {
				$filter_scores[$filter]['total'] += round($data->score);
				$filter_scores[$filter]['count'] += 1;
			} else {
				$filter_scores[$filter]['total'] = round($data->score);
				$filter_scores[$filter]['count'] = 1;
			}

			$total += round($data->score);
			$i += 1;
		}

		$standard = $total / $i;

		$submitted_surveys	= $this->getSubmiitedSurveys($from_ts, $to_ts, $secondary_filter, $col);

		$area_ratings				= $this->getAreaRatings($from_ts, $to_ts, $submitted_surveys);
		$area_ratings_agg		= $this->getAreaRatingsAggregate($area_ratings['ratings']);

		$emp_surveys				= $this->getEmpSurveys($from_ts, $to_ts, $submitted_surveys);
		$emp_surveys_total	= $this->getEmpSurveysTotal($emp_surveys);

		$report_data = [
			'show_data'					=> 1,
			'standard'					=> $standard,
			'survey_scores'			=> $survey_scores,
			'filter_scores'			=> $filter_scores,
			'survey_area_scores'=> $survey_area_scores,
			'area_ratings'			=> $area_ratings,
			'area_ratings_agg'	=> $area_ratings_agg,
			'emp_surveys'				=> $emp_surveys,
			'emp_surveys_total'	=> $emp_surveys_total
		];

		return $report_data;
	}

	public function get_color($per) {
		if ($per >= 95) $color = '#1b5e20';
		elseif ($per >= 90) $color = '#4caf50';
		elseif ($per >= 80) $color = '#ffc107';
		elseif ($per >= 70) $color = '#f7941d';
		else $color = '#ef5350';

		return $color;
	}

	public function getSubmiitedSurveys($from_ts, $to_ts, $secondary_filter, $col) {
		if ($col == 'job') {
			$jobs_list = [];
			foreach ($secondary_filter as $job_details)
				$jobs_list[] = str_replace('#', '', explode(' - ', $job_details)[0]);
			$secondary_filter = $jobs_list;
		}

		$data = DB::table('submitted_surveys')
						->whereIn($col, $secondary_filter)
						->where('timestamp', '>=', $from_ts)
						->where('timestamp', '<', $to_ts)
						->get();
		$ids = [];
		foreach ($data as $d)
			$ids[] = $d->id;

		return $ids;
	}

	public function getAreaRatings($from_ts, $to_ts, $submitted_surveys) {
		$data = DB::table('submitted_surveys_ratings')
							->select('question_name as area', DB::raw('avg(rating_level) as rating'))
							->whereIn('submitted_survey_id', $submitted_surveys)
							->groupBy('question_name')
							->get();

		$area_ratings = ['areas' => [], 'ratings' => []];
		foreach ($data as $d) {
			$area_ratings['areas'][] = $d->area;
			$area_ratings['ratings'][] = ['y' => round((int)$d->rating, 1), 'color' => $this->get_rating_color(round((int)$d->rating, 1))];
		}

		return $area_ratings;
	}

	public function get_rating_color($rating) {
		$per = $rating * 20;
		if ($per >= 95) $color = '#1b5e20';
		elseif ($per >= 90) $color = '#4caf50';
		elseif ($per >= 80) $color = '#ffc107';
		elseif ($per >= 70) $color = '#f7941d';
		else $color = '#ef5350';

		return $color;
	}

	public function getAreaRatingsAggregate($ratings) {
		$total = 0;
		foreach ($ratings as $rating)
			$total += $rating['y'];
		$area_ratings_agg = $total / count($ratings);

		return $area_ratings_agg;
	}

	public function getEmpSurveys($from_ts, $to_ts, $submitted_surveys) {
		return DB::table('submitted_surveys')
						->select('user_id', DB::raw('count(*) as count'))
						->whereIn('id', $submitted_surveys)
						->groupBy('user_id')
						->get();
	}

	public function getEmpSurveysTotal($emp_surveys) {
		$emp_surveys_total = [];
		$emp_surveys_total['total'] = 0;
		foreach ($emp_surveys as $data)
			$emp_surveys_total['total'] += $data->count;

		foreach ($emp_surveys as $data)
			$emp_surveys_total[$data->user_id] = $data->count / $emp_surveys_total['total'] * 100;

		return $emp_surveys_total;
	}

	public function clientPerSurvey(Request $request) {
		$jobs_list = DB::table('job')
									->where('active', 1)
									->get();

		if (null != session('client_per_survey') && isset($request->s)) {
			$client_per_survey = session('client_per_survey');

			$signature = DB::table('submitted_surveys')
										->select('signature')
										->where('id', $request->s)
										->first();

			$matrix = DB::table('submitted_surveys_matrix')
								->select('question_name as area', 'matrix', DB::raw('ques_score / total_score * 100 as score'))
								->where('submitted_survey_id', $request->s)
								->get();

			$comments = DB::table('submitted_surveys_comments')
									->select('question_name as area', 'comments')
									->where('submitted_survey_id', $request->s)
									->get();

			$images = DB::table('submitted_surveys_images')
									->select('question_name as area', 'images')
									->where('submitted_survey_id', $request->s)
									->get();

			$sql = "SELECT sur.signature, mat.question_name AS area, mat.matrix, (mat.ques_score / mat.total_score * 100) AS score, com.comments, img.images, rat.rating_level 
							FROM submitted_surveys AS sur 
							LEFT JOIN submitted_surveys_matrix AS mat 
							ON sur.id = mat.submitted_survey_id 
							LEFT JOIN submitted_surveys_comments AS com 
							ON sur.id = com.submitted_survey_id 
							LEFT JOIN submitted_surveys_images AS img 
							ON sur.id = img.submitted_survey_id 
							LEFT JOIN submitted_surveys_ratings AS rat 
							ON sur.id = rat.submitted_survey_id 
							WHERE sur.id = $request->s 
							AND mat.question_name = com.question_name 
							AND mat.question_name = img.question_name
							AND mat.question_name = rat.question_name";

			$survey_data = DB::select($sql);

			return view('survey-reports.client-per-survey', [
				'show_data'		=> 1,
				'jobs_list'		=> $jobs_list,
				'survey_data'	=> $survey_data,
				'facility'		=> $client_per_survey['job'],
				'auditors'		=> $client_per_survey['auditors'],
				'user'				=> $client_per_survey['user'],
			]);
		}

		return view('survey-reports.client-per-survey', [
			'show_data'		=> 0,
			'jobs_list'		=> $jobs_list,
			'survey_data'	=> [],
			'facility'		=> '',
			'auditors'		=> '',
			'user'				=> '',
		]);
	}

	public function clientPerSurveyAuditors(Request $request) {
		$job = explode(' - ', $request->job);
		$job_number = str_replace('#', '', $job[0]);
		$job_name = $job[1];

		$sql = "SELECT sub_sur.user_id, users.first_name, users.last_name 
						FROM submitted_surveys AS sub_sur 
						LEFT JOIN users AS users 
						ON sub_sur.user_id = users.id 
						WHERE job = $job_number 
						GROUP BY sub_sur.user_id";

		$data = DB::select($sql);

		session(['client_per_survey' => ['job' => $request->job, 'auditors' => $data]]);

		return Response::json([
			'auditors'	=> $data
		], 200);
	}

	public function clientPerSurveyList(Request $request) {
		$client_per_survey = session('client_per_survey');
		$client_per_survey['user'] = $request->user;

		session(['client_per_survey' => $client_per_survey]);

		$job = explode(' - ', $client_per_survey['job']);
		$job_number = str_replace('#', '', $job[0]);
		$job_name = $job[1];

		$sql = "SELECT sub_sur.id, sub_sur.include_ques AS area, sub_sur.created_at, sub_sur.updated_at, sub_sur.status, sur.name 
						FROM submitted_surveys AS sub_sur 
						LEFT JOIN surveys AS sur 
						ON sub_sur.survey_random_id = sur.random_id
						WHERE sub_sur.job = $job_number 
						AND sub_sur.user_id = $request->user";

		$data = DB::select($sql);

		return Response::json([
			'surveys'	=> $data
		], 200);
	}
}