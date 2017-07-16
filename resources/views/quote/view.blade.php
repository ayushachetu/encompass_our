@extends('layouts.default')
@section('styles')

@endsection
@section('content')	
	<div class="wrapper ">
	@include('includes.sidebar')
	<div class="content" id="content">
	<div class="overlay"></div>				
	@include('includes.topbar')
		<!-- Page Header -->
		<div class="page_header">
			<div class="pull-left">
				<a href='/quotes'><i class="icon ti-comment-alt page_header_icon"></i></a>
				<span class="main-text">Quote :: QT-{{$quote->job_number}}-{{$quote->correlative}}</span>
				<p class="text"><a href="/quotes">< Back to List</a></p>
			</div>
			<div class="right pull-right">
				<a class="btn btn-red btn-lg" target="_blank" href="/quote/pdf/{{$quote->id}}"><i class="ion-ios-cloud-download-outline"></i> <span>PDF</span></a>
				<a class="btn btn-info btn-lg"  href="/quote/email/{{$quote->id}}"><i class="ion-ios-email-outline"></i> <span>Send Email</span></a>
			</div>
		</div>
		
		<!-- /pageheader -->
		<!-- main content -->
		<div class="main-content">
			<!--theme panel-->
			<div class="panel">
				<div class="panel-body">
					@if (Session::has('errors'))
                    <div class="alert alert-danger" role="alert">
                    <ul>
                        <strong>Error Message : </strong>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if (Session::has('status'))
	                    <div class="alert bg-success text-white" role="alert">
	                    	<strong>{{ Session::get('status') }}</strong>
	                    </div>
                    @endif
						@if($quote->exported==0)
							<form class="form" method="post" id="form-wrapper">
								{!! csrf_field() !!}
								<div class="row status-panel">
								<div class="col-md-6">
									<div class="">
										<label class="control-label">Quote Status:</label>	
										<select class="form-control" name="status">
											<option value="1" {{($quote->status==1)?'selected':''}}>In Progress</option>
											<option value="5" {{($quote->status==5)?'selected':''}}>Approve</option>
											<option value="10" {{($quote->status==10)?'selected':''}}>Denied</option>
										</select>
									</div>
									
								</div>
								<div class="col-md-3">
									<br/>
									<button class="btn btn-primary btn-lg">Save</button>
								</div>
								<div class="col-md-3">
									
								</div>
								</div>
							</form>
							<hr/>
						@endif
						<form class="form" method="post" id="form-wrapper-section" action="/quote/edit_quote/{{ $quote->id }}">
						{!! csrf_field() !!}
							<div class="col-md-9">
								<!--Input Form-->
								<div class="form-group">
									<label class="control-label">Subject:</label>
									@if($quote->status==1)
										<input type="text" name="subject" class="form-control"  value="{{ $quote->subject }}" data-validation="required">
									@else
										<div class="input-display">{{ $quote->subject }}</div>
									@endif		
								</div>
								<!--Input Form-->
							</div>
							<div class="col-md-3">
								<!--Input Form-->
								<div class="form-group">
									<label class="control-label">Start Date:</label>
									@if($quote->status==1)
										<input type="text"  name="start_date" class="form-control datepicker"  value="{{ date( 'm/d/Y', strtotime( $quote->start_date) ) }}" data-validation="required">
									@else	
										<div class="input-display">{{ date( 'm/d/Y', strtotime( $quote->start_date) ) }}</div>
									@endif
								</div>
								<!--Input Form-->
							</div>
							<div class="col-md-6">
								<!-- xselectize form   -->
								<div class="form-group">
									<label class="control-label">Account Number:</label>
									@if($quote->status==1)
										<select class="name_search form-control" name="job_number">
											<option value="">Select a Job</option>
											@foreach ($job_list as $job)
												<option value="{{$job->job_number}}" {{($quote->job_number==$job->job_number)?'selected="selected"':''}}>{{$job->job_number}} - {{$job->job_description}}</option>
											@endforeach
										</select>
									@else
										@foreach ($job_list as $job)
											<?=($quote->job_number==$job->job_number)?'<div class="input-display">'.$job->job_number.'-'.$job->job_description.'</div>':''?>
										@endforeach
									@endif
								</div>
								<!-- xselect form   -->
								<!--Textarea Form-->
								<div class="form-group">
									<label class="control-label">Description<small>(Visible to client)</small>:</label>
									@if($quote->status==1)
										<textarea  rows="12" cols="30" class="form-control text-area" name="description">{{ $quote->description }}</textarea>
									@else
										<p class="textarea-display">{{ $quote->description }}</p>
									@endif
								</div>
								<!--Textarea Form-->

							</div>
							<div class="col-md-6">
								<!--Input Form-->
								<div class="form-group">
									<label class="control-label">Client Name:</label>
									@if($quote->status==1)
										<input type="text" name="client_name" class="form-control"  value="{{ $quote->client_name }}" data-validation="required">
									@else
										<div class="input-display">{{ $quote->client_name }}</div>
									@endif
									
								</div>
								<!--Input Form-->
								<!--Input Form-->
								<div class="form-group">
									<label class="control-label">Client Email:</label>
									@if($quote->status==1)
										<input type="text" name="client_email" class="form-control"  value="{{ $quote->client_email }}" >
									@else
										<div class="input-display">{{ $quote->client_email }}</div>
									@endif
								</div>
								<!--Input Form-->
								<!--Input Form-->
								<div class="form-group">
									<label class="control-label">Manage by:</label>
									@if($quote->status==1)
										<!--Input Form-->
											<select class="form-control" name="managed_by">
												<option value="2" {{ ($quote->managed_by==2)?'selected="selected"':'' }}>Manager</option>
												<option value="1" {{ ($quote->managed_by==1)?'selected="selected"':'' }}>Dispatch</option>
											</select>
										
										<!--Input Form-->
									@else
										<div class="input-display">{{ ($quote->managed_by==1)?'Dispatch':'Manager' }}</div>
									@endif
								</div>
								<!--Input Form-->

							</div>
							<div class="col-md-12">
								<h3 class="text-left">ITEMS QUOTE<span class="pull-right btn btn-green">Hours: <span id="label-minutes">{{number_format($quote->minutes/60,2,'.','')}}</span></h3>
								<div class="table-responsive">
									<?php 
										$column_count=4;
										if($quote->discount_field==1){
											$column_count=5;
										}
									?>
									<table class="table table-hover table-bordered">
										<thead>
											<tr>
												<th class="table-hightlight">Description</th>
												<th class="table-hightlight">Qty (SF/Unit)</th>
												<th class="table-hightlight">	
												Price ($)
												@if($quote->unit_field==1)
													<span class="ion-eye pull-right"></span>
												@else
													<span class="ion-eye-disabled pull-left"></span>
												@endif
												</th>
												<th class="table-hightlight">Tax (%)</th>
												@if($quote->discount_field==1)
													<th class="table-hightlight">Discount (%)</th>
												@endif
												<th class="table-hightlight">Total Item ($)</th>
											</tr>
										</thead>
										<tbody id="item-list" data="{$quote_items_count}}" data-count="{{$quote_items_count}}">
											<?php 
												$list_cnt=1;
												$total=0;
											?>
											@forelse($quote_items as $item)
												<?php $base_total=$item->price*$item->quantity; ?>
												<tr>
													<td class='td-large'><input type='hidden' name='quote_item_id[]' value='{{$item->id}}'><input type='hidden' name='id_item[]' value='{{$item->quote_data_id}}'><?=($item->parent_id!=0)?' <span class="chevron ti-angle-right"></span>':''?> {{$item->item_subject}}</td>
													<td class='text-right'>{{$item->quantity}}</td>
													<td class='td-medium'>
														${{number_format($item->price,2)}}</td>
													<td class='text-right'>{{number_format($item->tax,2)}}</td>
													@if($quote->discount_field==1)
														<td class='text-right'>{{number_format($item->discount,2)}}</td>
													@endif
													<td class='td-medium'>${{number_format($item->total,2)}}</td>
												</tr>
												<?php 
													$total+=$item->total;
													$list_cnt++;
												?>
											@empty
											<tr>
												<td colspan="6">
													<h4 class="text-center">No items added</h4>
												</td>
											</tr>
											@endforelse
											<tr>
												<td colspan="{{$column_count}}" class="text-right table-hightlight"><strong>TOTAL:</strong></td>
												<td class="td-medium table-hightlight">${{number_format($total,2)}}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>

							<div class="col-md-12">
								<!--Textarea Form-->
								<div class="form-group">
									<label class="control-label">Internal Notes:</label>
									@if($quote->status==1)
										<textarea placeholder="" rows="12" cols="30" class="form-control text-area" name="notes">{{ $quote->notes }}</textarea>
									@else
										<p class="textarea-display">{{ $quote->notes }}</p>
									@endif
								</div>
								<!--Textarea Form-->
							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="text-right">		
										<a href="/quotes">Cancel</a>
										@if($quote->status==1)
											<button type="submit" class="btn btn-primary btn-lg" id="btn-save">Update</button>
										@endif
									</div>
								</div>
							</div>

						</form>
				</div>
			</div>
			<!--theme panel-->
		</div>
		<!-- /main content -->	
	</div>
