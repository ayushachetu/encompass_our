<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;


use DB;
use Auth;
use Config;
use Response;
use Mail;
use DateTime;
use Storage;
use Excel;
use Carbon\Carbon;
use App\Announce;
use App\Configuration;
use App\Payroll;
use App\Payroll_item;
use App\User;


class ToolsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
    	$role_user=Auth::user()->getRole();
        return view('tools.page',
        	[] 
        );
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getAnnouncement()
    {
        $role_user=Auth::user()->getRole();
        return view('tools.announcement',
            [] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function submitAnnouncement(Request $request)
    {
        $validator = $this->validator_announcement($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $list_email=array();
        $role_user=Auth::user()->getRole();
        $data=$request->all();
        $email_to=$data['email_to'];
        $receiver=$data['receiver'];
        $subject=$data['subject'];
        $message_send=$data['message'];

        //Get Lists
        if($email_to==1){
            //Only can send to area managers and supervisors
            if (in_array(6, $receiver)) {
                $list_areas= DB::table('users')
                            ->where('role', 6)
                            ->where('active', 1)
                            ->get();
                foreach ($list_areas as $item) {
                    $list_email[]=$item->email;
                }
            }

            if (in_array(8, $receiver)) {
                $list_supervisors= DB::table('users')
                            ->whereIn('role',[5, 8] )
                            ->where('active', 1)
                            ->get();
                foreach ($list_supervisors as $item) {
                    $list_email[]=$item->email;
                }
            }

        }elseif ($email_to==2) {
            if (in_array(6, $receiver)) {
                $list_areas= DB::table('users')
                            ->where('role', 6)
                            ->where('active', 1)
                            ->where('email_personal',"!=", "")
                            ->where('email_personal',"!=", "n/a")
                            ->where('email_personal',"!=", "na@na.com")
                            ->get();
                foreach ($list_areas as $item) {
                    $list_email[]=$item->email_personal;
                }
            }

            if (in_array(8, $receiver)) {
                $list_supervisors= DB::table('users')
                            ->whereIn('role',[5, 8] )
                            ->where('active', 1)
                            ->where('email_personal',"!=", "")
                            ->where('email_personal',"!=", "n/a")
                            ->where('email_personal',"!=", "na@na.com")
                            ->get();
                foreach ($list_supervisors as $item) {
                    $list_email[]=$item->email_personal;
                }
            }

            if (in_array(9, $receiver)) {
                $list_supervisors= DB::table('users')
                            ->whereIn('role',[9] )
                            ->where('active', 1)
                            ->where('email_personal',"!=", "")
                            ->where('email_personal',"!=", "n/a")
                            ->where('email_personal',"!=", "na@na.com")
                            ->get();
                foreach ($list_supervisors as $item) {
                    $list_email[]=$item->email_personal;
                }
            }
        }

        //$list_to=explode(",", $data['to']);
        $list_to=explode(",", $list_email);

        $data=array(
            'subject'   =>  $subject,
            'to'        =>  $list_to,
        );

        Mail::send(['html' => 'emails.announcement'], ['message_send' => $message_send], function ($message) use ($data) {
            $message->from('no-reply@encompassonsite.com', 'Encompassonsite Announcement');
            $message->bcc($data['to']);
            $message->subject($data['subject']);
        });


        return view('tools.announcement_result',
            ['email_to'     =>  $email_to,
             'receiver'     =>  $receiver,
             'subject'      =>  $subject,
             'message'      =>  $message_send,
             'list_email'   =>  $list_email
            ] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getAnnouncementDashboard()
    {
        $result = DB::table('announce')
                    ->select(DB::raw('*'))
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->paginate(50);

        return view('tools.announcement_dashboard',
            ['announce_list'  => $result] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getAnnouncementCreate()
    {
        $dateVal        = Carbon::now();
        $dateValWeek    = $dateVal->copy()->addWeek(1);

        return view('tools.announcement_new',['date_insert' =>  $dateValWeek->format('m/d/Y')]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getAnnouncementSave(Request $request)
    {
        $data=$request->all();
        $list_date=explode("/", $data['closing_date']);
        if(!isset($data['receiver'])){
            $permission="00000";
        }else{
            if(in_array(0, $data['receiver'])){
              $permission="11111";  
            }else{
                $perm1=$perm2=$perm3=$perm4=$perm5=0;

                if(in_array(4, $data['receiver'])){
                    $perm1=1;
                }

                if(in_array(6, $data['receiver'])){
                    $perm2=1;
                }

                if(in_array(5, $data['receiver'])){
                    $perm3=1;
                }

                if(in_array(8, $data['receiver'])){
                    $perm4=1;
                }

                if(in_array(9, $data['receiver'])){
                    $perm5=1;
                }

                $permission=$perm1.$perm2.$perm3.$perm4.$perm5;  
            }

        }
        $announce=Announce::create([
            'title'         => $data['title'],
            'message'       => $data['message'],
            'closing_date'  => $list_date[2].'-'.$list_date[0].'-'.$list_date[1],
            'permission'    => $permission,
            'user_id'       => Auth::user()->id
        ]);

        return redirect('/announcement-dashboard');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getAnnouncementEdit($id)
    {
         return view('tools.announcement_edit', ['announce' => Announce::findOrFail($id)]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function getAnnouncementUpdate(Request $request, $id)
    {
        
        $announce=Announce::findOrFail($id);
        $data=$request->all();
        if(!isset($data['receiver'])){
            $permission="00000";
        }else{
            if(in_array(0, $data['receiver'])){
              $permission="11111";  
            }else{
                $perm1=$perm2=$perm3=$perm4=$perm5=0;

                if(in_array(4, $data['receiver'])){
                    $perm1=1;
                }

                if(in_array(6, $data['receiver'])){
                    $perm2=1;
                }

                if(in_array(5, $data['receiver'])){
                    $perm3=1;
                }

                if(in_array(8, $data['receiver'])){
                    $perm4=1;
                }

                if(in_array(9, $data['receiver'])){
                    $perm5=1;
                }

                $permission=$perm1.$perm2.$perm3.$perm4.$perm5;  
            }

        }

        $list_date=explode("/", $data['closing_date']);

        $announce->title=$data['title'];
        $announce->message=$data['message'];
        $announce->permission=$permission;
        $announce->closing_date = $list_date[2].'-'.$list_date[0].'-'.$list_date[1];

        $announce->save();
        return redirect('/announcement-dashboard')->with('status', 'Announcement has been updated!');;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getAnnouncementDelete($id)
    {
         return view('tools.announcement_delete', ['announce' => Announce::findOrFail($id)]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getPayroll()
    {
        $result = DB::table('payroll')
                    ->select(DB::raw('*'))
                    ->orderBy('created_at', 'desc')
                    ->paginate(50);

        return view('tools.payroll_list',
            ['list'  => $result] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function requestPayroll()
    {
      $date_ini_data=Configuration::where('variable', 'PAYROLL_DATE_INI')->first();
      $date_end_data=Configuration::where('variable', 'PAYROLL_DATE_END')->first();

      $role_user=Auth::user()->getRole();
        return view('tools.payroll',
          ['date_ini_data' => $date_ini_data, 'date_end_data' => $date_end_data] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function submitPayroll(Request $request)
    {
        $data=$request->all();

        $fein="20-1888766";
        $employee_location_id="20-1888766";

        $str = $data['start'];
        $date_ini = DateTime::createFromFormat('m/d/Y', $str);

        $str = $data['end'];
        $date_end = DateTime::createFromFormat('m/d/Y', $str);

        
        $datePaymentIni = Carbon::createFromFormat('Y-m-d', $date_ini->format('Y-m-d'));
        $datePaymentIniFinal  = $datePaymentIni->copy()->addWeek(1);
        $datePayment = Carbon::createFromFormat('Y-m-d', $date_end->format('Y-m-d'));
        $datePaymentFinal  = $datePayment->copy()->addWeek(1);


        $payroll= Payroll::create([
            'name'         => 'payroll'.strtotime("now"),
            'email'        => Auth::user()->email,
            'date_ini'     => $date_ini->format('Y-m-d'),
            'date_end'     => $date_end->format('Y-m-d')
        ]);


        $result = DB::table('payroll_user as pu')
                ->select(DB::raw('*, 
                    (SELECT SUM(hours) FROM timekeeping as tk WHERE tk.employee_number=pu.employee_number AND work_date >="'.$date_ini->format('Y-m-d').'" AND work_date<="'.$date_end->format('Y-m-d').'") as amount_hours,
                    (SELECT SUM(overtimehours) FROM timekeeping as tk WHERE tk.employee_number=pu.employee_number AND work_date >="'.$date_ini->format('Y-m-d').'" AND work_date<="'.$date_end->format('Y-m-d').'") as amount_overtimehours,
                    (SELECT SUM(total) FROM payroll_paycheck as pc WHERE pc.employee_number=pu.employee_number AND check_date >="'.$datePaymentIniFinal->format('Y-m-d').'" AND check_date<="'.$datePaymentFinal->format('Y-m-d').'") as wages'))
                ->orderBy('created_at', 'desc')
                ->get();


                

        foreach ($result as $item) {
            $user=User::where('employee_number', $item->employee_number)->first();
            Payroll_item::create([
                'payroll_id'            => $payroll->id, 
                'ssn'                   => $item->ssn,
                'employee_number'       => $item->employee_number,
                'birth_day'             => $item->birth_date,
                'first_name'            => $item->first_name,
                'last_name'             => $item->last_name,  
                'address'               => $item->address,
                'city'                  => $item->city,
                'state'                 => $item->state,
                'job_title'             => $item->job_title,
                'postal_code'           => $item->postal_code,
                'hire_date'             => $item->hire_date,
                'rehire_date'           => $item->hire_date,
                'department_name'       => "Department",    
                'employee_location_id'  => (isset($user->primary_job)?$user->primary_job:'1'),
                'fein'                  => $fein,
                'pay_period_start_date' => $date_ini->format('Y-m-d'),
                'pay_period_end_date'   => $date_end->format('Y-m-d'),
                'pay_check_date'        => $date_end->format('Y-m-d'),  
                'hours'                 => $item->amount_hours,
                'overtimehours'         => $item->amount_overtimehours, 
                'hour_rate'             => $item->rate, 
                'employment_type'       => 1, 
                'wages_type'            => $item->type,
                'part_full'             => $item->part_full, 
                'wages'                 => $item->wages, 
            ]);            
        }        

        $name_file='Encompass-Onsite_17384_'.date('Ymd').'_'.date('His').'_EMPLOYEEPAYROLL_'.str_pad($payroll->id, 4, "0", STR_PAD_LEFT).'.txt';
        $content="SSN|EmployeeID|DateOfBirth|FirstName|MiddleName|LastName|Street|City|State|PostalCode|OriginalHireDate|RehireDate|TerminationDate|JobTitle|DepartmentNumber|DepartmentName|EmployerLocationID|FEIN|PayPeriodStartDate|PayPeriodEndDate|PaycheckDate|Hours|Wages|HourlyRate|EmploymentType|PayCode|ApplicantID|WageType \r\n";
        $result = DB::table('payroll_item')
                  ->select(DB::raw('*'))
                  ->where('payroll_id', $payroll->id)
                  ->get();
        foreach ($result as $item) {
          if($item->hours>0){
            $date_birth = DateTime::createFromFormat('Y-m-d', $item->birth_day);
            $date_hire = DateTime::createFromFormat('Y-m-d', $item->hire_date);
            $date_start = DateTime::createFromFormat('Y-m-d', $item->pay_period_start_date);
            $date_end = DateTime::createFromFormat('Y-m-d', $item->pay_period_end_date);
            $date_pay = DateTime::createFromFormat('Y-m-d', $item->pay_check_date);
            //$wages_total = (($item->wages_type==1)?(($item->hours-$item->overtimehours)*$item->hour_rate)+($item->overtimehours*($item->hour_rate*1.5)):($item->hour_rate*7));
            $wages_total = $item->wages;

            //$hours_total= (($item->wages_type==1)?$item->hour_rate:($item->hour_rate/80));
            $hours_week=80*7;
            $hours_total= (($item->wages_type==1)?($item->wages/$item->hours):($item->wages/$hours_week));
            $content.=$item->ssn."|".$item->employee_number."|".$date_birth->format('Ymd')."|".$item->first_name."||".$item->last_name."|".$item->address."|".$item->city."|".$item->state."|".$item->postal_code."|".$date_hire->format('Ymd')."|||".$item->job_title."||".$item->department_name."|".$item->employee_location_id."|".$item->fein."|".$date_start->format('Ymd')."|".$date_end->format('Ymd')."|".$date_pay->format('Ymd')."|".(($item->wages_type==1)?$item->hours:$hours_week)."|".$wages_total."|".$hours_total."|".(($item->part_full==1)?'FT':'PT')."|||".(($item->wages_type==1)?'H':'S')." \r\n";
         }
        }

        $file_doc=Storage::disk('local')->put($name_file, $content);
        
        return view('tools.payroll_submit', ['payroll' => $payroll, 'name_file' => $name_file] );

    }    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getFileCSV($id)
    {
      $result = DB::table('payroll_item')
                  ->select(DB::raw('*'))
                  ->where('payroll_id', $id)
                  ->orderBy('employee_number', 'asc')
                  ->get();

      foreach ($result as $item) {
          if($item->hours>0){
            $date_birth = DateTime::createFromFormat('Y-m-d', $item->birth_day);
            $date_hire = DateTime::createFromFormat('Y-m-d', $item->hire_date);
            $date_start = DateTime::createFromFormat('Y-m-d', $item->pay_period_start_date);
            $date_end = DateTime::createFromFormat('Y-m-d', $item->pay_period_end_date);
            $date_pay = DateTime::createFromFormat('Y-m-d', $item->pay_check_date);

            $hours_week=80*7;

            //$wages_total = (($item->wages_type==1)?(($item->hours-$item->overtimehours)*$item->hour_rate)+($item->overtimehours*($item->hour_rate*1.5)):($item->hour_rate*7));
            //$hours_total= (($item->wages_type==1)?$item->hour_rate:($item->hour_rate/80));

            $wages_total = $item->wages;
            $hours_total= (($item->wages_type==1)?($item->wages/$item->hours):($item->wages/$hours_week));

            $data[]=array(
                'SSN'                 =>  $item->ssn, 
                'EmployeeID'          =>  $item->employee_number, 
                'DateOfBirth'         =>  $date_birth->format('m/d/Y'),
                'FirstName'           =>  $item->first_name,
                'MiddleName'          =>  '',
                'LastName'            =>  $item->last_name,
                'Street'              =>  $item->address,
                'City'                =>  $item->city,
                'State'               =>  $item->state,
                'PostalCode'          =>  $item->postal_code,
                'OriginalHireDate'    =>  $date_hire->format('m/d/Y'),
                'RehireDate'          =>  '',
                'TerminationDate'     =>  '',
                'JobTitle'            =>  $item->job_title,
                'DepartmentNumber'    =>  '',
                'DepartmentName'      =>  $item->department_name,
                'EmployerLocationID'  =>  $item->employee_location_id,
                'FEIN'                =>  $item->fein,
                'PayPeriodStartDate'  => $date_start->format('m/d/Y'),
                'PayPeriodEndDate'    =>  $date_end->format('m/d/Y'),
                'PaycheckDate'        =>  $date_pay->format('m/d/Y'),
                'Hours'               =>  (($item->wages_type==1)?$item->hours:$hours_week),
                'Wages'               =>  $wages_total,  
                'HourlyRate'          =>  $hours_total,
                'EmploymentType'      =>  (($item->part_full==1)?'FT':'PT'),
                'PayCode'             =>  '',
                'ApplicantID'         =>  '',
                'WageType'            =>  (($item->wages_type==1)?'H':'S')
                );

          }
        }
        Excel::create('PayrollOutput'.strtotime("now"), function($excel) use($data){

            $excel->sheet('Excel sheet', function($sheet) use($data){
                 $sheet->fromArray($data);
            });

        })->download('xls');



    }


     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getFile($name_file)
    {
      

      $path = storage_path('app/'.$name_file);
    
      return response()->download($path);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function getJobFile()
    {
      $FEIN='20-1888766';
      $content="EmployerLocationID|FEIN|SEIN|EntityName|InternalLocationName|ExternalLocationName|LocationType|DateOpened|DateClosed|Street|City|State|PostalCode|PhoneNumber|FaxNumber|EmailAddress|ContactName|ContactStreet|ContactCity|ContactState|ContactPostalCode|ContactPhoneNumber|ContactFaxNumber|ContactEmailAddress"."<br/>";
      $result = DB::table('job')
                  ->select(DB::raw('*'))
                  ->orderBy('job_number', 'asc')
                  ->get();
      $content.="1|20-1888766||Encompass Onsite||||||6555 N Powerline Rd. Suite #304|Fort Lauderdale|FL|33309|||||||||||"." <br/>";
      foreach ($result as $item) {
        $content.=$item->job_number."|20-1888766||".$item->job_description."|".$item->job_description."|||||".$item->address1."|".$item->city."|".$item->state."|".$item->zip."|||||||||||"." <br/>";
      }
      echo $content;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function getAnnouncementDestroy($id)
    {
        $announce=Announce::findOrFail($id);
        $announce->delete();
        return redirect('/announcement-dashboard')->with('status', 'Announcement has been deleted!');;
    }

        /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_announcement(array $data)
    {
        return Validator::make($data, [
            'subject'                  => 'required',
            'message'                  => 'required',
            'receiver'                 => 'required',
        ]);
    }


}
