<html>
<head>
	<meta charset="text/html">
</head>
<body style="text-align: center;">
	{!! $body['standard_chart'] !!}

	{!! $body['filter_chart'] !!}

	{!! $body['survey_chart'] !!}

	@foreach ($body['per_survey_cahrt'] as $survey => $html)
		{!! $html !!}
	@endforeach

	{!! $body['rating_chart'] !!}

	{!! $body['emp_count'] !!}
</body>
</html>