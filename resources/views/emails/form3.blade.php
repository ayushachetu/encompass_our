<p>Training Registration Information:</p>
<table>

    <tr>
    	<td>
    		<span style="font-size:12px">Name</span>
    	<td>
    	<td>
    		<span style="font-size:12px"><?=$data['name']?></span>
    	<td>
	</tr>
	<tr>
    	<td>
    		<span style="font-size:12px">Email</span>
    	<td>
    	<td>
    		<span style="font-size:12px"><?=$data['email']?></span>
    	<td>
	</tr>

	<tr>
    	<td>
    		<span style="font-size:12px">Date Registration</span>
    	<td>
    	<td>
    		<span style="font-size:12px"><?=date("F", mktime(0, 0, 0, $data['date_month_training'], 10))?> <?=$data['date_day_training']?>, <?=$data['date_year_training']?></span>
    	<td>
	</tr>

	<tr>
    	<td>
    		<span style="font-size:12px">Comment</span>
    	<td>
    	<td>
    		<span style="font-size:12px"><?=$data['comment']?></span>
    	<td>
	</tr>
    
</table>
<p>
Employees List
</p>
<table>
		<tr>
			<td>Employee Name</td>
			<td width="20"></td>
			<td>Employee Number</td>
			<td width="20"></td>
			<td>Account Number</td>
		</tr>
	<?php for ($i=0; $i < $data['count_items'] ; $i++) { ?>
		<tr>
			<td><?=$data['employee_name'][$i]?></td>
			<td width="20"></td>
			<td><?=$data['employee_number'][$i]?></td>
			<td width="20"></td>
			<td><?=$data['account_number'][$i]?></td>
		</tr>

	<?php } ?>
</table>
<p>
Export List
</p>
<table>
        <tr>
            <td>Employee Number</td>
            <td>Job Number</td>
            <td>Taining Date</td>
        </tr>
    <?php for ($i=0; $i < $data['count_items'] ; $i++) { ?>
        <tr>
            <td><?=$data['employee_number'][$i]?> </td>
            <td><?=$data['account_number'][$i]?> </td>
            <td><?=$data['date_month_training']?>/<?=$data['date_day_training']?>/<?=$data['date_year_training']?></td>
        </tr>

    <?php } ?>
</table>
