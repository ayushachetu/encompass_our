<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;
use Auth;
use Config;
use Validator;
use Response;

use App\Question;
use App\Industry;
use App\IndustryQuestion;
use App\MatrixOption;

class QuestionsController extends Controller {

	public function index() {
		$user_id = Auth::user()->id;
		$role_user = Auth::user()->getRole();

		if ($role_user == Config::get('roles.DIR_POS')) {
			$view_display = "questions.page";

			$questions_data = DB::select("SELECT q.*, GROUP_CONCAT(iq.industry_id SEPARATOR ',') AS industries 
																		FROM questions q 
																		LEFT JOIN industry_question iq 
																		ON q.id = iq.question_id 
																		WHERE q.is_deleted = 0
																		GROUP BY q.id 
																		ORDER BY q.priority ASC");

			$industries = Industry::where('active', 1)->get();

			$matrix_options = MatrixOption::where('is_deleted', 0)
																		->get(['id', 'es_option', 'en_option']);

			$deleted_matrix_options = MatrixOption::where('is_deleted', 1)
																						->get(['id', 'es_option', 'en_option']);

			$deleted_options = [];
			foreach ($deleted_matrix_options as $option)
				$deleted_options[(string) $option->id] = [$option->en_option, $option->es_option];

			return view($view_display, [
				'user_id'					=> $user_id,
				'role_user'				=> $role_user,
				'questions_data'	=> $questions_data,
				'industries'			=> $industries,
				'matrix_options'	=> $matrix_options,
				'deleted_options' => $deleted_options
			]);
		}
	}

	public function store(Request $request) {
		$data = $request->all();

		$validator = Validator::make($data, [
			'name'			=> 'required',
			'matrix'		=> 'required',
			'image'			=> 'required',
			'comment'		=> 'required',
			'es_name'		=> 'required',
			'es_matrix'	=> 'required',
			'es_image'	=> 'required',
			'es_comment'=> 'required'
		]);
		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}

		$flow = false;
		if ($data['matrix']['flow'] == 'true') {
			$flow = true;

			$validator = Validator::make($data['matrix'], [
				'label'		=> 'required',
				'options'	=> 'required|array'
			]);
			if ($validator->fails()) {
				return Response::json([
					'Matrix Label'		=> 'Required',
					'Matrix Options'	=> 'Required'
				], 422);
			}

			$validator = Validator::make($data['es_matrix'], [
				'label'		=> 'required',
				'options'	=> 'required|array'
			]);
			if ($validator->fails()) {
				return Response::json([
					'Spanish Matrix Label'		=> 'Required',
					'Spanish Matrix Options'	=> 'Required'
				], 422);
			}
		}

		if ($data['image']['flow'] == 'true') {
			$flow = true;

			$validator = Validator::make($data['image'], [
				'label'	=> 'required'
			]);
			if ($validator->fails()) {
				return Response::json([
					'Image Label'	=> 'Required'
				], 422);
			}

			$validator = Validator::make($data['es_image'], [
				'label'	=> 'required'
			]);
			if ($validator->fails()) {
				return Response::json([
					'Spanish Image Label'	=> 'Required'
				], 422);
			}
		}

		if ($data['comment']['flow'] == 'true') {
			$flow = true;

			$validator = Validator::make($data['comment'], [
				'label'	=> 'required'
			]);
			if ($validator->fails()) {
				return Response::json([
					'Comment Label'	=> 'Required'
				], 422);
			}

			$validator = Validator::make($data['es_comment'], [
				'label'	=> 'required'
			]);
			if ($validator->fails()) {
				return Response::json([
					'Spanish Comment Label'	=> 'Required'
				], 422);
			}
		}

		if ($flow === false) {
			return Response::json([
				'Atleast one flow for question'	=> 'Required'
			], 422);
		}

		$created_by = Auth::user()->id;

		$last_priority = Question::orderBy('priority', 'desc')->first();
		$priority = isset($last_priority->priority) ? $last_priority->priority + 1 : 1;

		$question_data = [
			'name'			=> $data['name'],
			'image'			=> json_encode($data['image']),
			'matrix'		=> json_encode($data['matrix']),
			'comment'		=> json_encode($data['comment']),
			'es_name'		=> $data['es_name'],
			'es_image'	=> json_encode($data['es_image']),
			'es_matrix'	=> json_encode($data['es_matrix']),
			'es_comment'=> json_encode($data['es_comment']),
			'priority'	=> $priority,
			'created_by'=> $created_by
		];

		try {
			Question::create($question_data);
			return Response::json([
				'success'	=> 1
			], 200);
		} catch (\Exception $e) {
			if ($e->getCode() == '23000') {
				return Response::json([
					'Question already exists.' => ''
				], 422);
			}
		}
	}

