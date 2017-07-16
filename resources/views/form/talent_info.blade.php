<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <tr>
                <td class="text-right"><strong>Name:</strong></td>
                <td>{{$data->name}}</td>
            </tr>
            <tr>
                <td class="text-right"><strong>Email:</strong></td>
                <td>{{$data->email}}</td>
            </tr>
            <tr>
                <td class="text-right"><strong>Site Name:</strong></td>
                <td>{{$data->site_name}}</td>
            </tr>
            <tr>
                <td class="text-right"><strong>Site Account Number:</strong></td>
                <td>{{$data->site_account_number}}</td>
            </tr>
            <tr>
                <td class="text-right"><strong>Type:</strong></td>
                <td>{{$type_list[$data->type]}}</td>
            </tr>
            <?php if($data->type==1){?>
                <tr>
                    <td class="text-right"><strong>Date Needed:</strong></td>
                    <td>{{$data_inside['date_needed']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Position Title:</strong></td>
                    <td>{{$data_inside['position_title']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Position Rate:</strong></td>
                    <td>{{$data_inside['position_rate']}} | {{$data_inside['measure_rate']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Position Job Code:</strong></td>
                    <td>{{$data_inside['position_job_code']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Work Schedule:</strong></td>
                    <td>
                        <?php if(is_array($data_inside['work_schedule'])){ ?>
                            @foreach ($data_inside['work_schedule'] as $key_i => $value_i)
                                {{$value_i}}
                            @endforeach                                         
                        <?php }else{ ?>
                                {{$data_inside['work_schedule']}}
                        <?php }?>   
                    </td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Shift:</strong></td>
                    <td>
                        <?php if(is_array($data_inside['shift'])){ ?>
                            @foreach ($data_inside['shift'] as $key_i => $value_i)
                                {{$value_i}}
                            @endforeach                                         
                        <?php }else{ ?>
                                {{$data_inside['shift']}}
                        <?php }?>   
                    </td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Hours(per Week):</strong></td>
                    <td>{{$data_inside['hours_per_week']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Requirements:</strong></td>
                    <td valign="top">{{$data_inside['site_specific']}}</td>
                </tr>

            <?php }?>
            <?php if($data->type==2){?>
                <tr>
                    <td class="text-right"><strong>Employee Name:</strong></td>
                    <td>{{$data_inside['employee_name']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Employee Number:</strong></td>
                    <td>{{$data_inside['employee_number']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Efective Date:</strong></td>
                    <td>{{$data_inside['effective_date']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Reason for termination:</strong></td>
                    <td>{{$data_inside['reason_termination']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Explanation for Termination:</strong></td>
                    <td valign="top">{{$data_inside['explanation_termination']}}</td>
                </tr>
                <?php if(isset($data_inside['position_title'])){?>
                    <tr>
                        <td colspan="2" class="text-center"><strong>Replace Position</strong></td>
                        
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Position Title:</strong></td>
                        <td>{{$data_inside['position_title']}}</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Position Rate:</strong></td>
                        <td>{{$data_inside['position_rate']}} | {{$data_inside['measure_rate']}}</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Position Job Code:</strong></td>
                        <td>{{$data_inside['position_job_code']}}</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Work Schedule:</strong></td>
                        <td>
                            <?php if(is_array($data_inside['work_schedule'])){ ?>
                                @foreach ($data_inside['work_schedule'] as $key_i => $value_i)
                                    {{$value_i}}
                                @endforeach                                         
                            <?php }else{ ?>
                                    {{$data_inside['work_schedule']}}
                            <?php }?>   
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Shift:</strong></td>
                        <td>
                            <?php if(is_array($data_inside['shift'])){ ?>
                                @foreach ($data_inside['shift'] as $key_i => $value_i)
                                    {{$value_i}}
                                @endforeach                                         
                            <?php }else{ ?>
                                    {{$data_inside['shift']}}
                            <?php }?>   
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Hours(per Week):</strong></td>
                        <td>{{$data_inside['hours_per_week']}}</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Requirements:</strong></td>
                        <td valign="top">{{$data_inside['site_specific']}}</td>
                    </tr>
                <?php }?>
            <?php }?>
            <?php if($data->type==3){?>
                <tr>
                    <td class="text-right"><strong>Employee Name:</strong></td>
                    <td>{{$data_inside['employee_name']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Employee Number:</strong></td>
                    <td>{{$data_inside['employee_number']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Efective Date:</strong></td>
                    <td>{{$data_inside['effective_date']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Change Requested:</strong></td>
                    <td>{{$data_inside['change_requested']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Explanation of Change:</strong></td>
                    <td valign="top">{{$data_inside['explanation_change']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Work Schedule:</strong></td>
                    <td>
                        <?php if(is_array($data_inside['work_schedule'])){ ?>
                            @foreach ($data_inside['work_schedule'] as $key_i => $value_i)
                                {{$value_i}}
                            @endforeach                                         
                        <?php }else{ ?>
                                {{$data_inside['work_schedule']}}
                        <?php }?>   
                    </td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Shift:</strong></td>
                    <td>
                        <?php if(is_array($data_inside['shift'])){ ?>
                            @foreach ($data_inside['shift'] as $key_i => $value_i)
                                {{$value_i}}
                            @endforeach                                         
                        <?php }else{ ?>
                                {{$data_inside['shift']}}
                        <?php }?>   
                    </td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Hours(per Week):</strong></td>
                    <td>{{$data_inside['hours_per_week']}}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center"><strong><small>Change in Pay Rate</small></strong></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Current Rate:</strong></td>
                    <td>{{$data_inside['current_rate']}} | {{$data_inside['current_measure_rate']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>New Rate:</strong></td>
                    <td>{{$data_inside['new_rate']}} | {{$data_inside['new_measure_rate']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Change in Job Title:</strong></td>
                    <td>{{$data_inside['position_title']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Change in Position Job Code:</strong></td>
                    <td>{{$data_inside['position_job_code']}}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Additional Changes:</strong></td>
                    <td>{{$data_inside['additional_changes']}}</td>
                </tr>
            <?php }?>
        </table>
    </div>
</div>