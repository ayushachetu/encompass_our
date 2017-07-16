$(document).ready(function(){
  window.applyValidation(true, '#manager-form', 'top');
  window.applyValidation(true, '#talent-form', 'top');

  $("select").select2({
	    placeholder: "Select Option"
	});

  $(".date-format").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
  $(".inside-form-option").hide();
  $( "#action-select" ).change(function() {
  	  $(".inside-form-option").hide();	
	  $("#action-form-"+$(this).val()).show();
	});


  $('#btn_replace_position').click(function() {
    var value=$("#replace_position").val();
    if(value==0){
      $('#btn_replace_position i').removeClass();
      $('#btn_replace_position i').addClass('ion-ios-minus');
      $('#btn_replace_position').removeClass('btn-icon-primary');
      $('#btn_replace_position').removeClass('btn-primary');
      $('#btn_replace_position').addClass('btn-danger');
      $('#btn_replace_position').addClass('btn-icon-danger');

      $("#replace_position").val(1);
      $("#container-add-person").show();  
    }else{
      $('#btn_replace_position i').removeClass();
      $('#btn_replace_position i').addClass('ion-ios-plus-outline');
      $('#btn_replace_position').removeClass('btn-icon-danger');
      $('#btn_replace_position').removeClass('btn-danger');
      $('#btn_replace_position').addClass('btn-icon-primary');
      $('#btn_replace_position').addClass('btn-primary');
      $("#replace_position").val(0);
      $("#container-add-person").hide(); 
    }
    
  });   

  $('#btn-submit').click(function() {
      if($('#action-select').val()==""){
        alert('Select an Action to continue.');
        return false;
      }

      if($('#action-select').val()=="1"){
        if($('#form1_position_job_code').val()==""){
          alert('Select a Position Job Code to continue.');
          return false;   
        }  
      }

      if($('#action-select').val()==2){
        if($('#form2_reason_termination').val()==""){
          alert('Select Reason for Termination to continue.');
          return false;   
        }  
        if($('#replace_position').val()==1){
          if($('#form2_add_position_job_code').val()==""){
            alert('Select a Position Job Code to replace position to continue.');
            return false; 
          }  

        }
      }  

      if($('#action-select').val()==3){
        if($('#form3_change_requested').val()==""){
          alert('Select a Change Request to continue.');
          return false; 
        }

        if($('#form3_position_job_code').val()==""){
          alert('Select a Change in Position Job Code to continue.');
          return false; 
        }
        
      }  



  });  


  $( "#btn-add-new-line" ).click(function() {
    /*$clone = $('#line-elements-new #item-1 .row').clone( false, false );
    $clone.find( ".employee-name-input" ).value('');
    $clone.find( ".employee-number-input" ).value( '' );
    $clone.find( ".account-number-input" ).value( '' );*/
    var new_element='<div class="row"><div class="col-md-4"><label><small>Name(*)</small></label><input class="form-control employee-name-input" name="employee_name[]" id="" type="text" placeholder="" value="" data-validation="required"></div><div class="col-md-4"><label><small>Employee Number(*)</small></label><input class="form-control employee-number-input" name="employee_number[]" id="" type="text" placeholder="" value="" data-validation="required number"></div><div class="col-md-4"><label><small>Account Number(*)</small></label><input class="form-control account-number-input" name="account_number[]" id="" type="text" placeholder="" value="" data-validation="required number"></div></div>';
    $('#line-elements-new').append(new_element);
    
    
    
  });




  
});