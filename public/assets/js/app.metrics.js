$(document).ready(function(){

    $( "#btn-comments" ).on('click', function(e){
        var data_val=$(this).attr('data');
        if(data_val==0){
          $('#comments-panel').html('<div class="inner-panel">Loading...</div>');
          $('#btn-comments').removeClass('btn-gray');    
          $('#btn-comments').addClass('btn-primary');    
          var data = {
            data: $(this).attr('data'),
            user_id: $(this).attr('user-id'),
          };
          $.ajax({
            url: '/loadcomments',
            type: "get",
            data: data,
            success: function(data){
              $('#comments-panel').html(data.html);
              $('#btn-comments').attr('data','1');    
            },
            error: function(data){
               
            },
          }); 
        }else{
          $(this).attr('data','0');
          $('#comments-panel').html("");
          $('#btn-comments').removeClass('btn-primary');    
          $('#btn-comments').addClass('btn-gray');    
        }
        

    });

});