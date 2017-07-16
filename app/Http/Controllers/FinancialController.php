<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;


use DB;
use Auth;
use Config;
use Response;
use Carbon\Carbon;
use Excel;
use App\Financial_file;
use App\Financial;
use App\Configuration;


class FinancialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        $result = DB::table('financial_file')
                    ->select(DB::raw('*, 
                        (SELECT count(DISTINCT(invoice_number)) FROM financial WHERE id_financial_file=financial_file.id) as count_invoices,
                        (SELECT SUM(distribution_amount) FROM financial WHERE id_financial_file=financial_file.id) as amount_invoices'))
                    ->orderBy('created_at', 'desc')
                    ->paginate(50);

        return view('financial.page',
        	['financial_files'  => $result] 
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function request()
    {
        $dateNow        = Carbon::now();
        $dateLastMonth  = $dateNow->copy()->subMonth(1);
        return view('financial.request', ['date_ini'    =>  $dateLastMonth->format('m/d/Y'), 'date_end' =>  $dateNow->format('m/d/Y')  ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function call(Request $request)
    {
        $data=$request->all();
        $date_ini=$data['date_ini'];
        $date_end=$data['date_end'];
        $mark_exported=(isset($data['mark_exported'])?1:0);

        $date_ini_items=explode("/", $date_ini);
        $date_end_items=explode("/", $date_end);

        $str_date_ini=$date_ini_items[2].'-'.$date_ini_items[0].'-'.$date_ini_items[1].'T00:00';
        $str_date_end=$date_end_items[2].'-'.$date_end_items[0].'-'.$date_end_items[1].'T23:59';        

        $file_id=0;
        $flag=0;
        $first_item=true;
        $list_invoices=array();
        $first_item_id=0;

        //Get Invoices PTR
        $element_invoice=Configuration::where('variable', 'PTR_INVOICE')->first();
        $ptr_invoice=$element_invoice->value;
        
        $last_invoice_id=0;


        $client = new Client();
        $res = $client->request('GET', 'https://dirtpros.coupahost.com/api/invoices?status[in]=approved&exported=false&updated_at[gt_or_eq]='.$str_date_ini.'&updated_at[lt_or_eq]='.$str_date_end, [
            'headers' => [
                'X-COUPA-API-KEY' => 'e41f2e5bf5869e70760f5b3fd8e5b12b2629d3f2',
                'ACCEPT'          => 'application/xml',
            ]
        ]);
        $code=$res->getStatusCode();    
        $header=$res->getHeader('content-type');
        $contents=$res->getBody();
        $string_xml=(string) $contents;
        //$string_xml=str_replace("-", "", $string_xml);
        $xml = simplexml_load_string($string_xml);


        foreach ($xml as $element) {
            //Id is over the ptr limit
            //if($element->{'id'}<=$ptr_invoice)
                //$flag=1;

            //Correlative doesn't match
            /*if($element->{'id'}!=($ptr_invoice+1) && $first_item && $flag!=1){
                $flag=2;
                $first_item_id=$element->{'id'};
                $first_item=false;
                break;
            }*/
                

            if($flag==0){
                if($first_item){
                    $financial_file=Financial_file::create([
                        'name'         => 'financial'.strtotime("now"),
                        'email'        => Auth::user()->email,
                    ]);

                    $file_id=$financial_file->id;
                    $first_item=false;
                }

                $supplier_name=$element->{'supplier'}->{'name'};
                $vendor_number="";
                $account_number="";
                $last_invoice_id=$element->{'id'};

                $qVendor = DB::table('vendor')
                                ->select(DB::raw('vendor_number, account_number'))
                                ->where('name', strtoupper(trim($supplier_name)))
                                ->get();

                foreach ($qVendor as $value) {
                    $vendor_number=$value->vendor_number;         
                    $account_number=$value->account_number;
                }

                if($vendor_number=="")
                    $vendor_number=$supplier_name;

                $flag_invoice=false;

                foreach ($element->{'invoice-lines'}->{'invoice-line'} as $item) {
                    $job_number_str=$item->{'account'}->{'segment-1'};
                    if($job_number_str!=""){
                        //Line with one account
                        $job_number_array = explode("-", $job_number_str);

                        $financial=Financial::create([
                            'id_financial_file'         => $file_id,
                            'invoice_id'                => $element->{'id'},
                            'vendor_number'             => $vendor_number, 
                            'invoice_number'            => $element->{'invoice-number'},
                            'po_number'                 => '', 
                            'invoice_date'              => $element->{'invoice-date'},
                            'invoice_amount'            => $element->{'gross-total'}, 
                            'account_number'            => $account_number, 
                            'job_number'                => trim($job_number_array[0]), 
                            'distribution_amount'       => $item->{'accounting-total'}, 
                        ]);    
                        $flag_invoice=true;
                    }else{
                        //Multiple accounts
                        foreach ($item->{'account-allocations'} as $item_inner) {
                            foreach ($item_inner as $item_i) {
                                //echo "Amount:".($item_i->{'amount'})."<br/>";   
                                $job_number_str = $item_i->{'account'}->{'segment-1'};
                                $job_number_array = explode("-", $job_number_str);
                                $financial=Financial::create([
                                    'id_financial_file'         => $file_id,
                                    'invoice_id'                => $element->{'id'},
                                    'vendor_number'             => $vendor_number, 
                                    'invoice_number'            => $element->{'invoice-number'},
                                    'po_number'                 => '', 
                                    'invoice_date'              => $element->{'invoice-date'},
                                    'invoice_amount'            => $element->{'gross-total'}, 
                                    'account_number'            => $account_number, 
                                    'job_number'                => trim($job_number_array[0]), 
                                    'distribution_amount'       => $item_i->{'amount'}, 
                                ]);
                                $flag_invoice=true;
                            }   
                        }
                    }
                }
                //Mark them as exported
                if($mark_exported==1 && $flag_invoice){
                    $client->request('PUT', 'https://dirtpros.coupahost.com/api/invoices/'.$element->{'id'}.'?exported=true', [
                        'headers' => [
                            'X-COUPA-API-KEY' => 'e41f2e5bf5869e70760f5b3fd8e5b12b2629d3f2',
                            'ACCEPT'          => 'application/xml',
                        ]
                    ]);
                }

            }

              
        }


        if($flag==0){
            //Update Invoice PTR
            $element_invoice->value=$last_invoice_id;
            $element_invoice->save();


            $result = Financial::where('id_financial_file',$file_id)->paginate(500);
        
            return view('financial.view',
                [
                'id'           =>  $file_id,
                'financials'   =>  $result,
                'xml'          =>  $xml ]
            );
        }elseif($flag==1){
            return view('financial.up_to_date',
                []
            );
        }elseif($flag==2){
            $list_invoices_numbers=array();
            $ptr=$ptr_invoice+1;

            for ($i=($ptr_invoice+1); $i < $first_item_id ; $i++) { 
                $list_invoices[]=$i;
                $res = $client->request('GET', 'https://dirtpros.coupahost.com/api/invoices/'.$i, [
                    'headers' => [
                        'X-COUPA-API-KEY' => 'e41f2e5bf5869e70760f5b3fd8e5b12b2629d3f2',
                        'ACCEPT'          => 'application/xml',
                    ]
                ]);
                $code=$res->getStatusCode();    
                $header=$res->getHeader('content-type');
                $contents=$res->getBody();
                $string_xml=(string) $contents;
                $xml_item = simplexml_load_string($string_xml);
                $list_invoices_numbers[]=$xml_item->{'invoice-number'};
            }

            return view('financial.verify_correlative',
                ['list_invoices'            =>  $list_invoices,
                 'list_invoices_numbers'    =>  $list_invoices_numbers,
                 'first_item_id'            =>  $first_item_id,
                 'ptr'                      =>  $ptr]
            );
        }
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function view($id)
    {
         return view('financial.view', ['id'    =>  $id, 'financials' => Financial::where('id_financial_file', $id)->paginate(500)]);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */

    public function export($id)
    {
        $result = Financial::where('id_financial_file',$id)->paginate(500);
        $data=array();
        foreach ($result as $row) {
            $data[]=array(
                'VendorNumber'     =>  $row->vendor_number, 
                'InvoiceNumber'    =>  $row->invoice_number, 
                'PONumber'         =>  '',
                'InvoiceDate'      =>  date( 'm/d/Y', strtotime( $row->invoice_date) ),
                'InvoiceAmount'    =>  $row->invoice_amount,
                'GLNumber'         =>  $row->account_number,
                'JobNumber'        =>  $row->job_number,
                'DistributionAmount'=> $row->distribution_amount,
                'WorkTicketNumber'       =>  $row->work_ticket_number,
                );
        }
        Excel::create('financialOutput'.strtotime("now"), function($excel) use($data){

            $excel->sheet('Excel sheet', function($sheet) use($data){
                 $sheet->fromArray($data);
            });

        })->download('csv');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
     
    public function mark_exported()
    {
        $client = new Client();
        $res = $client->request('PUT', 'https://dirtpros.coupahost.com/api/invoices/17713'.'?exported=true', [
            'headers' => [
                'X-COUPA-API-KEY' => 'e41f2e5bf5869e70760f5b3fd8e5b12b2629d3f2',
                'ACCEPT'          => 'application/xml',
            ]
        ]);
        $code=$res->getStatusCode();    
        $header=$res->getHeader('content-type');
        $contents=$res->getBody();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
     
    public function structure()
    {
        $client = new Client();
        //Has more than 1 account = 17739
        // One account = 17738

        $res = $client->request('GET', 'https://dirtpros.coupahost.com/api/invoices/18220', [
            'headers' => [
                'X-COUPA-API-KEY' => 'e41f2e5bf5869e70760f5b3fd8e5b12b2629d3f2',
                'ACCEPT'          => 'application/xml',
            ]
        ]);
        $code=$res->getStatusCode();    
        $header=$res->getHeader('content-type');
        $contents=$res->getBody();
        $string_xml=(string) $contents;
        $xml = simplexml_load_string($string_xml);

        return view('financial.structure',
            ['xml'          =>  $xml ]
        );
    }


    

}
