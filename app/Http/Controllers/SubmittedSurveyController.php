<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;
use Auth;
use Config;
use Validator;
use Response;
use Crypt;
use Image;

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

class SubmittedSurveyController extends Controller {
	public function index($survey_random_id, Request $request) {
		$request_data	= $request->all();
		if (!isset($request_data['data']))
			return abort(404);

		$survey_data = Survey::where('random_id', $survey_random_id)
													->where('is_deleted', 0)
													->where('is_active', 1)
													->first();
		if (is_null($survey_data))
			return abort(404);

		$request_data	= Crypt::decrypt($request_data['data']);
		$parameters		= explode("&", $request_data);
		$industry			= explode("=", $parameters[0])[1];
		$version			= explode("=", $parameters[1])[1];
		$job					= explode("=", $parameters[2])[1];
		$random				= explode("=", $parameters[3])[1];
		$manager			= explode("=", $parameters[4])[1];
		$include_ques	= explode("=", $parameters[5])[1];
		$user_id 			= Auth::id();

		$submitted_survey_data = SubmittedSurvey::where('survey_random_id', $survey_random_id)
																						->where('industry', $industry)
																						->where('version', $version)
																						->where('job', $job)
																						->where('random', $random)
																						->where('user_id', $user_id)
																						->where('manager', $manager)
																						->where('include_ques', $include_ques)
																						->first();

		if (!is_null($submitted_survey_data)) {

			if ($submitted_survey_data['status'] == 'ended') {
				return view('survey.completed', [
					'message'	=> 'The survey is completed and we have stored your feedback.'
				]);
			}
			session(['submitted_survey_id' => $submitted_survey_data['id']]);

			$submitted_survey_current_question = SubmittedSurveysQuestions::where('submitted_survey_id', $submitted_survey_data['id'])
																																		->where('is_current', 1)
																																		->first();
			$current_question = [
				'id'		=> $submitted_survey_current_question->question_id,
				'name'	=> $submitted_survey_current_question->question_name,
				'index'	=> $submitted_survey_current_question->question_index
			];

			$submitted_survey_matrix = SubmittedSurveysMatrix::where('submitted_survey_id', $submitted_survey_data['id'])
																												->where('question_name', $current_question['name'])
																												->first();
			if (!is_null($submitted_survey_matrix)) {
				$current_question['matrix'] = json_decode($submitted_survey_matrix->matrix);
			}

			$submitted_survey_comment = SubmittedSurveysComments::where('submitted_survey_id', $submitted_survey_data['id'])
																													->where('question_name', $current_question['name'])
																													->first();
			if (!is_null($submitted_survey_comment))
				$current_question['comments'] = $submitted_survey_comment->comments;

			$submitted_survey_image = SubmittedSurveysImages::where('submitted_survey_id', $submitted_survey_data['id'])
																											->where('question_name', $current_question['name'])
																											->first();
			if (!is_null($submitted_survey_image))
				$current_question['images'] = json_decode($submitted_survey_image->images);

			$submitted_survey_rating = SubmittedSurveysRatings::where('submitted_survey_id', $submitted_survey_data['id'])
																												->where('question_name', $current_question['name'])
																												->first();
			if (!is_null($submitted_survey_rating))
				$current_question['ratings'] = $submitted_survey_rating->rating_level;

			$submitted_survey_questions_data = SubmittedSurveysQuestions::where('submitted_survey_id', $submitted_survey_data['id'])
																																	->get();
			$questions_list = [];
			$questions_id_list = [];
			foreach ($submitted_survey_questions_data as $ssqd) {
				$questions_list[] = [
					'name'		=> $ssqd['question_name'],
					'status'	=> $ssqd['question_status']
				];

				$questions_id_list[] = $ssqd['question_id'];
			}

			$question_data = Question::where('id', $current_question['id'])
																->first();
			$question = [
				'name'				=> $question_data['name'],
				'es_name'			=> $question_data['es_name'],
				'matrix'			=> json_decode($question_data['matrix']),
				'es_matrix'		=> json_decode($question_data['es_matrix']),
				'image'				=> json_decode($question_data['image']),
				'es_image'		=> json_decode($question_data['es_image']),
				'comment'			=> json_decode($question_data['comment']),
				'es_comment'	=> json_decode($question_data['es_comment']),
				'index'				=> $current_question['index']
			];

			$industry_list = [
				'1'	=> 'Healthcare',
				'3'	=> 'Education',
				'4'	=> 'Commercial',
				'5'	=> 'Hospitality',
				'6'	=> 'Government',
				'7'	=> 'PublicVenue',
				'8'	=> 'Retail',
				'9'	=> 'Industrial',
			];

			$job_data = Job::where('job_number', $job)
										->first();

			$job_industry = $industry_list[$job_data->division];

			$industry_data = Industry::where('name', $job_industry)
																->first();

			$industry_question_data = IndustryQuestion::where('industry_id', $industry_data->id)
																								->get();
			$survey_industry_ques = [];
			foreach ($industry_question_data as $iqd)
				$survey_industry_ques[] = $iqd->question_id;
		} else {
			$submitted_survey = SubmittedSurvey::create([
				'survey_random_id'	=> $survey_random_id,
				'job'								=> $job,
				'user_id'						=> $user_id,
				'random'						=> $random,
				'version'						=> $version,
				'industry'					=> $industry,
				'manager'						=> $manager,
				'include_ques'			=> $include_ques,
				'status'						=> 'started',
				'timestamp'					=> time(),
			]);

			session(['submitted_survey_id' => $submitted_survey->id]);

			$industry_list = [
				'1'	=> 'Healthcare',
				'3'	=> 'Education',
				'4'	=> 'Commercial',
				'5'	=> 'Hospitality',
				'6'	=> 'Government',
				'7'	=> 'PublicVenue',
				'8'	=> 'Retail',
				'9'	=> 'Industrial',
			];

			$job_data = Job::where('job_number', $job)
											->first();

			$job_industry = $industry_list[$job_data->division];

			$industry_data = Industry::where('name', $job_industry)
																->first();

			$industry_question_data = IndustryQuestion::where('industry_id', $industry_data->id)
																								->get();
			$survey_industry_ques = [];
			foreach ($industry_question_data as $iqd)
				$survey_industry_ques[] = $iqd->question_id;

			if ($random != '1')
				$survey_questions_data = SurveyQuestions::where('survey_id', $survey_data['id'])
																								->whereIn('question_id', $survey_industry_ques)
																								->get();
			else
				$survey_questions_data = SurveyQuestions::where('survey_id', $survey_data['id'])
																								->whereIn('question_id', $survey_industry_ques)
																								->inRandomOrder()
																								->take(5)
																								->get();
			$questions_id_list = [];
			foreach ($survey_questions_data as $sqd)
				$questions_id_list[] = $sqd['question_id'];

			if ($include_ques == 'All')
				$questions_data = Question::whereIn('id', $questions_id_list)
																	->get();
			else
				$questions_data = Question::where('name', '=', $include_ques)
																	->get();

			$submitted_survey_questions = [];
			$questions_list = [];
			$questions = [];
			$i = 0;
			foreach ($questions_data as $qd) {
				$submitted_survey_questions[] = [
					'submitted_survey_id'	=> $submitted_survey->id,
					'question_id'					=> $qd['id'],
					'question_name'				=> $qd['name'],
					'question_status'			=> 'pending',
					'question_index'			=> $i,
					'is_current'					=> 0
				];

				$questions_list[] = [
					'name'		=> $qd['name'],
					'status'	=> 'pending'
				];

				$questions[] = [
					'name'			=> $qd['name'],
					'es_name'		=> $qd['es_name'],
					'matrix'		=> json_decode($qd['matrix']),
					'es_matrix'	=> json_decode($qd['es_matrix']),
					'image'			=> json_decode($qd['image']),
					'es_image'	=> json_decode($qd['es_image']),
					'comment'		=> json_decode($qd['comment']),
					'es_comment'=> json_decode($qd['es_comment'])
				];

				$i++;
			}
			$question = $questions[0];
			$question['index'] = 0;

			$submitted_survey_questions[0]['question_status'] = 'started';
			$submitted_survey_questions[0]['is_current'] = 1;
			SubmittedSurveysQuestions::insert($submitted_survey_questions);

			$questions_list[0]['status'] = 'started';

			$current_question = [
				'id'		=> $submitted_survey_questions[0]['question_id'],
				'name'	=> $submitted_survey_questions[0]['question_name'],
				'index'	=> $submitted_survey_questions[0]['question_index']
			];
		}

		session(['current_question' => $current_question]);
		session(['questions_list' => $questions_list]);

		session(['survey_version' => 'Task List']);
		$scores = ['Done', 'Not Done', 'N/A'];
		if ($survey_data['type'] == 'Quality')
			switch ($version) {
				case 'Full':
					session(['survey_version' => 'Full']);
					$scores = ['Poor', 'Fair', 'Good', 'Excellent', 'N/A'];
					break;
				case 'Supervisor':
					session(['survey_version' => 'Supervisor']);
					$scores = ['Poor', 'Acceptable', 'Good', 'N/A'];
					break;
			}

		$matrix_options_data = MatrixOption::all(['id', 'en_option', 'es_option']);
		$matrix_options = new \stdClass();
		$options = [];
		$es_to_en_options = [];
		$en_to_es_options = [];
		foreach ($matrix_options_data as $mod) {
			$matrix_options->$mod['id'] = [
				'en_option'	=> $mod['en_option'],
				'es_option'	=> $mod['es_option']
			];

			$options[$mod['id']] = $mod['en_option'];

			$es_to_en_options[$mod['es_option']] = $mod['en_option'];

			$en_to_es_options[$mod['en_option']] = $mod['es_option'];
		}
		$options = json_encode($options);

		$questions_bank = Question::where('is_deleted', 0)
															->whereNotIn('id', $questions_id_list)
															->whereIn('id', $survey_industry_ques)
															->get(['id', 'name', 'matrix', 'image', 'comment']);

		$industries = Industry::all(['id', 'name']);

		$questions_data = Question::all();
		$en_to_es_ques = [];
		$es_to_en_ques = []; 
		foreach ($questions_data as $qd) {
			$en_to_es_ques[$qd['name']] = $qd['es_name'];
			$es_to_en_ques[$qd['es_name']] = $qd['name'];
		}

		return view('survey.form', [
			'questions_list'		=> $questions_list,
			'question'					=> $question,
			'scores'						=> $scores,
			'matrix_options'		=> $matrix_options,
			'options'						=> $options,
			'es_to_en_options'	=> $es_to_en_options,
			'en_to_es_options'	=> $en_to_es_options,
			'questions'					=> $questions_bank,
			'industries'				=> $industries,
			'current_question'	=> $current_question,
			'en_to_es_ques'			=> $en_to_es_ques,
			'es_to_en_ques'			=> $es_to_en_ques
		]);
	}

