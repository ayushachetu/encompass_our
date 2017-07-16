<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;
use Auth;
use Config;
use Validator;
use Response;

use App\MatrixOption;

class MatrixOptionsController extends Controller {
	public function index() {
		$user_id = Auth::user()->id;
		$role_user = Auth::user()->getRole();

		if ($role_user == Config::get('roles.DIR_POS')) {
			$view_display="matrixoptions.page";

			$matrix_options = MatrixOption::where('is_deleted', 0)
																		->get();

			$deleted_matrix_options = MatrixOption::where('is_deleted', 1)
																						->get();

			return view($view_display, [
				'user_id'								=> $user_id,
				'role_user'							=> $role_user,
				'matrix_options'				=> $matrix_options,
				'deleted_matrix_options'=> $deleted_matrix_options
			]);
		}
	}

	public function store(Request $request) {
		$data = $request->all();

		$validator = Validator::make($data, [
			'en_option'	=> 'required'
		]);
		if ($validator->fails()) {
			return Response::json([
				'English Option'	=> 'Required'
			], 422);
		}

		$validator = Validator::make($data, [
			'es_option'	=> 'required'
		]);
		if ($validator->fails()) {
			return Response::json([
				'Spanish Option'	=> 'Required'
			], 422);
		}

		$created_by = Auth::user()->id;

		$option_data = [
			'en_option'		=> $data['en_option'],
			'es_option'		=> $data['es_option'],
			'created_by'	=> $created_by,
			'is_deleted'	=> 0
		];

		try {
			MatrixOption::create($option_data);
			return Response::json([
				'success'	=> 1
			], 200);
		} catch (\Exception $e) {
			if ($e->getCode() == '23000') {
				return Response::json([
					'Option already exists.' => ''
				], 422);
			}
		}

		return Response::json([
			'success'	=> 1
		], 200);
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

		MatrixOption::where('id', '=', $id)
								->update(['is_deleted' => 1]);

		return Response::json([
			'success'	=> 1
		], 200);
	}

	public function edit(Request $request) {
		$data = $request->all();

		$validator = Validator::make($data, [
			'id'				=> 'required'
		]);
		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}
		$id = $data['id'];

		$validator = Validator::make($data, [
			'en_option'	=> 'required'
		]);
		if ($validator->fails()) {
			return Response::json([
				'English Option'	=> 'Required'
			], 422);
		}

		$validator = Validator::make($data, [
			'es_option'	=> 'required'
		]);
		if ($validator->fails()) {
			return Response::json([
				'Spanish Option'	=> 'Required'
			], 422);
		}

		$option_data = [
			'en_option'	=> trim($data['en_option']),
			'es_option'	=> trim($data['es_option'])
		];

		try {
			MatrixOption::where('id', $id)
									->update($option_data);
			return Response::json([
				'success'	=> 1
			], 200);
		} catch (\Exception $e) {
			if ($e->getCode() == '23000') {
				return Response::json([
					'Option already exists.' => ''
				], 422);
			}
		}
	}

	public function restore(Request $request) {
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

		MatrixOption::where('id', '=', $id)
								->update(['is_deleted' => 0]);

		return Response::json([
			'success'	=> 1
		], 200);
	}
}