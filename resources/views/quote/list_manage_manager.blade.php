@extends('layouts.default')
@section('styles')
<link href="{{ asset('assets/css/sweet-alerts/sweetalert.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('assets/css/jtree.css') }}" rel="stylesheet" type="text/css" >
@endsection
@section('content')
	<div class="piluku-preloader text-center">
		<div class="loader">Loading...</div>
	  </div>
	<div class="wrapper">
	@include('includes.sidebar')
	<div class="content" id="content">
		<div class="overlay"></div>			
		@include('includes.topbar')

		<!-- Page Header -->
		<div class="page_header">
			<div class="pull-left">
				<i class="icon ti-comment-alt page_header_icon"></i>
				<span class="main-text">Quotes</span>
			</div>
			<div class="right pull-right">
				@if($role==1 || $role==4)
					<a href="/quotes/export" class="btn btn-gray btn-lg" id="btn-add-new">Export Quotes</a>
				@endif
				<a href="/quotes/create" class="btn btn-primary btn-lg" id="btn-add-new">Add New</a>
			</div>

			<div class="header-option-panel">				
				<ul class="list-inline checkboxes-radio">
	                <li>Quote Filter:</li>
	                <li>
	                    <input type="checkbox" class="filter_option" id="draft" name="filter_draft" value="1" {{(($param1==1)?'checked="checked"':'')}}/>
	                    <label for="draft"><span></span>Draft</label>
	                </li>
	                <li>
	                    <input type="checkbox" class="filter_option" id="sent" name="filter_sent" value="1" {{(($param2==1)?'checked="checked"':'')}}/>
	                    <label for="sent"><span></span>Sent Quotes</label>
	                </li>
	                <li>
	                    <input type="checkbox" class="filter_option" id="approve" name="filter_approve" value="1" {{(($param3==1)?'checked="checked"':'')}}/>
	                    <label for="approve"><span></span>Approved Quotes</label>
	                </li>
	                <li>
	                    <input type="checkbox" class="filter_option" id="denied" name="filter_denied" value="1" {{(($param4==1)?'checked="checked"':'')}}/>
	                    <label for="denied"><span></span>Denied Quotes</label>
	                </li>
	                <li>
	                    <input type="checkbox" class="filter_option_all" id="filter_all" name="filter_all" value="1" {{(($param0==1)?'checked="checked"':'')}}/>
	                    <label for="filter_all"><span></span>All</label>
	                </li>
	            </ul>
			</div>

		</div>
		<!-- /pageheader -->  
		<div class="main-content">
			<div class="row">
				<div class="col-md-12">
					<!-- panel -->
					<div class="panel panel-piluku panel-users">
						@if (Session::has('status'))
	                    <div class="alert bg-success text-white" role="alert">
	                    	<strong>{{ Session::get('status') }}</strong>
	                    </div>
	                    @endif
						<div class="panel-body">
							<div>
								<div class="row">
									<div class="col-md-offset-7 col-md-2">
										<label>Order By</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-offset-7 col-md-2">
										<select class="form-control" id="order-type-1" name="order_type_2">
											<option value="1" {{(($order_by_1==1)?"selected='selected'":"")}}>Date</option>
											<option value="2" {{(($order_by_1==2)?"selected='selected'":"")}}>Account Number</option>
											<option value="3" {{(($order_by_1==3)?"selected='selected'":"")}}>Correlative Number</option>
											<option value="4" {{(($order_by_1==4)?"selected='selected'":"")}}>Total</option>
											<option value="5" {{(($order_by_1==5)?"selected='selected'":"")}}>User</option>
										</select>	
									</div>
									<div class="col-md-2">
										<select class="form-control" id="order-type-2" name="order_type_1">
											<option value="1" {{(($order_by_2==1)?"selected='selected'":"")}}>High - Low</option>
											<option value="2" {{(($order_by_2==2)?"selected='selected'":"")}}>Low - High</option>
										</select>
									</div>
									<div class="col-md-1">
										<a id="order-by" ref="javascript:void(0)" class="btn btn-primary btn-block btn-lg"><span class="fa fa-filter"></span></a>
									</div>
								</div>
							</div>
							<br/>
							<div class="clearfix"></div>
							<div class="table-responsive">
								<table class="table table-hover table-bordered">
									<thead>
										<tr>
											<th class="">Number</th>
											<th class="">Account Number</th>
											<th class="">Subject</th>
											<th class="">Total</th>
											<th class="">Date</th>
											<th class="">User</th>
											<th class=""></th>
											<th>&nbsp;</th>
										</tr>
									</thead>
									<tbody>
										@forelse ($list as $item)
											<tr class="table-row">
												<td>
													@if(!$item->draft)
														<nobr>QT-{{ $item->job_number }}-{{ $item->correlative }}</nobr>
													@else
														<small>Pending</small>
													@endif
												</td>
												<td>{{ $item->job_number }}</td>
												<td>{{ $item->subject }}</td>
												<td class="text-right">${{ number_format($item->total,2) }}</td>
												<td>{{ date( 'm/d/Y', strtotime($item->created_at) ) }}</td>
												<td>{{ $item->first_name}} {{ $item->last_name}}</td>
												<td><span class="btn btn-{{$quote_style[$item->status]}} btn-round">{{ $quote_status[$item->status] }} {{ ($item->status==1 && $item->email_quote==1)?'& Emailed':'' }} {{ ($item->status==5 && $item->exported==1)?'& Exported':'' }}</span></td>
												<td class="text-right td-small-two">
													<a href="/quote/clone/{{ $item->id }}"  title="clone" class="btn btn-default"><i class="ion ion-ios-browsers"></i></a>
													@if($item->draft)
														<a href="/quote/edit/{{ $item->id }}" title="edit" class="btn btn-green"><i class="ion ion-edit"></i></a>
													@endif	
													@if(!$item->draft)
														<a href="/quote/view/{{ $item->id }}" title="view" class="btn btn-primary"><i class="ion ion-clipboard"></i></a>
													@endif
													@if($item->draft || $item->status==1)	
														<button type="button" class="btn btn-danger btn-delete" data-id="{{ $item->id }}" data-toggle="modal" data-target="#deletemodal">
															<i class="ion ion-trash-a"></i>
														</button>
													@endif
													
												</td>
											</tr>
										@empty
										    <tr><td colspan="8" class="text-center">No records found.</td></tr>
										@endforelse
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- /panel -->
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					{!! $list->render() !!}
				</div>
			</div>	 
		</div>
	</div>  