</div>
<!-- Modal Large -->
<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="ti-close"></span></button>
				<h4 class="modal-title" id="myModalLabel1">Add Item</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<!--Input Form-->
						<div class="form-group">
							<label class="control-label">Item:</label>
							<select name="type_item" id="type_item" class="form-control data-select">
								<option value="">Select item</option>
								@foreach ($quote_data_list as $q_item)
									<option id="option-item-{{$q_item->id}}" value="{{$q_item->id}}" data-price="{{$q_item->price}}">{{$q_item->data_subject}} - ${{number_format($q_item->price,2)}}</option>
								@endforeach
							</select>
						</div>
						<!--Input Form-->
					</div>
					
				</div>
				<div class="row">
					<div class="col-md-3">
						<!--Input Form-->
						<div class="form-group">
							<label class="control-label">Qty (SF/Unit):</label>
							<input type="text" name="qty_item" id="qty_item" class="form-control input-watch"  value="">
						</div>
						<!--Input Form-->
					</div>
					<div class="col-md-3">
						<!--Input Form-->
						<div class="form-group">
							<label class="control-label">Price ($):</label>
							<input type="text" name="price_item" id="price_item" class="form-control"  value="" readonly="readonly">
						</div>
						<!--Input Form-->
					</div>
					<div class="col-md-3">
						<!--Input Form-->
						<div class="form-group">
							<label class="control-label">Tax (%):</label>
							<input type="text" name="tax_item" id="tax_item" class="form-control input-watch"  value="">
						</div>
						<!--Input Form-->
					</div>
					<div class="col-md-3">
						<!--Input Form-->
						<div class="form-group">
							<label class="control-label">Discount (%):</label>
							<input type="text" name="discount_item" id="discount_item" class="form-control input-watch"  value="">
						</div>
						<!--Input Form-->
					</div>
				</div>
				<div class="row">
					<div class="col-md-3 col-md-offset-9">
						<!--Input Form-->
						<div class="form-group">
							<label class="control-label"><strong>Total ($):</strong></label>
							<input type="text" name="total_item" id="total_item" class="form-control"  value="" readonly="readonly">
						</div>
						<!--Input Form-->
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="btn-insert-line" class="btn btn-primary btn-block btn-lg">Add</button>
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
  <script type="text/javascript" src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/wow.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.loadmask.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.accordion.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/materialize.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/build/d3.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/nvd3/nv.d3.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/select2.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery.multi-select.js') }}"></script>	
  <script type="text/javascript" src="{{ asset('assets/js/bootstrap-datepicker.js') }}"></script>	
  <script type="text/javascript" src="{{ asset('assets/js/bootstrap-colorpicker.js') }}"></script>	
  <script type="text/javascript" src="{{ asset('assets/js/jquery.maskedinput.js') }}"></script>	
  <script type="text/javascript" src="{{ asset('assets/js/form-elements.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/form-validation/jquery.form-validator.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/form-validation.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/app.quote.js') }}"></script>
@endsection
