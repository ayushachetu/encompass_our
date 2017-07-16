<p>Submitted Job Request Information:</p>
<table>
@foreach ($data as $key => $value)
    
    @if ($key!=="_token")
	    <tr>
	    	<td>
	    		<span style="font-size:12px">{{$key}}</span>
	    	<td>
	    	<td>
	    		<span style="font-size:12px">{{$value}}</span>
	    	<td>
    	</tr>
    @endif
@endforeach
</table>