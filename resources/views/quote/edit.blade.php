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
				<span class="main-text">Edit Quote </span>
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
					<form class="form" method="post" id="form-wrapper">
						{!! csrf_field() !!}
						<div class="row">
							<div class="col-md-9">
								<!--Input Form-->
								<div class="form-group">
									<label class="control-label">Subject:</label>
									<input type="text" name="subject" class="form-control"  value="{{ $quote->subject }}" data-validation="required">
								</div>
								<!--Input Form-->
							</div>
							<div class="col-md-3">
								<!--Input Form-->
								<div class="form-group">
									<label class="control-label">Job Start Date:</label>
									<input type="text"  name="start_date" class="form-control datepicker"  value="{{ date( 'm/d/Y', strtotime( $quote->start_date) ) }}">
								</div>
								<!--Input Form-->
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<!-- xselectize form   -->
								<div class="form-group">
									<label class="control-label">Account Number:</label>
									<select class="name_search form-control" name="job_number">
											<option value="">Select a Job</option>
										@foreach ($job_list as $job)
											<option value="{{$job->job_number}}" {{($quote->job_number==$job->job_number)?'selected="selected"':''}}>{{$job->job_number}} - {{$job->job_description}}</option>
										@endforeach
									</select>
								</div>
								<!-- xselect form   -->
								<!--Textarea Form-->
								<div class="form-group">
									<label class="control-label">Description <small>(Visible to client)</small>:</label>
									<textarea  rows="12" cols="30" class="form-control text-area" name="description">{{ $quote->description }}</textarea>
								</div>
								<!--Textarea Form-->

							</div>
							<div class="col-md-6">
								<!--Input Form-->
								<div class="form-group">
									<label class="control-label">Client Name:</label>
									<input type="text" name="client_name" class="form-control"  value="{{ $quote->client_name }}" data-validation="required">
								</div>
								<!--Input Form-->
								<!--Input Form-->
								<div class="form-group">
									<label class="control-label">Client Email:</label>
									<input type="text" name="client_email" class="form-control"  value="{{ $quote->client_email }}" >
								</div>
								<!--Input Form-->
								<!--Input Form-->
								<div class="form-group">
									<label class="control-label">Managed by:</label>
									<select class="form-control" name="managed_by">
										<option value="2" {{ ($quote->managed_by==2)?'selected="selected"':'' }}>Manager</option>
										<option value="1" {{ ($quote->managed_by==1)?'selected="selected"':'' }}>Dispatch</option>
									</select>
								</div>
								<!--Input Form-->

							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<h3 class="">ITEMS QUOTE <span class="pull-right btn btn-green">Hours: <span id="label-minutes">{{number_format($quote->minutes/60,2,'.','')}}</span></h3>
								<div class="table-responsive">
									<table class="table table-hover table-bordered">
										<thead>
											<tr>
												<th class="">Description</th>
												<th class="">Qty (SF/Unit)</th>
												<th class="">
													<ul class="list-inline checkboxes-radio pull-left" style="margin:0;">
			                                            <li>
			                                            	<input type="checkbox" id="unit_field" name="unit_field" value="1" {{ (($quote->unit_field==1)?'checked="checked"':'') }}/>
			                                            	<label for="unit_field"><span></span></label>
			                                            </li>
			                                        </ul>
													Price ($)</th>
												<th class="">Tax (%)</th>
												<th class="">
													<ul class="list-inline checkboxes-radio pull-left" style="margin:0;">
			                                            <li>
			                                            	<input type="checkbox" id="discount_field" name="discount_field" value="1" {{ (($quote->discount_field==1)?'checked="checked"':'') }}/>
			                                            	<label for="discount_field"><span></span></label>
			                                            </li>
			                                        </ul>
													Discount (%)</th>
												<th class="">Total Item ($)</th>
												<th></th>
											</tr>
										</thead>
										<tbody id="item-list" data="{{$quote_items_count}}" data-count="{{$quote_items_count}}">
											<?php 
												$list_cnt=1; 
												$parent_anc=0;
												$parent_cnt=0;
											?>
											@forelse($quote_items as $item)
												<?php 
													$readonly_field="";
													$parent_anc=1;
													if($item->custom_item==0)
														$readonly_field='readonly="readonly"';

													if($item->parent_id==0){
														$subitem_field="<a class='btn btn-info btn-sm pull-left' onclick='sub_item(".$list_cnt.")'><span class='ti-plus'></span></a>";
														$parent_anc=$list_cnt;
														$parent_cnt=1;
														$display_id=$list_cnt;
													}else{
														$subitem_field="<a class='btn btn-warning btn-sm pull-left'><span class='ti-arrow-circle-up'></span></a>";
														$display_id=($list_cnt-1).'-'.$parent_cnt;
													}
												?>
												<tr data='{{$display_id}}' id='quote-line-{{$display_id}}' <?=(($item->parent_id!=0)?"class='child-item-".$parent_anc."'":'')?>>
													<td class='td-large'><input type='hidden' class='parent-item' name='parent_item[]' value='{{$item->parent_id}}'><input type='hidden' name='quote_item_id[]' value='{{$item->id}}'><input type='hidden' name='id_item[]' value='{{$item->quote_data_id}}'><input type='hidden' class='ln-minutes' name='minutes_list[]' value='{{$item->base_minutes}}'><input type='hidden' class='ln-minutes-item' name='minutes_item_list[]' value='{{$item->minutes}}'><input type='hidden' class='ln-days-item' name='days_item[]' value='{{$item->days}}'><input type='hidden' class='ln-custom-item' name='custom_item[]' value='{{$item->custom_item}}'>{!!$subitem_field!!}<input type='text' name='description_item[]' value='{{$item->item_subject}}' class='form-control description_item_solo'></td>
													<td><input  name='qty_item[]' value='{{$item->quantity}}' class='form-control line-qty'></td>
													<td><input  type='hidden' name='labor_item[]' value='{{$item->labor}}'><input  type='hidden' class='line-labor-hours' name='labor_hours_item[]' value='{{($item->labor_hours!=0)?$item->labor_hours:''}}'><input  type='hidden' name='material_item[]' value='{{$item->material}}'><input  type='hidden' name='sub_contractor_item[]' value='{{$item->sub_contract}}'><input  type='hidden' name='margin_item[]' value='{{$item->margin}}'><input  name='price_item[]' value='{{$item->price}}' class='form-control ln-price-item' {{$readonly_field}}></td>
													<td><input name='tax_item[]' value='{{$item->tax}}' class='form-control line-tax'></td>
													<td><input name='discount_item[]' value='{{$item->discount}}' class='form-control line-discount'></td>
													<td><input  name='total_item[]' value='{{$item->total}}' class='form-control ln-total-item' {{$readonly_field}}> </td>
													<td class='td-small'><a class='btn btn-danger' onclick='remove_item("{{$display_id}}")'><span class='ti-close'></span></a></td>
												</tr>
												<?php if($item->parent_id==0)
														$list_cnt++;
													  else
													  	$parent_cnt++;
												?>
											@empty
											<tr>
												<td colspan="7">
													<h4 class="text-center">No items added</h4>
												</td>
											</tr>
											@endforelse
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="row">
                            <div class="col-md-12 text-center">
                                <a id="btn-new-item-quote" href="javascript:void(0)" class="btn btn-primary btn-round"><span class="icon ion-android-add"></span> New Item</a>
                            </div>
                        </div>
                        <div class="row">
							<div class="col-md-12">
								<!--Textarea Form-->
								<div class="form-group">
									<label class="control-label">Internal Notes:</label>
									<textarea placeholder="" rows="12" cols="30" class="form-control text-area" name="notes">{{ $quote->notes }}</textarea>
								</div>
								<!--Textarea Form-->
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="text-right">
									<input type="hidden" name="minutes" id="minutes" value="{{$quote->minutes}}">
									<input type="hidden" name="draft" id="draft" value="0">
									<a href="/quotes">Cancel</a>
									<button type="button" class="btn btn-warning btn-lg" id="btn-draft">Save as Draft</button>
									<button type="button" class="btn btn-primary btn-lg" id="btn-save">Complete & Export</button>
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
					<div class="col-md-5">
						<!--Input Form-->
						<div class="form-group">
							<label class="control-label">Task Type:</label>
							<select name="type_category" id="type_category" class="form-control">
								<option value="0">All</option>
								@foreach ($quote_category_type as $q_cat)
									<option id="option-cat-{{$q_cat->id}}" value="{{$q_cat->type_id}}">{{$q_cat->name}} ({{$q_cat->count}})</option>
								@endforeach
							</select>
						</div>
						<!--Input Form-->
					</div>
					<div class="col-md-7">
						<!--Input Form-->
						<div class="form-group">
							<label class="control-label">Item:</label>
							<select name="type_item" id="type_item" class="form-control data-select">
								<option value="0">Select item</option>
								@foreach ($quote_data_list as $q_item)
									<option id="option-item-{{$q_item->id}}" value="{{$q_item->id}}" data-price="{{$q_item->price}}">{{$q_item->data_subject}} - ${{number_format($q_item->price,2)}}</option>
								@endforeach
							</select>
						</div>
						<!--Input Form-->
					</div>	
				</div>
				<div class="item-detail-panel" >
					<div class="row">
						<div class="col-md-12 text-right">
							<ul class="list-inline checkboxes-radio pull-right" style="margin:0;">
	                            <li>
	                            	<input type="checkbox" id="custom_item" name="custom_item" value="1" />
	                            	<label for="custom_item" style="color:#000; font-size: 1.2em;"><span></span>Custom Item</label>
	                            </li>
	                        </ul>
						</div>
					</div>
					<div class="row">
						<div class="col-md-9">
							<!--Input Form-->
							<div class="form-group">
								<label class="control-label">Item Description:</label>
								<input type="text" name="description_item" id="description_item" class="form-control"  value="">
							</div>
							<!--Input Form-->
						</div>
						<div class="col-md-3">
							<!--Input Form-->
							<div class="form-group">
								<label class="control-label">Days</label>
								<input type="text" name="days_item" id="days_item" class="form-control"  value="">
							</div>
							<!--Input Form-->
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<!--Input Form-->
							<div class="form-group">
								<label class="control-label">Qty (SF/Units):</label>
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
					<div id="details-fields-pane" style="display: none;">
						<div class="row">
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-6 text-right">
										<span>Total Labor($)</span>
									</div>
									<div class="col-md-6">
										<!--Input Form-->
										<div class="form-group">
											<input type="text" name="labor_item" id="labor_item" class="form-control input-watch-detail"  value="" >
										</div>
										<!--Input Form-->
									</div>	
								</div>
								<div class="row">
									<div class="col-md-6 text-right">
										<span>Total Material($)</span>
									</div>
									<div class="col-md-6">
										<!--Input Form-->
										<div class="form-group">
											<input type="text" name="material_item" id="material_item" class="form-control input-watch-detail"  value="" >
										</div>
										<!--Input Form-->
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 text-right">
										<span>Sub-Contractor Quote($)</span>
									</div>
									<div class="col-md-6">
										<!--Input Form-->
										<div class="form-group">
											<input type="text" name="sub_contractor_item" id="sub_contractor_item" class="form-control input-watch-detail"  value="" >
										</div>
										<!--Input Form-->
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-6 text-right">
										<span>Margin %</span>
									</div>
									<div class="col-md-6">
										<!--Input Form-->
										<div class="form-group">
											<input type="text" name="margin_item" id="margin_item" class="form-control input-watch-detail"  value="" >
										</div>
										<!--Input Form-->
									</div>
								</div>
								<div class="row darker-bg">
									<div class="col-md-6 text-right">
										<span>Total Labor Hours</span>
									</div>
									<div class="col-md-6">
										<!--Input Form-->
										<div class="form-group">
											<input type="text" name="hours_labor_item" id="hours_labor_item" class="form-control input-watch-detail"  value="" >
										</div>
										<!--Input Form-->
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col-md-1 col-md-offset-8">
							<div class="quote-calc-panel" style="display: none;">
								<a href="javascript:void(0)" id="btn-calculate" class="btn btn-default"><span class="fa fa-calculator"></span></a>
							</div>
						</div>
						<div class="col-md-3">
							<!--Input Form-->
							<div class="form-group">
								<label class="control-label"><strong>Total ($):</strong></label>
								<input type="text" name="total_item" id="total_item" class="form-control"  value="" readonly="readonly">
							</div>
							<!--Input Form-->
						</div>
					</div>
				</div>	
			</div>
			<div class="item-detail-panel-select">
				<h4 class="text-center">Select an item to load details</h4>
				<br/>
			</div>
			<div class="modal-footer item-detail-panel">
				<input type="hidden" name="minutes_item" id="minutes_item">
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
  <script type="text/javascript" src="{{ asset('assets/js/app.quote.js?v=1.1') }}"></script>
@endsection
