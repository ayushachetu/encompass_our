<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;
use Auth;
use Response;

use App\Trigger;
use App\User;

use App\Survey;
use App\SurveyQuestions;
use App\Question;
use App\Industry;
use App\IndustryQuestion;
use App\MatrixOption;
use App\SubmittedSurvey;
use App\SubmittedSurveysMatrix;
use App\SubmittedSurveysImages;
use App\SubmittedSurveysComments;
use App\SubmittedSurveysRatings;
use App\SubmittedSurveysQuestions;
use App\Job;

class TriggersController extends Controller {
	public function index() {
		$data = Trigger::all();

		return view('triggers.page', [
			'data'	=> $data
		]);
	}

	public function form() {
		$recipients_by_role = DB::table('role')->select('id', 'name')->get();

		$jobs = DB::table('job')->get();

		return view('triggers.create', [
			'recipients_by_role'	=> $recipients_by_role,
			'jobs'								=> $jobs
		]);
	}

	public function create(Request $request) {
		$submitted_survey_id = 5;
		$submitted_survey_data = SubmittedSurvey::where('id', $submitted_survey_id)->first();

		$sql = "SELECT *
						FROM triggers
						WHERE jobs
						LIKE '%$submitted_survey_data->job%'";

		$triggers_data = DB::select($sql);

		if (count($triggers_data) > 0) {
			// $roles_data = DB::select("SELECT * FROM role");
			// $roles_by_ids = [];
			// foreach ($roles_data as $data)
			// 	$roles_by_ids[$data->id] = $data->name;

			foreach ($triggers_data as $data) {
				// $recipients_by_roles = json_decode($data->recipients_by_roles);

				// $sql = "SELECT ";
				// $recipients_by_roles = [];
				// foreach (json_decode($data->recipients_by_roles) as $role_id)
				// 	$recipients_by_roles[] = $roles_by_ids[$role_id];
				// dd($recipients_by_roles);

				$matrix_data = SubmittedSurveysMatrix::where('submitted_survey_id', $submitted_survey_id)->get();
				$all_questions = [];
				$low_score_questions = [];
				foreach ($matrix_data as $data) {
					
				}

				switch ($data->on_action) {
					case 'survey-completed':
						// 
						break;

					case 'low-score-on-survey':
						$sql = "SELECT m1.* 
										FROM submitted_surveys_matrix m1 
										INNER JOIN submitted_surveys_matrix m2 ON m1.id = m2.id 
										GROUP BY m1.submitted_survey_id 
										HAVING ((SUM(m1.ques_score) / SUM(m1.total_score)) * 100) < 80 
										ORDER BY m1.id";
						$matrix_data = DB::select($sql);

						if (count($matrix_data) > 0) {
							$html = '';
							foreach ($matrix_data as $data) {
								// 
							}
						}
						break;

					case 'low-score-on-a-question':
						// 
						break;
					
					case 'low-ratings-score':
						// 
						break;

					default:
						// 
						break;
				}
			}
		}

		die;

		$data = [
			'recipients_by_roles'	=> json_encode($request->recipients_by_roles),
			'custom_recipients'		=> json_encode($request->custom_recipients),
			'on_action'						=> $request->on_action,
			'execution_time'			=> $request->execution_time,
			'execution_unit'			=> $request->execution_unit,
			'data_to_send'				=> json_encode($request->data_to_send),
			'custom_message'			=> $request->custom_message,
			'jobs'								=> implode(',', $request->jobs),
			'created_by'					=> Auth::user()->id,
			'is_deleted'					=> 0
		];

		Trigger::create($data);

		return Response::json([ 'success'	=> 1 ], 200);
	}
}