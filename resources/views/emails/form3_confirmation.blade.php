<p>The following team members have been scheduled for training on <strong><?=date("F", mktime(0, 0, 0, $data['date_month_training'], 10))?> <?=$data['date_day_training']?>, <?=$data['date_year_training']?></strong></p>
<p><strong><a href="https://www.dropbox.com/s/04i8xlia5ise23w/TRAINING_Month1.pdf?dl=0" style="text-decoration: underline; color:#000;">See detailed calendar for time & location</a></strong></p>
<p>Comments: <?=($data['comment']!="")?$data['comment']:'No Comments'?></p>
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