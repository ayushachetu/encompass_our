<table class="table table-bordered" id="displayTable">
	<thead>
		<tr>
			<th class="">Job Number</th>
			<th class="">Job Name</th>
			<th class="">Actual</th>
			<th class="">Target</th>
			<th class="">Variance</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$total=0;
			$total_budget=0;
			$actual_total=0;
			$actual_total_budget=0;
		?>
		@foreach ($list as $item)
			<?php 
				$budget_value=$item->bg_total_job+$item->bg_total;
				if(is_numeric($budget_value) && $budget_value!=0)
					$variance=number_format((($item->at_total-$budget_value)*100)/$budget_value,2);
				else
					$variance="";
			?>
			<tr class="table-row">
				<td>{{ $item->job_number }}</td>
				<td>{{ $item->job_description}}</td>
				<td class="text-right">${{ number_format($item->at_total,2)}}</td>
				<td class="text-right">${{ number_format($budget_value,2)}}</td>
				<td class="text-right">${{ number_format($item->at_total-$budget_value,2)}}</td>
			</tr>
			<?php 
				$total+=$budget_value;
				$actual_total+=$item->at_total;
			?>
		@endforeach
		<!--<tr class="bg-danger">
				<td colspan="2" class="text-right"><span style="color:#fff;">TOTAL:</span></td>
				<td class="text-right"><span style="color: #fff;">${{ number_format($actual_total,2)}}</span></td>
				<td class="text-right"><span style="color: #fff;">${{ number_format($total,2)}}</span></td>
				<td></td>
				<td></td>
				
				
		</tr>-->
	</tbody>
</table>
<script type="text/javascript">
	
	$(document).ready(function(){
		$('#label-total-actual').html('{{number_format($actual_total, 2)}}');
		$('#label-total-budget').html('{{number_format($total, 2)}}');
	});
</script>
