$(document).ready(function(){
  $('#employeenumbertab').css('cursor','pointer');
  $('#emailtab').css('cursor','pointer');

  $( "#employeenumbertab" ).on('click', function(e){
    e.preventDefault();
    $('#emailtab').removeClass('btn-success');
    $('#emailtab').addClass('btn-default');
    $('#employeenumbertab').addClass('btn-success');
    $('#employeenumbertab').removeClass('btn-default');
    $('#emailtabcontent').hide();
    $('#employeenumbertabcontent').show();
    $('#emailInput').val('');
    $('#passwordInput').val('');
  
  });

  $( "#emailtab" ).on('click', function(e){
    e.preventDefault();
    $('#emailtab').addClass('btn-success');
    $('#emailtab').removeClass('btn-default');
    $('#employeenumbertab').removeClass('btn-success');
    $('#employeenumbertab').addClass('btn-default');
    $('#emailtabcontent').show();
    $('#employeenumbertabcontent').hide();
    $('#employeeInput').val('');
    $('#passwordInput').val('');
  });


  $( "#sign-form" ).submit(function(event) { 
    event.preventDefault();
    var $form = $( this );
    var data = $form.serialize();
    $.ajax({
      url: 'admin',
      type: "post",

      data: data,
      success: function(data){
        if(data.status==1){
        	location.reload();
        }else{
        	$('.flip-container .flipper,.load_pulse').removeClass('flipped');
        	$('.sign-alert').html('<div class="alert bg-danger text-white">'+data.message+'</div>');
        }
        
      },
      error: function(data){
      	var error_str="";
      	$.each(data['responseJSON'], function(idx, item){
	     error_str=error_str+item+"<br/>";
	   	});
      	$('.sign-alert').html('<div class="alert bg-danger text-white">'+error_str+'</div>');
        $('.flip-container .flipper,.load_pulse').removeClass('flipped');
      },
    });      
  }); 

});