</div>
<!-- wrapper -->
<!-- Default Modal -->
<div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form class="form" method="post" id="form-wrapper" action="/quote/delete">
		{!! csrf_field() !!}
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="ti-close"></span></button>
					<h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
				</div>
				<div class="modal-body">
					<h3><i class="ion ion-trash-a"></i> Click on confirm to delete the quote.</h3>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="delete_item" id="delete_item" value="0">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-danger">Confirm</button>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection

@section('scripts')
  <script>
    jQuery(window).load(function () {
      $('.piluku-preloader').addClass('hidden');
    });
  </script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/wow.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.loadmask.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.accordion.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/materialize.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/build/d3.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/nvd3/nv.d3.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/sparkline.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/bic_calendar.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/widgets.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>
  <script type="text/javascript">
  	
  	$('.filter_option').click(function() {
	      $('.piluku-preloader').removeClass('hidden');
	      var option=0;
	      if($('#draft:checked').val()==1 && $('#sent:checked').val()==1 && $('#approve:checked').val()==1 && $('#denied:checked').val()==1){
	      	option=1;
	      }
	      $.ajax({
	        url:  '/quote/filter-quote/'+$('#draft:checked').val()+'/'+$('#sent:checked').val()+'/'+$('#approve:checked').val()+'/'+$('#denied:checked').val()+'/'+option,
	        type: "get",
	        success: function(dataResponse){
	          location.reload();
	        },
	        error: function(data){
	          
	        },
	      });  
	  });
  	$('.filter_option_all').click(function() {
	      $('.piluku-preloader').removeClass('hidden');
	      var option=0;
	      if($('#filter_all:checked').val()==1){
	      	option=1;
	      }else{
	      	option=2;
	      }
	      $.ajax({
	        url:  '/quote/filter-quote/'+$('#draft:checked').val()+'/'+$('#sent:checked').val()+'/'+$('#approve:checked').val()+'/'+$('#denied:checked').val()+'/'+option,
	        type: "get",
	        success: function(dataResponse){
	          location.reload(true);
	        },
	        error: function(data){
	          
	        },
	      });  
	  });

  	$('#order-by').click(function() {
  		$('.piluku-preloader').removeClass('hidden');
	      
	      $.ajax({
	        url:  '/quote/order-by-quote/'+$('#order-type-1').val()+'/'+$('#order-type-2').val(),
	        type: "get",
	        success: function(dataResponse){
	          location.reload(true);
	        },
	        error: function(data){
	          
	        },
	      });
  	});	

  	

  	$('.btn-delete').click(function() {
  		$('#delete_item').val($(this).attr('data-id'));
  	});


  </script>
@endsection
