<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Response;

use App\ScheduledReport;

class SchedulingController extends Controller {
	public function index() {
		$data = ScheduledReport::where('is_active', 1)
						->get();

		return view("scheduled-jobs.page", [
			'scheduled_jobs_data' => $data
		]);
	}

	public function create(Request $request) {
		$err_msg = '';

		if (!isset($request->is_recursive) || $request->is_recursive == '')
			$err_msg = 'Kindly select applicable: Recursive / Once';

		elseif (!isset($request->frequency) || $request->frequency == '')
			$err_msg = 'Kindly select the frequency';

		elseif (!isset($request->send_on) || $request->send_on == '')
			$err_msg = 'Kindly select day/date for sending emails';

		elseif (
			(!isset($request->recipients_by_roles) || $request->recipients_by_roles == '')
			&&
			(!isset($request->custom_recipients) || $request->custom_recipients == '')
		)
			$err_msg = 'Kindly select or add atleast one recipient to send emails';

		if ($err_msg != '')
			return Response::json([ 'message'	=> $err_msg ], 422);

		$report_data = session('client_cumulative');

		$dates = explode(' - ', $report_data['report_range']);
		$report_range = (int) date_diff(date_create($dates[0]), date_create($dates[1]))->format("%a") + 1;

		$data = [
			'is_recursive'				=> ($request->is_recursive == 'once') ? 0 : 1,
			'frequency'						=> $request->frequency,
			'send_on'							=> $request->send_on,
			'recipients_by_roles'	=> (gettype($request->recipients_by_roles) == "array") ? implode(', ', $request->recipients_by_roles) : '',
			'custom_recipients'		=> (gettype($request->custom_recipients) == "array") ? implode(', ', $request->custom_recipients) : '',
			'custom_message'			=> (isset($request->custom_message)) ? $request->custom_message : '',
			'report_range'				=> $report_range,
			'report_type'					=> $request->report_type,
			'primary_filter'			=> $report_data['primary_filter'],
			'secondary_filter'		=> implode(', ', $report_data['secondary_filter']),
			'created_by'					=> Auth::user()->id,
			'is_active'						=> 1
		];

		ScheduledReport::create($data);

		return Response::json([ 'success'	=> 1 ], 200);
	}

	public function delete(Request $request) {
		if (!isset($request->id))
			return Response::json([ 'message' => 'No record found.' ], 422);

		$result = ScheduledReport::where('id', $request->id)
							->update([ 'is_active' => 0 ]);

		if ($result > 0)
			return Response::json([ 'message' => 'Record deleted successfully.' ], 200);
		else
			return Response::json([ 'message' => 'No record found.' ], 404);
	}
}