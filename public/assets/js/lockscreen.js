
window.onload=function(){
}


$(document).ready(function(){
    $('.btn-submit-lock').on('click', function(e){
        e.preventDefault();
        $(this).text('ummm let me recognize you......');

        setTimeout(function(){
            $(location).attr('href',"/");      
        },'3000')
    });

    $('.sign.btn').on('click', function(e){
        /*e.preventDefault();*/
        $('.flip-container .flipper,.load_pulse').addClass('flipped');
    });
});