	public function edit(Request $request) {
		$data = $request->all();

		$validator = Validator::make($data, [
			'id'				=> 'required',
			'name'			=> 'required',
			'es_name'		=> 'required',
			'matrix'		=> 'required',
			'es_matrix'	=> 'required',
			'image'			=> 'required',
			'es_image'	=> 'required',
			'comment'		=> 'required',
			'es_comment'=> 'required'
		]);
		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}

		$flow = false;
		if ($data['matrix']['flow'] == 'true') {
			$flow = true;

			$validator = Validator::make($data['matrix'], [
				'label'		=> 'required',
				'options'	=> 'required|array'
			]);
			if ($validator->fails()) {
				return Response::json([
					'Matrix Label'		=> 'Required',
					'Matrix Options'	=> 'Required'
				], 422);
			}

			$validator = Validator::make($data['es_matrix'], [
				'label'		=> 'required',
				'options'	=> 'required|array'
			]);
			if ($validator->fails()) {
				return Response::json([
					'Spanish Matrix Label'		=> 'Required',
					'Spanish Matrix Options'	=> 'Required'
				], 422);
			}
		}

		if ($data['image']['flow'] == 'true') {
			$flow = true;

			$validator = Validator::make($data['image'], [
				'label'	=> 'required'
			]);
			if ($validator->fails()) {
				return Response::json([
					'Image Label'	=> 'Required'
				], 422);
			}

			$validator = Validator::make($data['es_image'], [
				'label'	=> 'required'
			]);
			if ($validator->fails()) {
				return Response::json([
					'Spanish Image Label'	=> 'Required'
				], 422);
			}
		}

		if ($data['comment']['flow'] == 'true') {
			$flow = true;

			$validator = Validator::make($data['comment'], [
				'label'	=> 'required'
			]);
			if ($validator->fails()) {
				return Response::json([
					'Comment Label'	=> 'Required'
				], 422);
			}

			$validator = Validator::make($data['es_comment'], [
				'label'	=> 'required'
			]);
			if ($validator->fails()) {
				return Response::json([
					'Spanish Comment Label'	=> 'Required'
				], 422);
			}
		}

		if ($flow === false) {
			return Response::json([
				'Atleast one flow for question'	=> 'Required'
			], 422);
		}

		$id = $data['id'];

		$question_data = [
			'name'			=> $data['name'],
			'image'			=> json_encode($data['image']),
			'matrix'		=> json_encode($data['matrix']),
			'comment'		=> json_encode($data['comment']),
			'es_name'		=> $data['es_name'],
			'es_image'	=> json_encode($data['es_image']),
			'es_matrix'	=> json_encode($data['es_matrix']),
			'es_comment'=> json_encode($data['es_comment'])
		];

		try {
			Question::where('id', $id)
							->update($question_data);

			return Response::json([
				'success'	=> 1
			], 200);
		} catch (\Exception $e) {
			if ($e->getCode() == '23000') {
				return Response::json([
					'Question already exists.' => ''
				], 422);
			}
		}

		return Response::json([
			'success'	=> 1
		], 200);
	}

	public function getQuestionData(Request $request) {
		$data = $request->all();

		$validator = Validator::make($data, [
			'id'	=> 'required'
		]);

		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}

		$id = $data['id'];

		$question_data = Question::where('id', '=', $id)
											->get();

		return $question_data;
	}

	public function delete(Request $request) {
		$data = $request->all();

		$validator = Validator::make($data, [
			'id'	=> 'required'
		]);

		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}

		$id = $data['id'];

		Question::where('id', '=', $id)
							->update(['is_deleted' => 1]);
	}

	public function storeIndustryQuestion(Request $request) {
		$data = $request->all();
		
		$validator = Validator::make($data, [
			'industry'	=> 'required',
			'question'	=> 'required'
		]);

		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}

		$industry = $data['industry'];
		$question = $data['question'];
		$created_by = Auth::user()->id;

		$industry_question = IndustryQuestion::create([
			'industry_id'	=> $industry,
			'question_id'	=> $question,
			'created_by'=> $created_by
		]);
	}

	public function deleteIndustryQuestion(Request $request) {
		$data = $request->all();
		
		$validator = Validator::make($data, [
			'industry'	=> 'required',
			'question'	=> 'required'
		]);

		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}

		$industry = $data['industry'];
		$question = $data['question'];

		$deleted_row = IndustryQuestion::where([
				['industry_id', '=', $industry],
				['question_id', '=', $question]
			])->delete();
	}

	public function updatePriority(Request $request) {
		$data = $request->all();

		$validator = Validator::make($data, [
			'id'							=> 'required',
			'priority'				=> 'required',
			'sibling_id'			=> 'required',
			'sibling_priority'=> 'required'
		]);

		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}

		$id = $data['id'];
		$priority = $data['priority'];
		$sibling_id = $data['sibling_id'];
		$sibling_priority = $data['sibling_priority'];

		Question::where('id', '=', $id)
							->update(['priority' => $sibling_priority]);

		Question::where('id', '=', $sibling_id)
							->update(['priority' => $priority]);
	}
}