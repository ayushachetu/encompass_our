<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Quote</title>
    <link href="{{ asset('assets/css/style-pdf.css') }}" rel="stylesheet" type="text/css" >
  </head>
  <body>
  	  <div id="header">	
  	  	<img src="http://www.encompassonsite.com/hubfs/site/images/Encompass_Logo_4Color_NoShadow_Large_200.png">		
  	  </div>
  	  <div id="wrapper-content">  
        <div id="quote-header">
          <h1>Quote: QT-{{$quote->job_number}}-{{$quote->correlative}}<span>Quote Date: {{ date( 'm/d/Y', strtotime( $quote->updated_at) ) }}</span></h1>
        </div>
        <div id="quote-subject">
         	{{$quote->subject}}
        </div>
        <div id="user-info">
        	<div>
        		<table class="table">
        			<tr>
                        <td class="info-col-1">
                            Work expected date:
                        </td>
                        <td class="info-col-2">
                            {{ date( 'm/d/Y', strtotime( $quote->start_date) ) }}
                        </td>
                        <td class="info-col-1"></td>
                    </tr>
                    <tr>
                        <td height="2"></td>
                    </tr>
                    <tr>
        				<td class="info-col-1">
        					Prepare by:
        				</td>
        				<td class="info-col-2">
        					{{$user->first_name}} {{$user->last_name}}
        				</td>
        				<td class="info-col-1"></td>
        			</tr>
        			<tr>
        				<td height="2"></td>
        			</tr>
        			<tr>
        				<td class="info-col-1">
        					Email:
        				</td>
        				<td class="info-col-2">
        					{{$user->email}}
        				</td>
        				<td class="info-col-1"></td>
        			</tr>
        			<tr>
        				<td height="2"></td>
        			</tr>
        			<tr>
        				<td class="info-col-1">
        					Client Name:
        				</td>
        				<td class="info-col-2">
        					{{$quote->client_name}}
        				</td>
        				<td class="info-col-1"></td>
        			</tr>
        			<tr>
        				<td height="2"></td>
        			</tr>
        			<tr>
        				<td class="info-col-1">
        					Client Email:
        				</td>
        				<td class="info-col-2">
        					{{$quote->client_email}}
        				</td>
        				<td class="info-col-1"></td>
        			</tr>
        			<tr>
        				<td height="2"></td>
        			</tr>
        			<tr>
        				<td class="info-col-1">
        					Account:
        				</td>
        				<td class="info-col-2">
        					{{$job->job_description}}
        				</td>
        				<td class="info-col-1"></td>
        			</tr>
        		</table>
        	</div>
        </div>
        <h2>Items</h2>
        <?php 
            $column_count=3;
            if($quote->unit_field==1){
                $column_count++;
            }
            if($quote->discount_field==1){
                $column_count++;
            }
        ?>
        <table class="table table-hover table-bordered" border="0" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th class="table-hightlight">Description</th>
					<th class="table-hightlight">Qty (SF/Unit)</th>
					@if($quote->unit_field==1)
                    <th class="table-hightlight">Price ($)</th>
                    @endif
					<th class="table-hightlight">Tax (%)</th>
                    @if($quote->discount_field==1)
					<th class="table-hightlight">Discount (%)</th>
                    @endif
					<th class="table-hightlight">Total Item ($)</th>
				</tr>
			</thead>
			<tbody id="item-list">
				<?php 
					$list_cnt=1;
					$total=0;
				?>
				@forelse($quote_items as $item)
                    <?php $base_total=$item->price*$item->quantity; ?>
					<tr>
						<td class='td-subject'><?=($item->parent_id!=0)?' &#8226;':''?> {{$item->item_subject}}</td>
						<td class='text-right'>{{$item->quantity}}</td>
						@if($quote->unit_field==1)
                        <td class='text-right'>${{number_format($item->price,2)}}</td>
                        @endif
						<td class='text-right'>{{number_format($item->tax,2)}}</td>
                        @if($quote->discount_field==1)
						<td class='text-right'>{{number_format($item->discount,2)}}</td>
                        @endif
						<td class='text-right'>${{number_format($item->total,2)}}</td>
					</tr>
					<?php 
						$total+=($item->total);
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
					<td class="td-medium text-right table-hightlight">${{number_format($total,2)}}</td>
				</tr>
			</tbody>
		</table>
		<div>
			<h2>Note</h2>
			<div class="notes-box">
				{{$quote->description}}
			</div>
		</div>
	      <div id="footer">
	      	<div>
	      		This quote is valid for 30 days, after which prices are subject to change. By accepting this quote, you are agreeing to allow us to perform and subsequently bill the specified services. Any additional services not explicitly included in this quote are subject to additional charges, according to the rates that are in effect at the time or that have been agreed upon.
	      	</div>
	      </div>
      </div>
  </body>
</html>