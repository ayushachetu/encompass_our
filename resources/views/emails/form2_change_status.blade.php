<p>Change of Status Requested:</p>
<table>
@foreach ($data as $key => $value)
    @if ($key!=="_token")
	    <tr>
	    	<td>
	    		<span style="font-size:12px">{{$key}}</span>
	    	<td>
	    	<td>
	    		<span style="font-size:12px">
	    			<?php if(is_array($value)){ ?>
						@foreach ($value as $key_i => $value_i)
							{{$value_i}}
						@endforeach						    				
	    			<?php }else{ ?>
	    					{{$value}}
	    			<?php }?>	
	    			
	    		</span>
	    	<td>
    	</tr>
    @endif
@endforeach
</table>