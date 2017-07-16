<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Auth;
use Crypt;
use Config;
use Response;
use Carbon\Carbon;

use App\Survey;
use App\SurveyJob;
use App\SurveyQuestions;
use App\Question;
use App\IndustryQuestion;
use App\ Industry;


class QualityController extends Controller {
	public function getIndex() {
		$role_user						= Auth::user()->getRole();
		$type_survey					= "Full";
		$label_survey					= "Full";
		$manager_id						= 0;
		$primary_job					= 0;
		$job_list_health			= [];
		$job_list_education		= [];
		$job_list_commercial	= [];
		$job_list_hospitality	= [];
		$job_list_government	= [];
		$job_list_publicvenue	= [];
		$job_list_retail			= [];
		$job_list_industrial	= [];
		$survey_link 					= "/survey/form/";
		$industry_list 				= [
			'1'	=> 'Healthcare',
			'3'	=> 'Education',
			'4'	=> 'Commercial',
			'5'	=> 'Hospitality',
			'6'	=> 'Government',
			'7'	=> 'PublicVenue',
			'8'	=> 'Retail',
			'9'	=> 'Industrial',
		];

		if ($role_user == Config::get('roles.AREA_MANAGER')) {
			$manager_id = Auth::user()->getManagerId();

			$job_list_health = DB::table('job')
														->where('manager', $manager_id)
														->where('division', 1)
														->where('active', 1)
														->get();

			$job_list_education = DB::table('job')
															->where('manager', $manager_id)
															->where('division', 3)
															->where('active', 1)
															->get();

			$job_list_commercial = DB::table('job')
																->where('manager', $manager_id)
																->where('division', 4)
																->where('active', 1)
																->get();

			$job_list_hospitality = DB::table('job')
																->where('manager', $manager_id)
																->where('division', 5)
																->where('active', 1)
																->get();

			$job_list_government = DB::table('job')
																->where('manager', $manager_id)
																->where('division', 6)
																->where('active', 1)
																->get();

			$job_list_publicvenue = DB::table('job')
																->where('manager', $manager_id)
																->where('division', 7)
																->where('active', 1)
																->get();

			$job_list_retail = DB::table('job')
														->where('manager', $manager_id)
														->where('division', 8)
														->where('active', 1)
														->get();

			$job_list_industrial = DB::table('job')
																->where('manager', $manager_id)
																->where('division', 9)
																->where('active', 1)
																->get();
		} elseif ($role_user == Config::get('roles.SUPERVISOR') || $role_user == Config::get('roles.AREA_SUPERVISOR')) {
			$type_survey = "Supervisor";
			$label_survey = "Supervisor";
			$primary_job = Auth::user()->gePrimayJob();

			$job_query = DB::table('job')
											->where('job_number', $primary_job)
											->get();

			foreach ($job_query as $value)
				$manager_id=$value->manager;

			$job_list_health = DB::table('job')
														->where('manager', $manager_id)
														->where('division', 1)
														->where('active', 1)
														->get();

			$job_list_education = DB::table('job')
															->where('manager', $manager_id)
															->where('division', 3)
															->where('active', 1)
															->get();

			$job_list_commercial = DB::table('job')
																->where('manager', $manager_id)
																->where('division', 4)
																->where('active', 1)
																->get();

			$job_list_hospitality = DB::table('job')
																->where('manager', $manager_id)
																->where('division', 5)
																->where('active', 1)
																->get();

			$job_list_government = DB::table('job')
																->where('manager', $manager_id)
																->where('division', 6)
																->where('active', 1)
																->get();

			$job_list_publicvenue = DB::table('job')
																->where('manager', $manager_id)
																->where('division', 7)
																->where('active', 1)
																->get();

			$job_list_retail = DB::table('job')
														->where('manager', $manager_id)
														->where('division', 8)
														->where('active', 1)
														->get();


			$job_list_industrial = DB::table('job')
																->where('manager', $manager_id)
																->where('division', 9)
																->where('active', 1)
																->get();
							
		} elseif ($role_user==Config::get('roles.DIR_POS') || $role_user==Config::get('roles.DASHBOARD_MANAGER')) {
			$job_list_health = DB::table('job')
														->where('division', 1)
														->where('active', 1)
														->get();

			$job_list_education = DB::table('job')
															->where('division', 3)
															->where('active', 1)
															->get();

			$job_list_commercial = DB::table('job')
																->where('division', 4)
																->where('active', 1)
																->get();

			$job_list_hospitality = DB::table('job')
																->where('division', 5)
																->where('active', 1)
																->get();

			$job_list_government = DB::table('job')
																->where('division', 6)
																->where('active', 1)
																->get();

			$job_list_publicvenue = DB::table('job')
																->where('division', 7)
																->where('active', 1)
																->get();

			$job_list_retail = DB::table('job')
														->where('division', 8)
														->where('active', 1)
														->get();


			$job_list_industrial = DB::table('job')
																->where('division', 9)
																->where('active', 1)
																->get();
		}

		$survey_data = Survey::where('is_deleted', 0)
													->where('is_active', 1)
													->get();
		$surveys = [];
		$survey_bg = [];
		$active_surveys_ids = [];
		foreach ($survey_data as $sd) {
			$surveys[$sd['id']] = [
				'survey_id'	=> $sd['id'],
				'name' 			=> $sd['name'],
				'random_id'	=> $sd['random_id'],
				'express'		=> $sd['has_express']
			];

			$survey_bg[$sd['random_id']] = $sd['bgcolor'];

			$active_surveys_ids[] = $sd['id'];
		}

		$survey_jobs_data = SurveyJob::whereIn('survey_id', $active_surveys_ids)->get();
		$job_survey = new \stdClass();
		foreach ($survey_jobs_data as $sjd)
			if (isset($job_survey->$sjd['job_number']))
				array_push($job_survey->$sjd['job_number'], $surveys[$sjd['survey_id']]);
			else
				$job_survey->$sjd['job_number'] = [$surveys[$sjd['survey_id']]];

		$questions_data = Question::all(['id', 'name']);
		$questions_name = [];
		foreach ($questions_data as $qd)
			$questions_name[$qd['id']] = $qd['name'];

		$survey_questions_data = SurveyQuestions::all();
		$survey_questions = new \stdClass();
		foreach ($survey_questions_data as $sqd)
			if (isset($survey_questions->$sqd['survey_id'])) {
				if (!in_array($questions_name[$sqd['question_id']], $survey_questions->$sqd['survey_id']))
					array_push($survey_questions->$sqd['survey_id'], $questions_name[$sqd['question_id']]);
			} else {
				$survey_questions->$sqd['survey_id'] = [$questions_name[$sqd['question_id']]];
			}

		$industry_data = Industry::all();
		$industries = [];
		foreach ($industry_data as $i)
			$industries[$i->id] = $i->name;

		$industry_question_data = IndustryQuestion::all();
		$industry_question = [];
		foreach ($industry_question_data as $iqd) {
			if (isset($industry_question[$industries[$iqd->industry_id]]))
				$industry_question[$industries[$iqd->industry_id]][] = $questions_name[$iqd->question_id];
			else
				$industry_question[$industries[$iqd->industry_id]] = [$questions_name[$iqd->question_id]];
		}

		return view('quality.page', [
			'job_list_health'			=> $job_list_health,
			'job_list_education'	=> $job_list_education,
			'job_list_commercial'	=> $job_list_commercial,
			'job_list_hospitality'=> $job_list_hospitality,
			'job_list_government'	=> $job_list_government,
			'job_list_publicvenue'=> $job_list_publicvenue,
			'job_list_retail'			=> $job_list_retail,
			'job_list_industrial'	=> $job_list_industrial,
			'type_survey'					=> $type_survey,
			'primary_job'					=> $primary_job,
			'survey_link'					=> $survey_link,
			'label_survey'				=> $label_survey,
			'industry_list'				=> $industry_list,
			'job_survey'					=> $job_survey,
			'survey_questions'		=> $survey_questions,
			'industry_question'		=> $industry_question,
			'survey_bg'						=> $survey_bg
		]);
	}

	public function getUrl(Request $request) {
		$data = $request->all();

		$link = "/survey/form/".$data['survey']."?data=".Crypt::encrypt("Industry=".$data['industry']."&Version=Full&Job=".$data['job']."&Random=0&Manager=".$data['manager']."&Question=".$data['question']);

		return Response::json([
			'success'	=> 1,
			'link'		=> $link
		], 200);
	}
}