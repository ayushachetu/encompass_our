<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;

use App\Form1;
use App\Form2;
use App\Form3;
use App\Form3_item;
use App\Form4;
use App\User;
use Carbon\Carbon;

use Auth;
use Mail;
use Excel;
use DB;
use Response;
use Config;

class FormController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getMemberForm()
    {
        $name="";
        $email="";
        if (Auth::check()) {
            $name=Auth::user()->first_name." ".Auth::user()->last_name;
            $email=Auth::user()->email;
        }    

    	return view('form.manager', ['name'  => $name, 'email'  => $email]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getMemberFormSubmit(Request $request)
    {
        $validator = $this->validator_form1($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $data=$request->all();
        $form1=Form1::create($data);

        Mail::send('emails.form1', ['data' => $data], function ($message) {
		    $message->from('no-reply@encompassonsite.com', 'Encompassonsite Job Request');
		    $message->to(Config::get('sendemail.JOB_REQUEST'));
		    $message->subject("New Job Request");
		});

        return view('form.success_form1', ['success'  => 1]);
    }


     /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getTalentForm()
    {
        $name="";
        $email="";
        if (Auth::check()) {
            $name=Auth::user()->first_name." ".Auth::user()->last_name;
            $email=Auth::user()->email;
        }    
        return view('form.talent', ['name'  => $name, 'email'  => $email]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getTalentFormSubmit(Request $request)
    {
        $data=$request->all();

        $validator = $this->validator_form2($data);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }


        $job_list=array(
            101 => "101 Housekeeper",
            102 => "102 Housekeeper/ Day Porter",
            103 => "103 Housekeeper Supervisor",
            201 => "201 Maintenance Supervisor",
            202 => "202 Maintenance Day Porter",
            301 => "301 Power Sweeper Operator",
            401 => "401 Grounds Maintenance Specialist",
            402 => "402 Grounds Maintenance Supervisor",
            501 => "501 MRO Services Handyman",
            502 => "502 MRO Services Supervisor",
            601 => "601 Floor Care Specialist", 
            602 => "602 Floor Care Supervisor",
            701 => "701 Carpet Care Cleaning Specialist",      
            801 => "801 Specialty Services"
        );

        $data_save = array(
            'name'                      => $data['name'],
            'email'                     => $data['email'], 
            'site_name'                 => $data['site_name'],
            'site_account_number'       => $data['site_account_number'], 
        );

        $data_value=array();


        if($data['action_select']==1){
            $data_save['type']=1;    

            $measure_list=array(
                1   =>  "Hourly",
                2   =>  "Yearly Salary",
            );

            $data_action = array(
                'name'                      => $data['name'],
                'email'                     => $data['email'], 
                'site_name'                 => $data['site_name'],
                'site_account_number'       => $data['site_account_number'], 
                'date_needed'               => $data['form1_date_needed'], 
                'position_title'            => $data['form1_position_title'], 
                'position_rate'             => $data['form1_position_rate'], 
                'measure_rate'              => $measure_list[$data['form1_measure_rate']], 
                'position_job_code'         => $job_list[$data['form1_position_job_code']], 
                'work_schedule'             => (isset($data['form1_work_schedule'])?$data['form1_work_schedule']:""), 
                'shift'                     => (isset($data['form1_shift'])?$data['form1_shift']:""), 
                'hours_per_week'            => $data['form1_hours_per_week'], 
                'site_specific'             => $data['form1_site_specific'], 
            );

            $data_value=$data_action;

            Mail::send('emails.form2_new_position', ['data' => $data_action], function ($message) use ($data_action){
                $message->from('no-reply@encompassonsite.com', 'EncompassonsiteTalent');
                $message->to(Config::get('sendemail.TALENT1'));
                $message->cc(Config::get('sendemail.TALENT1_CC'));
                $message->subject("New Position Request from ".$data_action['name']);
            });
        }elseif ($data['action_select']==2) {
            $data_save['type']=2;

            $measure_list=array(
                1   =>  "Hourly",
                2   =>  "Yearly Salary",
            );

            $data_action = array(
                'name'                      => $data['name'],
                'email'                     => $data['email'], 
                'site_name'                 => $data['site_name'],
                'site_account_number'       => $data['site_account_number'], 
            );

            $data_action_employee = array(
                'employee_name'             => $data['form2_employee_name'], 
                'employee_number'           => $data['form2_employee_number'],
                'effective_date'            => $data['form2_effective_date'],
                'reason_termination'        => $data['form2_reason_termination'],
                'explanation_termination'   => $data['form2_explanation_termination'],
            );

            $data_value=$data_action_employee;

            

            //Verify if file exists
            if ($request->hasFile('form2_termination_file')) {
                $attach=$request->file('form2_termination_file');
                $data_param=array(
                    'name'      =>  $data['name'],
                    'attach'    =>  $attach,
                    'extension' =>  $attach->getClientOriginalExtension(),
                    'mime'      =>  $attach->getMimeType(),
                    'hasFile'   =>  1
                );
            }else{
                $data_param=array(
                    'name'      =>  $data['name'],
                    'hasFile'   =>  0
                );
            }  

            $flag_replace_position=0;

            if($data['form2_replace_position']==1){
                $flag_replace_position=1;

                $data_action_add = array(
                    'position_title'            => $data['form2_add_position_title'], 
                    'position_rate'             => $data['form2_add_position_rate'], 
                    'measure_rate'              => $measure_list[$data['form2_add_measure_rate']], 
                    'position_job_code'         => $job_list[$data['form2_add_position_job_code']], 
                    'work_schedule'             => (isset($data['form2_add_work_schedule'])?$data['form2_add_work_schedule']:""), 
                    'shift'                     => (isset($data['form2_shift'])?$data['form2_shift']:""), 
                    'hours_per_week'            => $data['form2_add_hours_per_week'], 
                    'site_specific'             => $data['form2_add_site_specific'], 
                );

                $data_value = array_merge($data_value, $data_action_add);

                Mail::send('emails.form2_add_position', ['data' => $data_action, 'data_employee' => $data_action_employee, 'data_add' => $data_action_add], function ($message) use ($data_action){
                    $message->from('no-reply@encompassonsite.com', 'EncompassonsiteTalent');
                    $message->to(Config::get('sendemail.TALENT2'));
                    $message->cc(Config::get('sendemail.TALENT2_CC'));
                    $message->subject("Replacement Position Request from ".$data_action['name']);
                });
            }

            $data_param['flag']=$flag_replace_position;

            Mail::send('emails.form2_terminate_position', ['data' => $data_action, 'data_employee' => $data_action_employee, 'flag' =>  $flag_replace_position], function ($message) use ($data_param){
                $message->from('no-reply@encompassonsite.com', 'EncompassonsiteTalent');
                $message->to(Config::get('sendemail.TALENT2_TERMINATE'));
                if($data_param['flag']==0)
                    $message->cc(Config::get('sendemail.TALENT2_TERMINATE_CC'));
                
                if($data_param['hasFile']==1)
                    $message->attach($data_param['attach'],['as'    => 'attachment-terminate.'.$data_param['extension'], 'mine' =>$data_param['mime']]);
                
                $message->subject("Termination Request from ".$data_param['name']);
            });

        }elseif ($data['action_select']==3) {
            $data_save['type']=3;

            $measure_list=array(
                1   =>  "Hourly(non-exempt)",
                2   =>  "Yearly Salary(exempt)",
            );    
            $data_action = array(
                'name'                      => $data['name'],
                'email'                     => $data['email'], 
                'site_name'                 => $data['site_name'],
                'site_account_number'       => $data['site_account_number'], 
                'employee_name'             => $data['form3_employee_name'], 
                'employee_number'           => $data['form3_employee_number'],
                'effective_date'            => $data['form3_effective_date'],
                'change_requested'          => $data['form3_change_requested'],
                'explanation_change'        => $data['form3_explanation_change'],
                'work_schedule'             => (isset($data['form3_work_schedule'])?$data['form3_work_schedule']:""), 
                'shift'                     => (isset($data['form3_shift'])?$data['form3_shift']:""), 
                'hours_per_week'            => $data['form3_hours_per_week'], 
                'current_rate'              => $data['form3_current_rate'], 
                'current_measure_rate'      => $measure_list[$data['form3_current_measure_rate']], 
                'new_rate'                  => $data['form3_new_rate'], 
                'new_measure_rate'          => $measure_list[$data['form3_new_measure_rate']], 
                'position_title'            => $data['form3_position_title'], 
                'position_job_code'         => $job_list[$data['form3_position_job_code']], 
                'additional_changes'        => $data['form3_additional_changes'], 
            );    

            $data_value = $data_action;


            //Verify if file exists
            if ($request->hasFile('form3_change_file')) {
                $attach=$request->file('form3_change_file');
                $data_param=array(
                    'name'      =>  $data['name'],
                    'attach'    =>  $attach,
                    'extension' =>  $attach->getClientOriginalExtension(),
                    'mime'      =>  $attach->getMimeType(),
                    'hasFile'   =>  1
                );
            }else{
                $data_param=array(
                    'name'      =>  $data['name'],
                    'hasFile'   =>  0
                );
            }   



            Mail::send('emails.form2_change_status', ['data' => $data_action], function ($message) use ($data_param){
                $message->from('no-reply@encompassonsite.com', 'EncompassonsiteTalent');
                $message->to(Config::get('sendemail.TALENT3'));
                $message->cc(Config::get('sendemail.TALENT3_CC'));
                if($data_param['hasFile']==1)
                    $message->attach($data_param['attach'],['as'    => 'attachment-change.'.$data_param['extension'], 'mine' =>$data_param['mime']]);
                $message->subject("Change of Status Request from ".$data_param['name']);
            });
        }

        $data_save['value']=serialize($data_value);
        $form2=Form2::create($data_save);

        
        return view('form.success_form2', ['success'  => 1]);
    }

     /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getTrainingForm()
    {
        $name="";
        $email="";
        if (Auth::check()) {
            $name=Auth::user()->first_name." ".Auth::user()->last_name;
            $email=Auth::user()->email;
        }    

        return view('form.training', ['name'  => $name, 'email'  => $email]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getTrainingFormSubmit(Request $request)
    {
        $validator = $this->validator_form3($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $data=$request->all();
        
        $employee_name_list=$data['employee_name'];
        $employee_number_list=$data['employee_number'];
        $account_number_list=$data['account_number'];
        $count_items=count($employee_number_list);
        $data['count_items']=$count_items;

        $subject_date=date("F", mktime(0, 0, 0, $data['date_month_training'], 10)).' '.$data['date_day_training'].', '.$data['date_year_training'];
        $data_param=array(
            'email'          =>   $data['email'],
            'subject_date'   =>   $subject_date
        );

        Mail::send('emails.form3', ['data' => $data], function ($message) use ($data_param){
            $message->from('no-reply@encompassonsite.com', 'Encompassonsite Training Registration');
            $message->to(Config::get('sendemail.TRAINING'));
            //$message->to('hesler.solares@encompassonsite.com');
            $message->subject("Training Registration - ".$data_param['subject_date']);
        });

        

        Mail::send('emails.form3_confirmation', ['data' => $data], function ($message) use ($data_param) {
            $message->from('no-reply@encompassonsite.com', 'Encompassonsite Training Registration');
            $message->to($data_param['email']);
            //$message->to('hesler.solares@encompassonsite.com');
            $message->subject("Training Registration Confirmation - ".$data_param['subject_date']);
        });

        //Send a copy of emails
        if($data['copy_emails']!=""){
            $email_list = explode(",", $data['copy_emails']);
            foreach ($email_list as $email) {
                if(filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false){
                    $data_param['email']=trim($email);

                    Mail::send('emails.form3_confirmation', ['data' => $data], function ($message) use ($data_param) {
                        $message->from('no-reply@encompassonsite.com', 'Encompassonsite Training Registration');
                        $message->to($data_param['email']);
                        //$message->to('hesler.solares@encompassonsite.com');
                        $message->subject("Training Registration Confirmation - ".$data_param['subject_date']);
                    });                    
                }
            }

        }

        //Save information to database
        $date_convert = Carbon::createFromFormat('d/m/Y', $data['date_day_training'].'/'.$data['date_month_training'].'/'.$data['date_year_training']);
        $data_insert=array(
            'name'          =>  $data['name'],
            'email'         =>  $data['email'],
            'date_training' =>  $date_convert->format('Y-m-d'),
            'comment'       =>  $data['comment']
        );

        $form3=Form3::create($data_insert);

        for ($i=0; $i < $count_items; $i++) { 
            $data_insert=array(
                'id_form3'               =>  $form3->id,
                'employee_name'          =>  $employee_name_list[$i],
                'employee_number'        =>  $employee_number_list[$i],
                'account_number'         =>  $account_number_list[$i],
                'date_training'          =>  $date_convert->format('Y-m-d')
            );

            $form3_item=Form3_item::create($data_insert);
        }

        return view('form.success_form3', ['success'  => 1]);
    }


     /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getExitInterviewForm()
    {
        $name="";
        $email="";
        if (Auth::check()) {
            $name=Auth::user()->first_name." ".Auth::user()->last_name;
            $email=Auth::user()->email;
        }    

        return view('form.exit_interview', ['name'  => $name, 'email'  => $email]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getExitInterviewFormSubmit(Request $request)
    {
        $validator = $this->validator_form4($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $data=$request->all();

        $data_param=array(
            'subject'   =>   $data['employee_number'].' - '.$data['employee_name']
        );

        Mail::send('emails.form4', ['data' => $data], function ($message) use ($data_param){
            $message->from('no-reply@encompassonsite.com', 'Encompassonsite Team');
            $message->to(Config::get('sendemail.EXIT_INTERVIEW'));
            //$message->to('hesler.solares@encompassonsite.com');
            $message->subject("Exit Interview - ".$data_param['subject']);
        });

        //Save information to database
        $form4=Form4::create($data);

        return view('form.success_form4', ['success'  => 1]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getHistoryJob()
    {
        $list=Form1::orderBy('created_at', 'desc')
                    ->paginate(25);

        return view('form.history_job', ['list'  => $list]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getHistoryJobDownload()
    {
        $list=Form1::orderBy('created_at', 'desc')
                    ->paginate(500);

         $data=array();
        foreach ($list as $item) {
            $data[]=array(
                'Date Created'      =>  date( 'm/d/Y', strtotime( $item->created_at) ) , 
                'Email'             =>  $item->email, 
                'Job Number'        =>  $item->account_number,
                'Job Name'          =>  $item->account_name,
                'Customer Name'     =>  $item->customer_name,
                'Customer Email'    =>  $item->customer_email,
                'Customer Phone'    =>  $item->customer_cellphone,
                'Scope of Work'     =>  $item->scope_work,
                'Job Location'      =>  $item->job_location,
                'Target Start date and time'    => $item->target_start,
                'Total labor hours needed'      => $item->labor_hours,
                'Employee pay rate'             => $item->employee_pay_rate,
                'Material Cost'                 => $item->material_cost, 
                'Sub-contractor to be used'     => $item->sub_contractor
                );
        }
        Excel::create('jobRequestOutput'.date("mdYHi"), function($excel) use($data){

            $excel->sheet('Excel sheet', function($sheet) use($data){
                 $sheet->fromArray($data);
            });

        })->download('csv');

    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getHistoryJobView($id)
    {
        $item=Form1::findOrFail($id);

        return view('form.history_job_view', ['item'  => $item]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getHistoryTalentChange()
    {
        $type_list=[
            ''  =>  'N/A',
            '1' =>  'Employee for new position',
            '2' =>  'Terminate and replace position',
            '3' =>  'Change status of employee',
        ];
        $list=Form2::orderBy('created_at', 'desc')
                    ->paginate(25);

        return view('form.history_talent_change', ['list'  => $list, 'type_list'  =>   $type_list]);
    }

    /**
     * Display a listing of the resource.
     *
     * int $type
     * @return Response
     */

    public function getHistoryTalentChangeDownload($type)
    {
        $type_list=[
            ''  =>  'N/A',
            '1' =>  'EmployeeNewPosition',
            '2' =>  'Terminate',
            '3' =>  'ChangeStatus',
        ];


         $list=DB::table('form2')
                  ->select(DB::raw('*'))  
                  ->where('type', $type)
                  ->orderBy('created_at', 'desc')
                  ->paginate(500);      

        $data=array();
        foreach ($list as $item) {
            $array_storage=array();

            $array_storage=array(
                'Date Request'            =>  date( 'm/d/Y', strtotime( $item->created_at) ) , 
                'Name'                    =>  $item->name, 
                'Email'                   =>  $item->email,
                'Site Name'               =>  $item->site_name,
                'Site Account Number'     =>  $item->site_account_number
                );

            if($type==2){
                $data_value=unserialize($item->value);
                $userObj=User::where('employee_number', $data_value['employee_number'])->first();

                 $tk=DB::table('timekeeping')
                  ->select(DB::raw('*'))  
                  ->where('employee_number', $data_value['employee_number'])
                  ->orderBy('work_date', 'desc')
                  ->first();
                
                $array_storage['Employee Name']             =$data_value['employee_name'];
                $array_storage['Employee Number']           =$data_value['employee_number'];
                $array_storage['Effective Date']            =$data_value['effective_date'];
                $array_storage['Reason for Termination']    =$data_value['reason_termination'];
                $array_storage['Active Winteam']            =' '.$userObj->active.' ';
                $array_storage['Explanation Termination']   =$data_value['explanation_termination'];
                $array_storage['Timekeeping Date']          =(isset($tk->work_date)?$tk->work_date:'');  
            }

            $data[]=$array_storage;


        }

        Excel::create($type_list[$type].'Output'.date("mdYHi"), function($excel) use($data){

            $excel->sheet('Excel sheet', function($sheet) use($data){
                 $sheet->fromArray($data);
            });

        })->download('xls');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getHistoryTraining()
    {
        $list=Form3::orderBy('created_at', 'desc')
                    ->paginate(25);

        return view('form.history_training', ['list'  => $list]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getHistoryTrainingView($id)
    {
        $item=Form3::findOrFail($id);
        $list=Form3_item::where('id_form3', $id)->get();

        return view('form.history_training_view', ['item'  => $item, 'list' =>  $list]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getHistoryExitInterview()
    {
        $list=Form4::orderBy('created_at', 'desc')
                    ->paginate(25);

        return view('form.history_exit_interview', ['list'  => $list]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getHistoryExitInterviewView($id)
    {
        $item=Form4::findOrFail($id);

        return view('form.history_exit_interview_view', ['item'  => $item]);
    }


    

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_form1(array $data)
    {
        return Validator::make($data, [
            'email'                 => 'required|email',
            'account_number' 		=> 'required',
            'account_name'  		=> 'required',
            'customer_name'     	=> 'required',
            'customer_email'    	=> 'required|email',
            'customer_cellphone'    => 'required',
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_form2(array $data)
    {
        return Validator::make($data, [
            'name'                  => 'required',
            'email'                 => 'required|email',
            'site_name'             => 'required',
            'site_account_number'   => 'required',
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_form3(array $data)
    {
        return Validator::make($data, [
            'name'                  => 'required',
            'email'                 => 'required|email',
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_form4(array $data)
    {
        return Validator::make($data, [
            'name'                  => 'required',
            'email'                 => 'required|email',
            'employee_name'         => 'required',
            'employee_number'       => 'required',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getTalentItemList($id)
    {
        $data=Form2::findOrFail($id);

        $type_list=[
            ''  =>  'N/A',
            '1' =>  'Employee for new position',
            '2' =>  'Terminate and replace position',
            '3' =>  'Change status of employee',
        ];

        $html=view('form.talent_info',
                ['data'         => $data, 'data_inside'   => unserialize($data->value), 'type_list' => $type_list   ]
                )->render();



         return Response::json(
                array(
                    'status'        => '1',
                    'html'          => $html, 
                )
        );
    }
}