	public function saveSurveyQuestionData(Request $request) {
		$submitted_survey_id = session('submitted_survey_id');
		$current_question = session('current_question');
		$questions_list = session('questions_list');
		$survey_version = session('survey_version');

		$data = $request->all();

		$matrix_data = json_decode($data['matrix']);
		$total_score = 0;
		$ques_score = 0;
		foreach ($matrix_data as $option => $score) {
			$total_score += 100;
			if ($survey_version == 'Task List')
				switch ($score) {
					case 'Done': $ques_score += 100; break;
					case 'Not Done': $ques_score += 0; break;
					default: $ques_score += 0; break;
				}
			elseif ($survey_version == 'Full')
				switch ($score) {
					case 'Poor': $ques_score += 70; break;
					case 'Fair': $ques_score += 80; break;
					case 'Good': $ques_score += 90; break;
					case 'Excellent': $ques_score += 100; break;
					default: $ques_score += 0; break;
				}
			else
				switch ($score) {
					case 'Poor': $ques_score += 70; break;
					case 'Good': $ques_score += 85; break;
					case 'Excellent': $ques_score += 100; break;
					default: $ques_score += 0; break;
				}
		}
		SubmittedSurveysMatrix::create([
			'submitted_survey_id'	=> $submitted_survey_id,
			'question_name'				=> $current_question['name'],
			'matrix'							=> $data['matrix'],
			'total_score'					=> $total_score,
			'ques_score'					=> $ques_score
		]);

		$original_img_dest = public_path('assets\survey-images\original');
		$resized_img_dest = public_path('assets\survey-images\resized');
		$img_desc = json_decode($data['img_desc']);
		$images = [];
		foreach ($_FILES as $file) {
			$img = Image::make($file['tmp_name']);
			$name = time().'-'.$file['name'];
			$img->save($original_img_dest.'\\'.$name);
			$img->resize(100, null, function ($c) {
				$c->aspectRatio();
			})->save($resized_img_dest.'\\'.$name);
			$images[] = [ $name => $img_desc->$file['name'] ];
		}
		SubmittedSurveysImages::create([
			'submitted_survey_id'	=> $submitted_survey_id,
			'question_name'				=> $current_question['name'],
			'images'							=> json_encode($images)
		]);

		SubmittedSurveysComments::create([
			'submitted_survey_id'	=> $submitted_survey_id,
			'question_name'				=> $current_question['name'],
			'comments'						=> $data['comment']
		]);

		SubmittedSurveysRatings::create([
			'submitted_survey_id'	=> $submitted_survey_id,
			'question_name'				=> $current_question['name'],
			'rating_name'					=> 'APPA',
			'rating_level'				=> $data['rating']
		]);

		SubmittedSurveysQuestions::where('submitted_survey_id', $submitted_survey_id)
															->where('question_name', $current_question['name'])
															->update([
																'question_status' => 'ended',
																'is_current'			=> 0,
															]);

		$updated = SubmittedSurveysQuestions::where('submitted_survey_id', $submitted_survey_id)
																				->where('question_index', $data['new_ques'])
																				->update([
																					'question_status' => 'started',
																					'is_current'			=> 1,
																				]);
		if ($updated == 0 && $data['new_ques'] >= count(session($questions_list))) {
			$questions_check = SubmittedSurveysQuestions::where('submitted_survey_id', $submitted_survey_id)
																									->where('question_status', '!=', 'ended')
																									->first();
			if ($questions_check == null) {
				SubmittedSurveysQuestions::where('submitted_survey_id', $submitted_survey_id)
																->where('question_index', 0)
																->update([
																	'is_current'	=> 1
																]);
				return Response::json([
					'signature'	=> 1
				], 200);
			}
		}

		return Response::json([
			'success'	=> 1
		], 200);
	}

