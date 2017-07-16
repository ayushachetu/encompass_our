<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Quote;
use App\Quote_item;
use App\Quote_category;
use App\Quote_data;
use App\User;
use App\Job;

use Auth;
use DB;
use Response;
use Excel;
use Config;
use Mail;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('quote.home');
    }

    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function manage(Request $request)
    {
        

        $param0 = $request->session()->get('param0', function() {
            return '0';
        });

        $param1 = $request->session()->get('param1', function() {
            return '1';
        });

        $param2 = $request->session()->get('param2', function() {
            return '1';
        });

        $param3 = $request->session()->get('param3', function() {
            return '0';
        });

        $param4 = $request->session()->get('param4', function() {
            return '0';
        });

        $order_by_1 = $request->session()->get('order_by_1', function() {
            return '1';
        });

        $order_by_2 = $request->session()->get('order_by_2', function() {
            return '1';
        });

        $quote_desc=array(
            '1'  =>  'Drafts',
            '2'  =>  'Sent Quotes',
            '3'  =>  'Approve Quotes',
            '4'  =>  'Denied Quotes',
        );

        $quote_status=array(
            '0'  =>  'Draft',
            '1'  =>  'Sent',
            '5'  =>  'Approve',
            '10'  =>  'Denied',
        );

        $quote_style=array(
            '0'  =>  'default',
            '1'  =>  'primary',
            '5'  =>  'green',
            '10'  => 'red',
        );

        $order_param_1=array(
            '1'         =>  'created_at',
            '2'         =>  'job_number',
            '3'         =>  'correlative',
            '4'         =>  'total',
            '5'         =>  'user_id',
        );

        $order_param_2=array(
            '1'     =>  'desc',
            '2'     =>  'asc',
        );

        $statusList=array();

        //Load status paramenters
        if($param1==1){
            $statusList[]=0;
        }

        if($param2==1){
            $statusList[]=1;
        }

        if($param3==1){
            $statusList[]=5;
        }

        if($param4==1){
            $statusList[]=10;
        }

        if(Auth::user()->getRole()==Config::get('roles.ADMIN') || Auth::user()->getRole()==Config::get('roles.DIR_POS')){
            $view='quote.list_manage_manager';

            $list= DB::table('quote')
                  ->select(DB::raw('*, quote.id as "id", quote.created_at as "created_at"'))  
                  ->leftJoin('users', 'quote.user_id' , '=' , 'users.id')
                  ->whereIn('quote.status', $statusList)
                  ->orderBy('quote.'.$order_param_1[$order_by_1], $order_param_2[$order_by_2])
                  ->paginate(25);      

        }elseif(Auth::user()->getRole()==Config::get('roles.AREA_MANAGER')){
            $view='quote.list_manage_manager';
            $manager_id=Auth::user()->getManagerId();
            $job_list_array=array();

            $jobList = DB::table('job')
                                ->select(DB::raw('*'))
                                ->where('manager', $manager_id)
                                ->get();                

            foreach ($jobList as $value) {
                $job_list_array[]=$value->job_number;         
            }  

            $list= DB::table('quote')
                  ->select(DB::raw('*, quote.id as "id", quote.created_at as "created_at"'))  
                  ->leftJoin('users', 'quote.user_id' , '=' , 'users.id')
                  ->whereIn('quote.status', $statusList)
                  ->where(function ($query)  use ($job_list_array){
                        $query->where('user_id', Auth::user()->getId())
                              ->orWhereIn('job_number', $job_list_array);
                    })
                  ->orderBy('quote.'.$order_param_1[$order_by_1], $order_param_2[$order_by_2])
                  ->paginate(25); 

        }else{    
            $view='quote.list_manage';
            $list=Quote::where('user_id', Auth::user()->getId())
                        ->whereIn('status', $statusList)
                        ->orderBy('quote.'.$order_param_1[$order_by_1], $order_param_2[$order_by_2])
                        ->paginate(25);  

        }
        
        return view($view, [
            'list'          =>  $list, 
            'quote_desc'    =>  $quote_desc,
            'quote_status'  =>  $quote_status,
            'quote_style'   =>  $quote_style,
            'param1'        =>  $param1,
            'param2'        =>  $param2,
            'param3'        =>  $param3,
            'param4'        =>  $param4,
            'param0'        =>  $param0,
            'order_by_1'    =>  $order_by_1,
            'order_by_2'    =>  $order_by_2,
            'role'          =>  Auth::user()->getRole()
            ]);


    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getListExport()
    {

        $list=Quote::where('draft', 0)->where('status', 5)->where('exported', 0)->orderBy('action_at', 'desc')->paginate(25);    
        
        return view('quote.list_export', [ 'list'  => $list]);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */

    public function ListExport(Request $request)
    {
        $data=$request->all();
        $export_list=$data['export_list'];

        $data=array();
        $data_dispatch=array();

        foreach ($export_list as $item) {

            $quote= Quote::findOrFail($item);
            $quote_items=Quote_item::where('quote_id', $item)->where('parent_id', 0)->orderBy('order', 'asc')->get();

            foreach ($quote_items as $i_item) {
                $quote_data=Quote_data::findOrFail($i_item->quote_data_id);

                $dispatch_item_inside=array();

                if($quote_data->measure_type==1){
                    $qty=($i_item->quantity/1000);
                }else{
                    $qty=$i_item->quantity;    
                }

                //Total without tax
                $total_withoutTax=($i_item->quantity*$i_item->price)-(($i_item->quantity*$i_item->price)*($i_item->discount/100));
                
                if($qty!=0)
                    $total_unit=$i_item->total/$qty;
                else
                    $total_unit=0;

                if($quote->managed_by==1){
                    //Dispatch Supervisor ID
                    $supervisorID="1";
                }else{
                    $job=Job::where('job_number', $quote->job_number)->first();
                    if($job->manager!=0){
                        $user=User::where('manager_id', $job->manager)->first();

                        if(isset($user->job_supervisor_id)){
                            $supervisorID=$user->job_supervisor_id;
                            //$supervisorID=10;
                             if(!is_numeric($supervisorID)) {
                                $supervisorID=0;
                             }
                        }else{
                            $supervisorID=0;
                        }
                    }else{
                        $supervisorID=0;
                    }
                }

                $notes="QTY:".$qty.' | UNIT:'.$total_unit.' | '.$i_item->total;

                //Duration
                if($i_item->custom_item==0 || $i_item->labor_hours==0){
                    $duration=($i_item->minutes/60);
                }else{
                    $weeks=number_format($i_item->days/7,0);
                    if($weeks>0)
                        $duration=$i_item->labor_hours/$weeks;
                    else    
                        $duration=$i_item->labor_hours;
                
                }

                //Get Child items
                $quote_items_child=Quote_item::where('parent_id', $i_item->id)->orderBy('order', 'asc')->get();
                foreach ($quote_items_child as $i_item_child) {
                    $total_withoutTax+=($i_item_child->quantity*$i_item_child->price)-(($i_item_child->quantity*$i_item_child->price)*($i_item_child->discount/100));
                    $notes.=" - Subitem - QTY:".$i_item_child->quantity.' | UNIT:'.$i_item_child->price.' | '.$i_item_child->total;
                    if($i_item_child->custom_item==0 || $i_item_child->labor_hours==0)
                        $duration+=($i_item_child->minutes/60);

                    $dispatch_item_inside[]=array(
                        'item_subject'      =>  $i_item_child->item_subject,
                        'duration'          =>  '0',
                        'quantity'          =>  $i_item_child->quantity,
                        'price'             =>  $i_item_child->price,
                        'total'             =>  $i_item_child->total,
                    );
                }    

                $ActualForJobCost='FALSE';
                $ActualBillable='FALSE';
                $ActualInvoiceDescription='';
                $ActualQuantity='';
                $ActualRate='';
                $ActualMeasure=''; 
                $ActualExtension='';

                if($total_withoutTax>0){
                    $ActualForJobCost='TRUE';
                    $ActualBillable='TRUE';
                    $ActualInvoiceDescription=str_replace(",", " ", $quote_data->data_subject).' | '.'QT-'.$quote->job_number.'-'.$quote->correlative;
                    $ActualQuantity='1';
                    $ActualRate=$total_withoutTax;
                    $ActualMeasure=$quote_data->measure_type; 
                    $ActualExtension=$total_withoutTax;
                }

                //Dispatch Data
                if($quote->managed_by==1){
                    $dispatch_item=array(
                        'id'                =>  $quote->id,
                        'name'              =>  'QT-'.$quote->job_number.'-'.$quote->correlative,
                        'subject'           =>  $quote->subject,
                        'start_date'        =>  date( 'm/d/Y', strtotime( $quote->start_date) ),
                        'item_subject'      =>  $i_item->item_subject,
                        'duration'          =>  $duration,
                        'quantity'          =>  $i_item->quantity,
                        'price'             =>  $i_item->price,
                        'total'             =>  $i_item->total,
                        'item_inside'       =>  $dispatch_item_inside
                    );    

                    $data_dispatch[]=$dispatch_item;
                }

                

                $data[]=array(
                    'JobNumber'                 =>  $quote->job_number, 
                    'LinkJobInfo'               =>  'TRUE', 
                    'Active'                    =>  'TRUE',
                    'Attention'                 =>  '',
                    'Name'                      =>  '',
                    'Address1'                  =>  '',
                    'Address2'                  =>  '',
                    'City'                      =>  '',
                    'State'                     =>  '',
                    'Zip'                       =>  '',
                    'ProjectName'              =>    str_replace(",", " ", substr($quote->subject, 0, 49)), 
                    'TaskCodeID'                =>   $quote_data->task_id,
                    'WorkDescription'           =>   str_replace(",", " ", $i_item->item_subject).' | '.'QT-'.$quote->job_number.'-'.$quote->correlative,
                    'RequestedBy'               =>  '',
                    'SalesRepID'                =>  '1000',
                    'TicketSupervisorID'        =>   $supervisorID,
                    'Section'                   =>  '',
                    'ScheduleTypeID'            =>   $quote->managed_by,
                    'RouteID'                   =>  '999',
                    'ComplaintID'               =>  '1000',
                    'ReviewDate'                =>  '',
                    'WorkTicketNotes'           =>  '',
                    'NotesOnKeys'               =>  'Quote Dashboard',
                    'Notes'                     =>  $notes,
                    'PrintItinerary'            =>  'TRUE',
                    'PrintWorkTicket'           =>  'TRUE',
                    'StartDate'                 =>  date( 'm/d/Y', strtotime( $quote->start_date) ),
                    'StartTime'                 =>  '',
                    'EndDate'                   =>  '',
                    'EndTime'                   =>  '',
                    'Duration'                  =>  $duration,
                    'CrewID'                    =>  '999',
                    'ScheduledCrewHours'        =>  '0',
                    'CustomerNumber'            =>  '',
                    'PONumber'                  =>  '',
                    'PrintInvoiceInformation'   =>  'FALSE',
                    'ForceBudgetsToActuals'     =>  'FALSE',
                    'BudgetDescriptionID'       =>   10,
                    'BudgetForJobCost'          =>  'TRUE',
                    'BudgetIncludeInRevenue'    =>  'TRUE',
                    'BudgetAdditionalDescription'=>  '',
                    'BudgetQuantity'            =>  '1',
                    'BudgetRate'                =>  $total_withoutTax,
                    'BudgetMeasure'             =>  $quote_data->measure_type,
                    'BudgetExtension'           =>  $total_withoutTax,
                    'ActualDescriptionID'       =>  10,
                    'ActualForJobCost'          =>  $ActualForJobCost,
                    'ActualBillable'            =>  $ActualBillable,
                    'ActualPrintOrder'          =>  '0',   
                    'ActualInvoiceDescription'  =>  $ActualInvoiceDescription,
                    'ActualQuantity'            =>  $ActualQuantity,  
                    'ActualRate'                =>  $ActualRate,
                    'ActualMeasure'             =>  $ActualMeasure,
                    'ActualExtension'           =>  $ActualExtension,
                    'CustomField1'              =>  'QT-'.$quote->job_number.'-'.$quote->correlative,
                    'CustomField2'              =>  '',
                    'CustomField3'              =>  '',
                    'CustomField4'              =>  '',
                    'CustomField5'              =>  '',
                    'CustomField6'              =>  '',
                    'CustomField7'              =>  '',
                    'CustomField8'              =>  '',
                    'CustomField9'              =>  '',
                    'CustomField10'              =>  '',
                    'CustomField11'              =>  '',
                    'CustomField12'              =>  ''
                );
            }
        
            $quote->exported=1;
            $quote->save();
        }

        //Send notification to dispatch
        if(count($data_dispatch)>0){
            Mail::send('emails.dispatch_notification', ['data_dispatch' => $data_dispatch], function ($message) {
                $message->from('no-reply@encompassonsite.com', 'Encompassonsite Dispatch Notification');
                $message->to(Config::get('sendemail.DISPATCH'));
                $message->subject("Dispatch Notification");
            });
        }

        Excel::create('QuotesOutput'.strtotime("now"), function($excel) use($data){

            $excel->sheet('Excel sheet', function($sheet) use($data){
                 $sheet->fromArray($data);
            });

        })->download('csv');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Get Job List
        $job_list=Job::where('active', 1)->orderBy('job_number', 'asc')->get();
        
        $quote_data_list=Quote_data::get();
        
        //Categories
        $quote_category_type=DB::table('quote_category')
                  ->select(DB::raw('*, (SELECT count(id) FROM quote_data WHERE category_type_id=type_id) as "count"'))  
                  ->orderBy('type_id', 'asc')
                  ->get();

        $quote_category_section=Quote_category::where('type', 2)->where('active', 1)->get();


        return view('quote.create', [
            'job_list'                  => $job_list, 
            'quote_data_list'           => $quote_data_list,
            'quote_category_type'       => $quote_category_type, 
            'quote_category_section'    => $quote_category_section 
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data=$request->all();
        $correlative=Quote::where('correlative','!=',  0)->where('job_number',  $data['job_number'])->max('correlative');
        $correlative++;
        $total=0;
        $discount=0;
        $tax=0;

        if($data['draft']==1){
            $draft=1;
            $status=0;
            $redirect=1;
            $request->session()->put('param1', 1);
            $correlative=0;
        }else{
            $draft=0;
            $status=1;
            $redirect=2;
            $request->session()->put('param2', 1);
        }

        $start_date=explode("/", $data['start_date']);

        if(isset($start_date[2]))
            $start_date_input=$start_date[2].'-'.$start_date[0].'-'.$start_date[1];
        else
            $start_date_input=date('Y-m-d');

        $quote=Quote::create([
            'user_id'         => Auth::user()->id,
            'correlative'     => $correlative,   
            'job_number'      => $data['job_number'],
            'subject'         => $data['subject'],
            'description'     => $data['description'],
            'client_name'     => $data['client_name'],
            'client_email'    => $data['client_email'],
            'notes'           => $data['notes'],
            'minutes'         => $data['minutes'],
            'managed_by'      => $data['managed_by'],
            'discount_field'  => ((isset($data['discount_field']))?'1':'0'),
            'unit_field'      => ((isset($data['unit_field']))?'1':'0'),
            'draft'           => $draft,
            'status'          => $status,
            'start_date'      => $start_date_input
        ]);

        if(isset($data['id_item'])){
            $id_item            =$data['id_item'];
            $qty_item           =$data['qty_item'];
            $tax_item           =$data['tax_item'];
            $discount_item      =$data['discount_item'];
            $description_item   =$data['description_item'];
            $minutes_item_list  =$data['minutes_item_list'];
            $minutes_list       =$data['minutes_list'];
            $custom_item        =$data['custom_item'];
            $price_item         =$data['price_item'];
            $total_item         =$data['total_item'];
            $labor_item         =$data['labor_item'];
            $labor_hours_item   =$data['labor_hours_item'];
            $material_item      =$data['material_item'];
            $sub_contractor_item=$data['sub_contractor_item'];
            $margin_item        =$data['margin_item'];
            $days_item          =$data['days_item'];
            $parent_item        =$data['parent_item'];
            $parent_obj=array();
            $cnt=0;
            foreach ($id_item as $item) {
                
                $total+=(isset($total_item[$cnt])?$total_item[$cnt]:0);
                $tax+=(isset($tax_item[$cnt])?$tax_item[$cnt]:0);
                $discount+=(isset($discount_item[$cnt])?$discount_item[$cnt]:0);

                if($parent_item[$cnt]!=0){
                    $parent_insert=$parent_obj->id;
                    $item_insert=$parent_obj->quote_data_id;
                }else{
                    $parent_insert=0;
                    $item_insert=$item;
                }

                $quote_data=Quote_data::findOrFail($item_insert);

                $objQuote=Quote_item::create([
                    'quote_id'          =>  $quote->id,
                    'quote_data_id'     =>  $item_insert,
                    'quote_task_id'     =>  $quote_data->task_id,
                    'quantity'          =>  (isset($qty_item[$cnt])?$qty_item[$cnt]:0),
                    'item_subject'      =>  (isset($description_item[$cnt])?$description_item[$cnt]:0),
                    'price'             =>  (isset($price_item[$cnt])?$price_item[$cnt]:0),
                    'tax'               =>  (isset($tax_item[$cnt])?$tax_item[$cnt]:0),
                    'discount'          =>  (isset($discount_item[$cnt])?$discount_item[$cnt]:0),
                    'minutes'           =>  (isset($minutes_item_list[$cnt])?$minutes_item_list[$cnt]:0),
                    'base_minutes'      =>  (isset($minutes_list[$cnt])?$minutes_list[$cnt]:0),
                    'total'             =>  (isset($total_item[$cnt])?$total_item[$cnt]:0),
                    'custom_item'       =>  (isset($custom_item[$cnt])?$custom_item[$cnt]:0),
                    'labor'             =>  (isset($labor_item[$cnt])?$labor_item[$cnt]:0),
                    'labor_hours'       =>  (isset($labor_hours_item[$cnt])?$labor_hours_item[$cnt]:0),
                    'material'          =>  (isset($material_item[$cnt])?$material_item[$cnt]:0),
                    'sub_contract'      =>  (isset($sub_contractor_item[$cnt])?$sub_contractor_item[$cnt]:0),
                    'margin'            =>  (isset($margin_item[$cnt])?$margin_item[$cnt]:0),
                    'days'              =>  (isset($days_item[$cnt])?$days_item[$cnt]:1),
                    'parent_id'         =>  $parent_insert,
                    'order'             =>  $cnt
                ]);

                if($parent_insert==0){
                    $parent_obj=$objQuote;
                }
                
                $cnt++;
            }
        }

        $quote->total=$total;
        $quote->tax=$tax;
        $quote->discount=$discount;
        $quote->save();
        if($redirect==1){
            return redirect('quotes')->with('status', 'Quote has been saved');    
        }else{
            return redirect('quote/view/'.$quote->id)->with('status', 'Quote has been saved');    
        }
        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $job_list=Job::where('active', 1)->orderBy('job_number', 'asc')->get();
        $quote_data_list=Quote_data::get();
        $quote_items=Quote_item::where('quote_id', $id)->orderBy('order', 'asc')->get();
        $quote_items_count=Quote_item::where('quote_id', $id)->count();

        $redirect=array(
            '1'     =>  '2',
            '5'     =>  '3',
            '10'    =>  '4',
        );
        
        return view('quote.view', [
            'quote'             => Quote::findOrFail($id), 
            'job_list'          => $job_list, 
            'quote_data_list'   => $quote_data_list,
            'quote_items'       => $quote_items,
            'quote_items_count' => $quote_items_count, 
            'redirect'          => $redirect 
        ]);
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, $id)
    {
        $data=$request->all();
        $quote=Quote::findOrFail($id);
        if($data['status']!=$quote->status){
            $quote->status=$data['status'];
            $quote->action_at=date('y-m-d H:i');
            switch ($data['status']) {
                case '5':
                    $redirect=3;
                    $request->session()->put('param3', 1);
                    break;
                case '10':
                    $redirect=4;
                    $request->session()->put('param4', 1);
                    break;
                
                default:
                    $redirect=2;
                    break;
            }
        }
        $quote->save();
        return redirect('quotes')->with('status', 'Quote has been updated.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function clone_quote($id)
    {
        $quote=Quote::findOrFail($id);
    
         $new_quote=Quote::create([
            'user_id'         => Auth::user()->id,
            'correlative'     => 0,   
            'job_number'      => $quote->job_number,
            'subject'         => $quote->subject.'(copy)',
            'description'     => $quote->description,
            'client_name'     => $quote->client_name,
            'client_email'    => $quote->client_email,
            'notes'           => $quote->notes,
            'minutes'         => $quote->minutes,
            'managed_by'      => $quote->managed_by,
            'discount_field'  => $quote->discount_field,
            'unit_field'      => $quote->unit_field,
            'draft'           => 1,
            'status'          => 0,
            'start_date'      => $quote->start_date,
        ]);


        $quote_items=Quote_item::where('quote_id', $id)->get();

        foreach ($quote_items as $item) {
            Quote_item::create([
                'quote_id'          =>  $new_quote->id,
                'quote_data_id'     =>  $item->quote_data_id,
                'quote_task_id'     =>  $item->quote_task_id,
                'quantity'          =>  $item->quantity,
                'item_subject'      =>  $item->item_subject,
                'price'             =>  $item->price,
                'tax'               =>  $item->tax,
                'discount'          =>  $item->discount,
                'minutes'           =>  $item->minutes,
                'base_minutes'      =>  $item->base_minutes,
                'total'             =>  $item->total,
                'custom_item'       =>  $item->custom_item,
                'labor'             =>  $item->labor,
                'labor_hours'       =>  $item->labor_hours,
                'material'          =>  $item->material,
                'sub_contract'      =>  $item->sub_contract,
                'margin'            =>  $item->margin,
                'parent_id'         =>  $item->parent_id,
                'days'              =>  $item->days,
            ]);    
        }

        
        return redirect('/quote/edit/'.$new_quote->id);
    
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $job_list=Job::where('active', 1)->orderBy('job_number', 'asc')->get();
        $quote_data_list=Quote_data::get();
        $quote_items=Quote_item::where('quote_id', $id)->orderBy('order', 'asc')->get();
        $quote_items_count=Quote_item::where('quote_id', $id)->where('parent_id', 0)->count();

        //Categories
        $quote_category_type=DB::table('quote_category')
                  ->select(DB::raw('*, (SELECT count(id) FROM quote_data WHERE category_type_id=type_id) as "count"'))  
                  ->orderBy('type_id', 'asc')
                  ->get();

        $quote_category_section=Quote_category::where('type', 2)->where('active', 1)->get();
        
        return view('quote.edit', [
            'quote'                     => Quote::findOrFail($id), 
            'job_list'                  => $job_list, 
            'quote_data_list'           => $quote_data_list,
            'quote_items'               => $quote_items,
            'quote_items_count'         => $quote_items_count, 
            'quote_category_type'       => $quote_category_type, 
            'quote_category_section'    => $quote_category_section 
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data=$request->all();
        $quote=Quote::findOrFail($id);

        $total=0;
        $discount=0;
        $tax=0;
        $items_active=array();

        $correlative=Quote::where('correlative','!=',  0)->where('job_number',  $data['job_number'])->max('correlative');
        $correlative+=1;

        if($data['draft']==1){
            $quote->draft=1;
            $quote->status=0;
            $redirect=1;
            $request->session()->put('param1', 1);
        }else{
            $quote->draft=0;
            $quote->status=1;
            $quote->correlative=$correlative;
            $redirect=2;
            $request->session()->put('param2', 1);
        }

        $start_date=explode("/", $data['start_date']);

        $quote->job_number      = $data['job_number'];
        $quote->subject         = $data['subject'];
        $quote->description     = $data['description'];
        $quote->client_name     = $data['client_name'];
        $quote->client_email    = $data['client_email'];
        $quote->notes           = $data['notes'];
        $quote->minutes         = $data['minutes'];
        $quote->managed_by      = $data['managed_by'];
        $quote->discount_field  = ((isset($data['discount_field']))?'1':'0');
        $quote->unit_field      = ((isset($data['unit_field']))?'1':'0');
        $quote->start_date      = $start_date[2].'-'.$start_date[0].'-'.$start_date[1];

        if(isset($data['id_item'])){
            $quote_item_id      =$data['quote_item_id'];
            $id_item            =$data['id_item'];
            $qty_item           =$data['qty_item'];
            $tax_item           =$data['tax_item'];
            $discount_item      =$data['discount_item'];
            $description_item   =$data['description_item'];
            $minutes_item_list  =$data['minutes_item_list'];
            $minutes_list       =$data['minutes_list'];
            $custom_item        =$data['custom_item'];
            $price_item         =$data['price_item'];
            $total_item         =$data['total_item'];
            $labor_item         =$data['labor_item'];
            $labor_hours_item   =$data['labor_hours_item'];
            $material_item      =$data['material_item'];
            $sub_contractor_item=$data['sub_contractor_item'];
            $margin_item        =$data['margin_item'];
            $days_item          =$data['days_item'];
            $parent_item        =$data['parent_item'];
            $cnt=0;
            $parent_obj =array();
            foreach ($id_item as $item) {
                
                if($quote_item_id[$cnt]==0){
                    //$total+=($qty_item[$cnt]*$quote_data->price)+(($qty_item[$cnt]*$quote_data->price)*($tax_item[$cnt]/100))-(($qty_item[$cnt]*$quote_data->price)*($discount_item[$cnt]/100));
                    $total+=$total_item[$cnt];
                    $tax+=$tax_item[$cnt];
                    $discount+=$discount_item[$cnt];

                    if($parent_item[$cnt]!=0){
                        $parent_insert=$parent_obj->id;
                        $item_insert=$parent_obj->quote_data_id;
                    }else{
                        $parent_insert=0;
                        $item_insert=$item;
                    }

                    $quote_data=Quote_data::findOrFail($item_insert);

                    $quote_item=Quote_item::create([
                        'quote_id'          =>  $quote->id,
                        'quote_data_id'     =>  $item,
                        'quote_task_id'     =>  $quote_data->task_id,
                        'quantity'          =>  $qty_item[$cnt],
                        'item_subject'      =>  $description_item[$cnt],
                        'price'             =>  $price_item[$cnt],
                        'tax'               =>  $tax_item[$cnt],
                        'discount'          =>  $discount_item[$cnt],
                        'base_minutes'      =>  $minutes_list[$cnt],
                        'minutes'           =>  $minutes_item_list[$cnt],
                        'total'             =>  $total_item[$cnt],
                        'custom_item'       =>  $custom_item[$cnt],
                        'labor'             =>  $labor_item[$cnt],
                        'labor_hours'       =>  $labor_hours_item[$cnt],
                        'material'          =>  $material_item[$cnt],
                        'sub_contract'      =>  $sub_contractor_item[$cnt],
                        'margin'            =>  $margin_item[$cnt],
                        'days'              =>  $days_item[$cnt],
                        'parent_id'         =>  $parent_insert,
                        'order'             =>  $cnt,
                    ]);
                    if($parent_insert==0){
                        $parent_obj=$quote_item;
                    }
                    $items_active[]=$quote_item->id;    
                }else{
                    $items_active[]=$quote_item_id[$cnt];
                    $quote_item=Quote_item::findOrFail($quote_item_id[$cnt]);
                    
                    //$total+=($qty_item[$cnt]*$quote_data->price)+(($qty_item[$cnt]*$quote_data->price)*($tax_item[$cnt]/100))-(($qty_item[$cnt]*$quote_data->price)*($discount_item[$cnt]/100));
                    $total+=$total_item[$cnt];
                    $tax+=$tax_item[$cnt];
                    $discount+=$discount_item[$cnt];

                    $quote_item->quantity       =$qty_item[$cnt];
                    $quote_item->price          =$price_item[$cnt];
                    $quote_item->item_subject   =$description_item[$cnt];
                    $quote_item->tax            =$tax_item[$cnt];
                    $quote_item->discount       =$discount_item[$cnt];
                    $quote_item->base_minutes   =$minutes_list[$cnt];
                    $quote_item->minutes        =$minutes_item_list[$cnt];
                    $quote_item->total          =$total_item[$cnt];
                    $quote_item->custom_item    =$custom_item[$cnt];
                    $quote_item->labor          =$labor_item[$cnt];
                    $quote_item->labor_hours    =$labor_hours_item[$cnt];
                    $quote_item->material       =$material_item[$cnt];
                    $quote_item->sub_contract   =$sub_contractor_item[$cnt];
                    $quote_item->margin         =$margin_item[$cnt];
                    $quote_item->order          =$cnt;

                    if($quote_item->parent_id==0){
                        $parent_obj=$quote_item;
                    }

                    $quote_item->save();
                }

                $cnt++;
            }
        }

        //Delete items not on the list
        Quote_item::where('quote_id', $quote->id)->whereNotIn('id', $items_active)->delete();
        
        $quote->total=$total;
        $quote->tax=$tax;
        $quote->discount=$discount;

        
        $quote->save();
        if($redirect==1){
            return redirect('quotes')->with('status', 'Quote has been saved');    
        }else{
            return redirect('quote/view/'.$id)->with('status', 'Quote has been saved');    
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_quote(Request $request, $id)
    {
        //
        $data=$request->all();
        $quote=Quote::findOrFail($id);

        $start_date=explode("/", $data['start_date']);

        $quote->job_number      = $data['job_number'];
        $quote->subject         = $data['subject'];
        $quote->description     = $data['description'];
        $quote->client_name     = $data['client_name'];
        $quote->client_email    = $data['client_email'];
        $quote->notes           = $data['notes'];
        $quote->managed_by      = $data['managed_by'];
        $quote->start_date      = $start_date[2].'-'.$start_date[0].'-'.$start_date[1];
        
        $quote->save();
        return redirect('quote/view/'.$id)->with('status', 'Quote has been saved');    
        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewPdf($id)
    {
        //
        $quote=Quote::findOrFail($id);
        $quote_items=Quote_item::where('quote_id', $id)->orderBy('order', 'asc')->get();
        
        //Get User
        $user=User::findOrFail($quote->user_id);

        //Get User
        $job=Job::where('job_number', $quote->job_number)->first();
        
        $view =view('quote.view_pdf',compact('quote', 'quote_items', 'user', 'job'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('encompassonsite-quote.pdf');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewEmail($id)
    {
        //
        $quote=Quote::findOrFail($id);
        $quote_items=Quote_item::where('quote_id', $id)->orderBy('order', 'asc')->get();
        
        //Get User
        $user=User::findOrFail($quote->user_id);

        //Get User
        $job=Job::where('job_number', $quote->job_number)->first();

        $view =view('quote.view_pdf',compact('quote', 'quote_items', 'user', 'job'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->save('quote_files/Quote'.$id.'-'.$quote->job_number.'-'.date('mdY').'.pdf');
        
        return view('quote.email_send', [
            'quote'                     => $quote, 
            'job'                       => $job, 
            'user'                      => $user,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function sendEmail(Request $request, $id)
    {
        
        $data=$request->all();

        $quote=Quote::findOrFail($id);
        $user=User::findOrFail($quote->user_id);

        if($data['copy_email']!="")
            $list_copy=explode(",", $data['copy_email']);
        else
            $list_copy=array();

        $data_action=array(
            'id'            => $id,
            'job_number'    => $quote->job_number, 
            'to'            => $data['client_email'],
            'subject'       => $data['subject'],
            'from_name'     => $user->first_name.' '.$user->last_name,
            'from_email'    => $user->email,
            'copy_email'    => $list_copy, 
        );

        


        Mail::send('emails.quote', ['data' => $data], function ($message) use ($data_action) {
            $message->from($data_action['from_email'], $data_action['from_name']);
            $message->to($data_action['to']);
            
            if(count($data_action['copy_email'])>0)
                $message->cc($data_action['copy_email']);
            
            $message->bcc($data_action['from_email']);
            $message->subject($data_action['subject']);
            $message->attach('quote_files/Quote'.$data_action['id'].'-'.$data_action['job_number'].'-'.date('mdY').'.pdf');
        });

        $quote->email_quote=1;
        $quote->save();

        return redirect('quote/view/'.$id)->with('status', 'Quote has been sent to client.');    
    }

    public function assignCorrelative()
    {
        $correlative=array();
        $list=Quote::where('correlative', '!=', 0)->orderBy('correlative', 'asc')->get();
        
        //Set up correlatives
        foreach ($list as $item) {
            if(!isset($correlative[$item->job_number])){
                $correlative[$item->job_number]=1;
            }    
        }

        foreach ($list as $item) {
            $quote=Quote::findOrFail($item->id);
            $quote->correlative=$correlative[$item->job_number];
            $correlative[$item->job_number]=$correlative[$item->job_number]+1;
            $quote->save();
        }    

        return "Loaded correlatives";

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getItemList($type, $id)
    {
        if($id!=0){
            if($type==1){
                $item_list=Quote_data::where('category_type_id', $id)->get();
            }else{
                $item_list=Quote_data::where('category_section_id', $id)->get();
            }
        }else{
            $item_list=Quote_data::get();
        }

        $html='<option value="0">Select Item</option>';

        foreach ($item_list as $item) {
            $html.='<option id="option-item-'.$item->id.'" value="'.$item->id.'" data-price="'.$item->price.'" data-description="'.$item->data_subject.'" data-minutes="'.$item->minutes.'">'.$item->data_subject.' - $'.number_format($item->price,2).'</option>';
        }


         return Response::json(
                array(
                    'status'        => '1',
                    'html'          => $html, 
                )
        );
    }


     /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function changeFilter(Request $request, $param1, $param2, $param3, $param4, $param0)
    {
        if($param1=='undefined') $param1=0;
        if($param2=='undefined') $param2=0;
        if($param3=='undefined') $param3=0;
        if($param4=='undefined') $param4=0;

        if($param0==0){
            $request->session()->put('param1', $param1);
            $request->session()->put('param2', $param2);
            $request->session()->put('param3', $param3);
            $request->session()->put('param4', $param4);    
            $request->session()->put('param0', 0);
        }elseif($param0==1){
            $request->session()->put('param1', 1);
            $request->session()->put('param2', 1);
            $request->session()->put('param3', 1);
            $request->session()->put('param4', 1);    
            $request->session()->put('param0', 1);
        }elseif($param0==2){
            $request->session()->put('param1', 0);
            $request->session()->put('param2', 0);
            $request->session()->put('param3', 0);
            $request->session()->put('param4', 0);    
            $request->session()->put('param0', 0);
        }

        

         return Response::json(
                array('status'        => '1')
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function changeOrder(Request $request, $orderby1, $orderby2)
    {
        $request->session()->put('order_by_1', $orderby1);
        $request->session()->put('order_by_2', $orderby2);
        
         return Response::json(
                array('status'        => '1')
        );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $data=$request->all();
        /*Quote::where('id', $data['delete_item'])->delete();
        Quote_item::where('quote_id', $data['delete_item'])->delete();*/

        $quote=Quote::findOrFail($data['delete_item']);
        $quote->status          = 20;
        
        $quote->save();

        return redirect('quotes')->with('status', 'Quote has been deleted.');

    }
}
