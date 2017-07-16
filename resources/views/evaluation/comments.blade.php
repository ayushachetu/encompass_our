<div class="inner-panel">
	<table class="table table-bordered" id="">
		<tr>
			<th><strong>Comments</strong></th>
		</tr>	
		@forelse ($evaluations as $evaluation)
			<tr>
				<td>{{ $evaluation->description}}</td>	
			</tr>
		@empty
		     <tr>
				<td>No comments yet</td>	
			</tr>
		@endforelse
	</table>
</div>