	public function addSurveyQuestion(Request $request) {
		$submitted_survey_id = session('submitted_survey_id');

		$data = $request->all();

		$validator = Validator::make($data, [
			'questions'	=> 'required|array'
		]);
		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}

		$submitted_survey_last_question = SubmittedSurveysQuestions::where('submitted_survey_id', $submitted_survey_id)
																																->orderBy('question_index', 'DESC')
																																->first();

		$questions = $data['questions'];
		$questions_data = Question::whereIn('id', $questions)
															->get();

		$submitted_survey_questions = [];
		$index = $submitted_survey_last_question->question_index + 1;
		foreach ($questions_data as $qd) {
			$submitted_survey_questions[] = [
				'submitted_survey_id'	=> $submitted_survey_id,
				'question_id'					=> $qd['id'],
				'question_name'				=> $qd['name'],
				'question_status'			=> 'pending',
				'question_index'			=> $index,
				'is_current'					=> 0
			];

			$index += 1;
		}
		SubmittedSurveysQuestions::insert($submitted_survey_questions);

		return Response::json([
			'success'	=> 1
		], 200);
	}

	public function saveSignature(Request $request) {
		$data = $request->all();
		$submitted_survey_id = session('submitted_survey_id');
		$path = public_path('assets\survey-images\signature\\');

		$signature = $data['signature'];
		$signature = str_replace('data:image/png;base64,', '', $signature);
		$signature = str_replace(' ', '+', $signature);
		$signature_data = base64_decode($signature);
		$signature_name = $path.$submitted_survey_id.'-'.time().'.png';
		file_put_contents($signature_name, $signature_data);

		SubmittedSurvey::where('id', $submitted_survey_id)
										->update([
											'status'		=> 'ended',
											'signature' => $signature_name
										]);

		return Response::json([
			'success'	=> 1
		], 200);
	}

	public function showCompleted() {
		return view('survey.completed', [
			'message'	=> 'The survey is completed and we have stored your feedback.'
		]);
	}
}