<p>Exit Interview:</p>
<table>
    <tr>
        <td colspan="3">
            <span style="font-size:12px"><strong>Manager</strong></span>
        <td>
    </tr>
    <tr>
    	<td>
    		<span style="font-size:12px">Name</span>
    	<td>
        <td width="20"></td>
    	<td>
    		<span style="font-size:12px"><?=$data['name']?></span>
    	<td>
	</tr>
	<tr>
    	<td>
    		<span style="font-size:12px">Email</span>
    	<td>
        <td width="20"></td>
    	<td>
    		<span style="font-size:12px"><?=$data['email']?></span>
    	<td>
	</tr>

    <tr>
        <td colspan="3">
            <span style="font-size:12px"><strong>Employee</strong></span>
        <td>
    </tr>
    <tr>
        <td>
            <span style="font-size:12px">Name</span>
        <td>
        <td width="20"></td>
        <td>
            <span style="font-size:12px"><?=$data['employee_name']?></span>
        <td>
    </tr>
    <tr>
        <td>
            <span style="font-size:12px">Number</span>
        <td>
        <td width="20"></td>
        <td>
            <span style="font-size:12px"><?=$data['employee_number']?></span>
        <td>
    </tr>
</table>
<p>
Questions
</p>
<table>
		<tr>
			<td><span style="font-size:11px">1. Why have you decided to leave Encompass Onsite?</span></td>
		</tr>
        <tr>
            <td><span style="font-size:11px"><?=$data['question_1']?></span></td>
        </tr>
        <tr><td height="10"></td></tr>
        <tr>
            <td><span style="font-size:11px">2. What did you like about your job?</span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px"><?=$data['question_2']?></span></td>
        </tr>
        <tr><td height="10"></td></tr>
        <tr>
            <td><span style="font-size:11px">3. What didn't you like about your job?</span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px"><?=$data['question_3']?></span></td>
        </tr>
        <tr><td height="10"></td></tr>
        <tr>
            <td><span style="font-size:11px">4. What was the most satisfying part of your job?</span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px"><?=$data['question_4']?></span></td>
        </tr>
        <tr><td height="10"></td></tr>
        <tr>
            <td><span style="font-size:11px">5. What caused you frustration at work?</span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px"><?=$data['question_5']?></span></td>
        </tr>
        <tr><td height="10"></td></tr>
        <tr>
            <td><span style="font-size:11px">6. Describe your relationship with your supervisor.</span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px"><?=$data['question_6']?></span></td>
        </tr>
        <tr><td height="10"></td></tr>
        <tr>
            <td><span style="font-size:11px">7. Have you accepted another position? If yes, with what company?</span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px"><?=$data['question_7']?></span></td>
        </tr>
        <tr><td height="10"></td></tr>
        <tr>
            <td><span style="font-size:11px">8. Is there anything we can do to give you a reason to stay?</span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px"><?=$data['question_8']?></span></td>
        </tr>
        <tr><td height="10"></td></tr>
        <tr>
            <td><span style="font-size:11px">9. Will you recommend Encompass Onsite to a friend as a good place to work?</span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px"><?=$data['question_9']?></span></td>
        </tr>
        <tr><td height="10"></td></tr>
        <tr>
            <td><span style="font-size:11px">10. Do you have anything else you would like to discuss?</span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px"><?=$data['question_10']?></span></td>
        </tr>
        <tr><td height="20"></td></tr>
        <tr>
            <td><span style="font-size:11px">Additional Comments:</span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px"><?=$data['comment']?></span></td>
        </tr>
</table>