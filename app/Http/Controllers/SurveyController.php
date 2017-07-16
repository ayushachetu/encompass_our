<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;
use Auth;
use Config;
use Validator;
use Response;

use App\Survey;
use App\SurveyJob;
use App\SurveyQuestions;
use App\Question;
use App\Industry;
use App\IndustryQuestion;
use App\MatrixOption;

class SurveyController extends Controller {
	public function index() {
		$role_user = Auth::user()->getRole();
		if ($role_user == Config::get('roles.DIR_POS')) {
			$user_id = Auth::user()->id;

			$survey_data = Survey::where('is_deleted', 0)
														->get();

			$health_jobs = DB::table('job')
												->where('division', 1)
												->where('active', 1)
												->get();

			$education_jobs = DB::table('job')
													->where('division', 3)
													->where('active', 1)
													->get();

			$commercial_jobs	= DB::table('job')
														->where('division', 4)
														->where('active', 1)
														->get();

			$hospitality_jobs = DB::table('job')
														->where('division', 5)
														->where('active', 1)
														->get();

			$government_jobs = DB::table('job')
														->where('division', 6)
														->where('active', 1)
														->get();

			$publicvenue_jobs = DB::table('job')
														->where('division', 7)
														->where('active', 1)
														->get();

			$retail_jobs = DB::table('job')
												->where('division', 8)
												->where('active', 1)
												->get();

			$industrial_jobs = DB::table('job')
														->where('division', 9)
														->where('active', 1)
														->get();

			return view("survey.page", [
				'user_id'					=> $user_id,
				'role_user'				=> $role_user,
				'survey_data'			=> $survey_data,
				'health_jobs'			=> $health_jobs,
				'education_jobs'	=> $education_jobs,
				'commercial_jobs'	=> $commercial_jobs,
				'hospitality_jobs'=> $hospitality_jobs,
				'government_jobs'	=> $government_jobs,
				'publicvenue_jobs'=> $publicvenue_jobs,
				'retail_jobs'			=> $retail_jobs,
				'industrial_jobs'	=> $industrial_jobs
			]);
		}
	}

	public function store(Request $request) {
		$role_user = Auth::user()->getRole();
		if ($role_user == Config::get('roles.DIR_POS')) {
			$data = $request->all();

			$validator = Validator::make($data, [
				'name'					=> 'required',
				'description'		=> 'required',
				'type'					=> 'required',
				'has_express'		=> 'required',
				'jobs'					=> 'required|array',
				'bgcolor'				=> 'required'
			]);
			if ($validator->fails()) {
				$this->throwValidationException(
					$request, $validator
				);
			}

			$random_id = uniqid();
			$created_by = Auth::user()->id;
			$survey_data = [
				'random_id'		=> $random_id,
				'name'				=> $data['name'],
				'description'	=> $data['description'],
				'type'				=> $data['type'],
				'has_express'	=> $data['has_express'],
				'created_by'	=> $created_by,
				'is_active'		=> 0,
				'is_deleted'	=> 0,
				'bgcolor'			=> $data['bgcolor']
			];

			$survey = Survey::create($survey_data);

			$survey_id = $survey->id;
			$survey_jobs = [];
			foreach ($data['jobs'] as $job) {
				$survey_jobs[] = [
					'survey_id'		=> $survey_id,
					'job_number'	=> $job
				];
			}
			SurveyJob::insert($survey_jobs);

			return Response::json([
				'id'	=> $survey_id
			], 201);
		}
	}

	public function edit(Request $request) {
		$role_user = Auth::user()->getRole();
		if ($role_user == Config::get('roles.DIR_POS')) {
			$data = $request->all();

			$validator = Validator::make($data, [
				'name'					=> 'required',
				'description'		=> 'required',
				'type'					=> 'required',
				'has_express'		=> 'required',
				'jobs'					=> 'required|array',
				'bgcolor'				=> 'required'
			]);
			if ($validator->fails()) {
				$this->throwValidationException(
					$request, $validator
				);
			}

			$id = session('survey_id');

			$survey_data = [
				'name'				=> $data['name'],
				'description'	=> $data['description'],
				'type'				=> $data['type'],
				'has_express'	=> $data['has_express'],
				'bgcolor'			=> $data['bgcolor']
			];
			$survey = Survey::where('id', $id)
											->update($survey_data);

			$old_jobs_data = SurveyJob::where('survey_id', '=', $id)
																->get();
			$old_jobs = [];
			foreach ($old_jobs_data as $ojd)
				$old_jobs[] = $ojd->job_number;

			$new_jobs = $data['jobs'];

			$to_add = [];
			foreach (array_diff($new_jobs, $old_jobs) as $job)
				$to_add[] = [
					'survey_id' 	=> $id,
					'job_number'	=> $job
				];
			SurveyJob::insert($to_add);

			$to_delete = array_diff($old_jobs, $new_jobs);
			DB::table('surveys_jobs')
				->where('survey_id', '=', $id)
				->whereIn('job_number', $to_delete)
				->delete();

			return Response::json([
				'success'	=> 1
			], 201);
		}
	}

	public function delete(Request $request) {
		$role_user = Auth::user()->getRole();
		if ($role_user == Config::get('roles.DIR_POS')) {
			$id = $request->id;
			Survey::where('id', $id)
						->update(['is_deleted' => 1]);

			return Response::json([
				'success'	=> 1
			], 201);
		}
	}

	public function launch(Request $request) {
		$role_user = Auth::user()->getRole();
		if ($role_user == Config::get('roles.DIR_POS')) {
			$id = session('survey_id');
			Survey::where('id', $id)
						->update(['is_active' => 1]);

			return Response::json([
				'success'	=> 1
			], 201);
		}
	}

