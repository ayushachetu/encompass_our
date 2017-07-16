<!DOCTYPE html>
<html>
<head><title></title></head>
<body>
<table cellpadding="0" cellspacing="0" style="border-left: 10px solid #1a4479;border-right: 10px solid #1a4479;">
	<tr>
		<td colspan="3" height="10" width="700" style="background:#1a4479;"></td>
	</tr>
	<tr>
		<td colspan="3" height="10" width="700" style="background:#fff;"></td>
	</tr>
	<tr>
		<td width="25"></td>
		<td>
			<img style="width: 250px;" src="http://www.encompassonsite.com/hubfs/site/images/Encompass_Logo_4Color_NoShadow_Large_200.png">
		</td>
		<td width="25"></td>
	</tr>
	<tr>
		<td colspan="3" height="10" width="700" style="background:#1a4479;"></td>
	</tr>
	<tr>
		<td colspan="3" height="10" width="700" style="background:#fff;"></td>
	</tr>
	<tr>
		<td width="25"></td>
		<td>
			<p style="font-size: 14px; font-weight: bold;font-family: Arial;">Dispatch Quotes</p>
		</td>
		<td width="25"></td>
	</tr>
	<tr>
		<td width="25"></td>
		<td>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="50"><span style="font-size: 10px; font-family: Arial;"><strong>Quote</strong></span></td>	
					<td width="5"></td>
					<td width="125" valign="top"><span style="font-size: 10px; font-family: Arial;"><strong>Subject</strong></span></td>
					<td width="5"></td>
					<td width="40"><span style="font-size: 10px; font-family: Arial;"><strong>Start Date</strong></span></td>
					<td width="5"></td>
					<td width="200" valign="top"><span style="font-size: 10px; font-family: Arial;"><strong>Description</strong></span></td>
					<td width="5"></td>
					<td width="50"><span style="font-size: 10px; font-family: Arial;"><strong>Qty</strong></span></td>
					<td width="5"></td>
					<td width="50"><span style="font-size: 10px; font-family: Arial;"><strong>Price</strong></span></td>
					<td width="5"></td>
					<td width="50"><span style="font-size: 10px; font-family: Arial;"><strong>Total</strong></span></td>
					<td width="5"></td>
					<td width="50"><span style="font-size: 10px; font-family: Arial;"><strong>Duration</strong></span></td>
				</tr>
				@foreach ($data_dispatch as $item) 
				<tr><td colspan="15" height="5"></td></tr>
				<tr>
					<td width="50" valign="top"><span style="font-size: 10px; font-family: Arial;">{{$item['name']}}</span></td>	
					<td width="5"></td>
					<td width="125" valign="top"><span style="font-size: 10px; font-family: Arial;">{{$item['subject']}}</span></td>
					<td width="5"></td>
					<td width="40" valign="top"><span style="font-size: 10px; font-family: Arial;">{{$item['start_date']}}</span></td>
					<td width="5"></td>
					<td width="200" valign="top"><span style="font-size: 10px; font-family: Arial;">{{$item['item_subject']}}</span></td>
					<td width="5"></td>
					<td width="50" valign="top"><span style="font-size: 10px; font-family: Arial;">{{$item['quantity']}}</span></td>
					<td width="5"></td>
					<td width="50" valign="top" style="text-align: right;"><span style="font-size: 10px; font-family: Arial;">{{number_format($item['price'],2)}}</span></td>
					<td width="5" ></td>
					<td width="50" valign="top" style="text-align: right;"><span style="font-size: 10px; font-family: Arial;">{{number_format($item['total'],2)}}</span></td>
					<td width="5"></td>
					<td width="50" valign="top" style="text-align: right;"><span style="font-size: 10px; font-family: Arial;">{{number_format($item['duration'],2)}}</span></td>
				</tr>
				<?php if(count($item['item_inside'])>0){?>
					@foreach ($item['item_inside'] as $item_child) 
						<tr><td colspan="15" height="5"></td></tr>
						<tr>
							<td width="50"><span style="font-size: 10px; font-family: Arial;"></span></td>	
							<td width="5"></td>
							<td width="125" valign="top"><span style="font-size: 10px; font-family: Arial;"></span></td>
							<td width="5"></td>
							<td width="40" valign="top"><span style="font-size: 10px; font-family: Arial;"></span></td>
							<td width="5"></td>
							<td width="200" valign="top"><span style="font-size: 10px; font-family: Arial;">{{$item_child['item_subject']}}</span></td>
							<td width="5"></td>
							<td width="50" valign="top"><span style="font-size: 10px; font-family: Arial;">{{$item_child['quantity']}}</span></td>
							<td width="5"></td>
							<td width="50" valign="top" style="text-align: right;"><span style="font-size: 10px; font-family: Arial;">{{number_format($item_child['price'],2)}}</span></td>
							<td width="5"></td>
							<td width="50" valign="top" style="text-align: right;"><span style="font-size: 10px; font-family: Arial;">{{number_format($item_child['total'],2)}}</span></td>
							<td width="5"></td>
							<td width="50" valign="top" style="text-align: right;"><span style="font-size: 10px; font-family: Arial;">{{number_format($item_child['duration'],2)}}</span></td>
						</tr>
					@endforeach	
				<?php }?>
				@endforeach
				<tr><td colspan="15" height="40"></td></tr>
			</table>
		</td>
		<td width="25"></td>
	</tr>
	<tr><td colspan="3" height="10" width="700" style="background:#fff;"></td></tr>
	<tr><td colspan="3" height="5" width="700" style="background:#cccccc;"></td></tr>
	<tr>
		<td colspan="3" style="text-align: center;background:#cccccc;">
			&copy; Encompass Onsite Solutions. All Rights Reserved.
		</td>
	</tr>
	<tr>
		<td colspan="3" height="5" width="700" style="background:#cccccc;"></td>
	</tr>
	<tr>
		<td colspan="3" height="40" width="700" style="background:#1a4479;"></td>
	</tr>
</table>
</body>
</html>



