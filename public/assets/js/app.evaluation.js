$(document).ready(function(){

   $('.btn-evaluation').on('click', function (e) {        
        var user=$(this).attr('data');

        var param1=$('.evaluation-'+user+' .param1').val();
        var param2=$('.evaluation-'+user+' .param2').val();
        var param3=$('.evaluation-'+user+' .param3').val();
        var param4=$('.evaluation-'+user+' .param4').val();
        var param5=$('.evaluation-'+user+' .param5').val();
        var description=$('.evaluation-'+user+' .description').val();

        /*Verify Parameters have been entered*/

        if(param1==0 || param2==0 || param3==0 || param4==0 || param5==0){
          $('#message-'+user).html("<div class='alert bg-danger text-white'>Please evaluate all values.</div>");
          setTimeout(function(){ $('#message-'+user).html("");}, 2000);
          return false;
        }

        swal.setDefaults({ allowOutsideClick: true });

        swal("Completed", "Evaluation has been saved!", "success");

         var data = {
            'user'          :  user,
            'param1'        :  param1,
            'param2'        :  param2,
            'param3'        :  param3,
            'param4'        :  param4,
            'param5'        :  param5,
            'description'   :  description,
            '_token'        :  $('input[name="_token"]').val()
          };
          $.ajax({
            url:  'evaluation/submit',
            type: "post",
            data: data,
            success: function(dataResponse){
              var DateDisplay=dataResponse.dateTime;
              $('.evaluation-'+user).removeAttr("style");
              $('.evaluation-'+user).slideUp( "slow", function() {});

               $('.evaluation-'+user).show();
               $clone_item = $('.evaluation-'+user).clone(true,true);
               
               $('.evaluation-'+user).remove();
               
               /*$(".name_search option[value='"+user+"']").remove();*/
               $(".select2-selection__rendered").html("All");  
               $("#wrapper-evaluation").append($clone_item);
               
               $("#date-review-"+user+" span").html(DateDisplay);  

              /*Clean Status*/
               $('.evaluation-'+user+' .param-val').val(0);
               $('.evaluation-'+user+' .param').attr("data-status", 0);
               $('.evaluation-'+user+' #description-'+user).val("");
               $('.evaluation-'+user+' .param').removeClass("option-1-active");
               $('.evaluation-'+user+' .param').removeClass("option-2-active");
               $('.evaluation-'+user+' .param').removeClass("option-3-active");
              $('.evaluation-item').show();
              
            },
            error: function(data){
              
            },
          });

        
    });

   $('.param').on('click', function (e) {        
      var type=$(this).attr('data-type');
      var status=$(this).attr('data-status');
      var param=$(this).attr('data-param');
      var user=$(this).attr('data-user');

      $('.evaluation-'+user+' .p'+param).removeClass('option-1-active');
      $('.evaluation-'+user+' .p'+param).removeClass('option-2-active');
      $('.evaluation-'+user+' .p'+param).removeClass('option-3-active');

      $('.evaluation-'+user+' .p'+param).attr('data-status', 0);
      $('.evaluation-'+user+' .p'+param).attr('data-status', 0);
      $('.evaluation-'+user+' .p'+param).attr('data-status', 0);

      

      if(status==0){
        $(this).attr('data-status', 1);
        $(this).addClass('option-'+type+'-active');
        $('.evaluation-'+user+' .param'+param).val(type);
      }else{
        $(this).attr('data-status', 0);
        $('.evaluation-'+user+' .param'+param).val(0);
      }
      



   });

   $(".name_search").select2({
        placeholder: "Select User"
    });

   $('.name_search').on('change', function (e) {        
      if($(this).val()!=0){
        $('.evaluation-item').hide();
        $('.evaluation-'+$(this).val()).show();
      }else{
        $('.evaluation-item').show();
      }



   });

    $('.input-date').datepicker({
        format: "mm-yyyy",
        daysOfWeekDisabled: "1,5",
        startView: "months", 
        minViewMode: "months"
    });

});