	public function hold(Request $request) {
		$role_user = Auth::user()->getRole();
		if ($role_user == Config::get('roles.DIR_POS')) {
			$id = session('survey_id');
			Survey::where('id', $id)
						->update(['is_active' => 0]);

			return Response::json([
				'success'	=> 1
			], 201);
		}
	}

	public function surveyDetails($id) {
		$user_id = Auth::user()->id;
		$role_user = Auth::user()->getRole();

		if ($role_user == Config::get('roles.DIR_POS')) {
			$survey_data = Survey::where('id', '=', $id)
														->first();

			if (is_null($survey_data))
				return redirect('survey');

			session(['survey_id' => $id]);

			$industries = Industry::all(['id', 'name']);

			$questions = Question::where('is_deleted', 0)
														->get(['id', 'name', 'matrix', 'image', 'comment']);

			$options_data = MatrixOption::all(['id', 'en_option']);
			$options = [];
			foreach ($options_data as $data)
				$options[$data->id] = $data->en_option;
			$options = json_encode($options);

			$survey_questions = SurveyQuestions::where('survey_id', '=', $id)
																					->get();
			$survey_questions_id = [];
			foreach ($survey_questions as $sq)
				$survey_questions_id[] = $sq['question_id'];

			$survey_questions_data = Question::where('is_deleted', 0)
																				->whereIn('id', $survey_questions_id)
																				->get();

			$health_jobs = DB::table('job')
												->where('division', 1)
												->where('active', 1)
												->get();

			$education_jobs = DB::table('job')
													->where('division', 3)
													->where('active', 1)
													->get();

			$commercial_jobs	= DB::table('job')
														->where('division', 4)
														->where('active', 1)
														->get();

			$hospitality_jobs = DB::table('job')
														->where('division', 5)
														->where('active', 1)
														->get();

			$government_jobs = DB::table('job')
														->where('division', 6)
														->where('active', 1)
														->get();

			$publicvenue_jobs = DB::table('job')
														->where('division', 7)
														->where('active', 1)
														->get();

			$retail_jobs = DB::table('job')
												->where('division', 8)
												->where('active', 1)
												->get();

			$industrial_jobs = DB::table('job')
														->where('division', 9)
														->where('active', 1)
														->get();

			$survey_jobs_data = SurveyJob::where('survey_id', '=', $id)
																		->get();

			$survey_jobs = [];
			foreach ($survey_jobs_data as $sjd)
				$survey_jobs[] = $sjd->job_number;

			return view("survey.single", [
				'user_id'								=> $user_id,
				'role_user'							=> $role_user,
				'options'								=> $options,
				'questions'							=> $questions,
				'industries'						=> $industries,
				'survey_data'						=> $survey_data,
				'survey_questions_data'	=> $survey_questions_data,
				'health_jobs'						=> $health_jobs,
				'education_jobs'				=> $education_jobs,
				'commercial_jobs'				=> $commercial_jobs,
				'hospitality_jobs'			=> $hospitality_jobs,
				'government_jobs'				=> $government_jobs,
				'publicvenue_jobs'			=> $publicvenue_jobs,
				'retail_jobs'						=> $retail_jobs,
				'industrial_jobs'				=> $industrial_jobs,
				'survey_jobs'						=> $survey_jobs
			]);
		}
	}

	public function getIndustryQuestion(Request $request) {
		$role_user = Auth::user()->getRole();
		if ($role_user == Config::get('roles.DIR_POS')) {
			$data = $request->all();

			$validator = Validator::make($data, [
				'industry'	=> 'required'
			]);
			if ($validator->fails()) {
				$this->throwValidationException(
					$request, $validator
				);
			}

			if ($data['industry'] != 'All') {
				$industry_questions = IndustryQuestion::where('industry_id', '=', $data['industry'])
																							->get();

				$questions = [];
				foreach ($industry_questions as $details)
					$questions[] = $details->question_id;

				$questions_data = Question::whereIn('id', $questions)
																	->get(['id', 'name', 'matrix', 'image', 'comment']);
			} else {
				$questions_data = Question::all(['id', 'name', 'matrix', 'image', 'comment']);
			}

			return Response::json([
				'data'	=> $questions_data
			], 200);
		}
	}

	public function addSurveyQuestion(Request $request) {
		$role_user = Auth::user()->getRole();
		if ($role_user == Config::get('roles.DIR_POS')) {
			$data = $request->all();

			$validator = Validator::make($data, [
				'questions'	=> 'required|array'
			]);
			if ($validator->fails()) {
				$this->throwValidationException(
					$request, $validator
				);
			}

			$created_by = Auth::user()->id;
			$survey_id = session('survey_id');
			$survey_questions = [];
			foreach ($data['questions'] as $question_id)
				$survey_questions[] = ['survey_id' => $survey_id, 'question_id' => $question_id, 'created_by' => $created_by];

			SurveyQuestions::insert($survey_questions);

			return Response::json([
				'success'	=> 1
			], 200);
		}
	}

	public function deleteSurveyQuestion(Request $request) {
		$role_user = Auth::user()->getRole();
		if ($role_user == Config::get('roles.DIR_POS')) {
			$data = $request->all();

			$validator = Validator::make($data, [
				'question'	=> 'required'
			]);
			if ($validator->fails()) {
				$this->throwValidationException(
					$request, $validator
				);
			}

			$survey_id = session('survey_id');
			$question_id = $data['question'];

			SurveyQuestions::where([
												['survey_id', '=', $survey_id],
												['question_id', '=', $question_id]
											])->delete();

			return Response::json([
				'success'	=> 1
			], 200);
		}
	}
}