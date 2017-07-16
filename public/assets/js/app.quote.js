$(document).ready(function(){
  $( "#btn-save" ).click(function() {
      $( "#form-wrapper" ).submit();
  });

  $( "#btn-draft" ).click(function() {
      $("#draft").val(1);
      $( "#form-wrapper" ).submit();
  });


  $( "#btn-new-item-quote" ).click(function() {
    $('#itemModal').modal('show');
    $('.item-detail-panel').hide();
    $('.item-detail-panel-select').show();
    $('#details-fields-pane').hide();
    $('.quote-calc-panel').hide();

    $('#type_item').html('<option>Loading...</option>');


    $.ajax({
        url:  '/quote/get-list/1/0',
        type: "get",
        success: function(dataResponse){
          $('#type_item').html(dataResponse.html);
          add_item_watcher();
        },
        error: function(data){
          
        },
      });
    
    $('#qty_item').val(1);
    $('#price_item').val(0);
    $('#tax_item').val(0);
    $('#discount_item').val(0);
    $('#total_item').val(0);
    $('#description_item').val("");

    $('#labor_item').val("");
    $('#days_item').val(1);
    $('#hours_labor_item').val("");
    $('#material_item').val("");
    $('#margin_item').val("");
    $('#sub_contractor_item').val("");




    $('.data-select option:eq(0)').prop('selected', true);
    
    $('#type_section option:eq(0)').prop('selected', true);
    $('#type_category option:eq(0)').prop('selected', true);
    
    $('#custom_item').removeAttr('checked');
    $('#price_item').attr('readonly', 'readonly');
    $('#total_item').attr('readonly', 'readonly');
    
  });

  $('#btn-calculate').click(function() {

    var qty     =$('#qty_item').val();
    var price   =$('#price_item').val();
    var tax     =$('#tax_item').val();
    var discount=$('#discount_item').val();
    
      var base_total=0;
      var calc_tax=0;
      var calc_discount=0;
      base_total=parseFloat(qty)*parseFloat(price);
      if(tax>0){
          calc_tax=(base_total*parseFloat(tax/100));  
        }
       if(discount>0){
          calc_discount=(base_total*parseFloat(discount/100));
       }  

      calc=base_total+calc_tax-calc_discount;
      
      $('#total_item').val(parseFloat(calc).toFixed(2));
    
  });

  $('#custom_item').click(function() {
     if($('#custom_item:checked').val()==1){
      $('#price_item').removeAttr('readonly', 'readonly');
      $('#total_item').removeAttr('readonly', 'readonly');
      $('#details-fields-pane').show();
      $('.quote-calc-panel').show();
     }else{
      $('#price_item').attr('readonly', 'readonly');
      $('#total_item').attr('readonly', 'readonly');
      
      $('#details-fields-pane').hide();
      $('.quote-calc-panel').hide();
      $('#labor_item').val(0);
      $('#material_item').val(0);
      $('#sub_contractor_item').val(0)
     }
  });  
  
  add_item_watcher();

  $( "#btn-insert-line" ).click(function() {
    var calc=0;
    var counter       =$('#item-list').attr('data');
    var count         =$('#item-list').attr('data-count');
    var item_id       =$('.data-select').val();
    var qty           =$('#qty_item').val();
    var price         =$('#price_item').val();
    var tax           =$('#tax_item').val();
    var discount      =$('#discount_item').val();
    var labor         =$('#labor_item').val();
    var labor_hours   =$('#hours_labor_item').val();
    var material      =$('#material_item').val();
    var sub_contractor=$('#sub_contractor_item').val();
    var margin        =$('#margin_item').val();
    var days          =$('#days_item').val();

    var base_total=0;
    var calc_tax=0;
    var calc_discount=0;
    base_total=parseFloat(qty)*parseFloat(price);
    if(tax>0){
      calc_tax=(base_total*parseFloat(tax/100));  
    }
   if(discount>0){
      calc_discount=(base_total*parseFloat(discount/100));
   }   

   calc=base_total+calc_tax-calc_discount;

    /*calc=(parseFloat($('#qty_item').val())*parseFloat($('#price_item').val()))+parseFloat(tax)-parseFloat(discount);*/


    /*var total_item=parseFloat(calc).toFixed(2);  */
    var total_item=parseFloat($('#total_item').val()).toFixed(2);


    var description=$('#description_item').val();
    var minutes=$('#minutes_item').val();

    counter=parseInt(counter)+1;
    count=parseInt(count)+1;

    var minutes_total=$('#minutes').val();
    var minutes_item=parseFloat((minutes/1000)*qty);
    minutes_total=parseFloat(minutes_total)+minutes_item;

    var split_text=$('.data-select option:selected').text().split('-');
    var custom_item=0;
    var readonly_field='readonly="readonly"';
    var description_field='';
    var subitem_field='';
    if($('#custom_item:checked').val()==1){
      custom_item=1;
      readonly_field='';
      
    }
    /*Add Subitems*/
    description_field='_solo';
    subitem_field="<a class='btn btn-info btn-sm pull-left' onclick='sub_item("+counter+")'><span class='ti-plus'></span></a>";

    var insert_html="";
    var line1="<td class='td-large'><input type='hidden'  class='parent-item' name='parent_item[]' value='0'><input type='hidden' name='quote_item_id[]' vale='0'><input type='hidden' name='id_item[]' value='"+item_id+"'><input type='hidden' class='ln-minutes' name='minutes_list[]' value='"+minutes+"'><input type='hidden' class='ln-minutes-item' name='minutes_item_list[]' value='"+minutes_item+"'><input type='hidden' class='ln-days-item' name='days_item[]' value='"+days+"'><input type='hidden' class='ln-custom-item' name='custom_item[]' value='"+custom_item+"'>"+subitem_field+"<input  name='description_item[]' value='"+description+"' class='form-control description_item"+description_field+" pull-left'></td>";
    var line2="<td><input  name='qty_item[]' value='"+qty+"' class='form-control line-qty'></td>";
    var line3="<td><input  type='hidden' name='labor_item[]' value='"+labor+"'><input  type='hidden' class='line-labor-hours' name='labor_hours_item[]' value='"+labor_hours+"'><input  type='hidden' name='material_item[]' value='"+material+"'><input  type='hidden' name='sub_contractor_item[]' value='"+sub_contractor+"'><input  type='hidden' name='margin_item[]' value='"+margin+"'><input  name='price_item[]' value='"+price+"' class='form-control ln-price-item' "+readonly_field+"></td>";
    var line4="<td><input  name='tax_item[]' value='"+tax+"' class='form-control line-tax'></td>";
    var line5="<td><input  name='discount_item[]' value='"+discount+"' class='form-control line-discount'></td>";
    var line6="<td><input class='form-control ln-total-item' name='total_item[]' value='"+total_item+"' "+readonly_field+"></td>";
    var line7="<td class='td-small'><a class='btn btn-danger' onclick='remove_item("+counter+")'><span class='ti-close'></span></a></td>";

    insert_html="<tr data='"+counter+"' id='quote-line-"+counter+"'>"+line1+line2+line3+line4+line5+line6+line7+"</tr>";
    
    /*Insert html line to list*/
    if(count==1){
      $('#item-list').html(insert_html);
    }else{
      $('#item-list').append(insert_html);
    }

    $('#item-list').attr('data',counter);
    $('#item-list').attr('data-count',count);

    
    /*Calculate Hours Labor - INI*/    
  
    if($('#custom_item:checked').val()==1 && labor_hours!=""){
       var minutes_total=$('#minutes').val();
       var minutes_new=parseFloat(minutes_total)+(parseFloat(labor_hours)*60);
       $('#label-minutes').html(parseFloat(minutes_new/60).toFixed(2));
       $('#minutes').val(parseFloat(minutes_new).toFixed(2));
      
    }else{
      $('#label-minutes').html(parseFloat(minutes_total/60).toFixed(2));
      $('#minutes').val(minutes_total);
    }

    
    /*Calculate Hours Labor - END*/

    $('#quote-line-'+counter+' input.ln-total-item').on('change', function (e) {
        var id=$(this).parent().parent().attr('data');
        var qty=$('#quote-line-'+id+' .line-qty').val();
        var total=$('#quote-line-'+id+' .ln-total-item').val();;
        var price=parseFloat(total)/parseFloat(qty);        
        $('#quote-line-'+id+' .ln-price-item').val(parseFloat(price).toFixed(2));  
        calculate_item_list(id);
    });

    $('#quote-line-'+counter+' input').on('change', function (e) {        
        calculate_item_list($(this).parent().parent().attr('data'));
    });

  
    $('#itemModal').modal('hide');  

  }); 

  $('#item-list input').on('change', function (e) {        
        calculate_item_list($(this).parent().parent().attr('data'));
  });

  $('#type_category').on('change', function (e) {        
      /*$('#type_section option:eq(0)').prop('selected', true);*/
      $('#type_item').html("<option>Loading...</option>");
      var id_cat=$(this).val();
      $.ajax({
        url:  '/quote/get-list/1/'+id_cat,
        type: "get",
        success: function(dataResponse){
          $('#type_item').html(dataResponse.html);
          add_item_watcher();
        },
        error: function(data){
          
        },
      });
      
  });

  $('#type_section').on('change', function (e) {        
      $('#type_category option:eq(0)').prop('selected', true);
      var id_cat=$(this).val();
      $.ajax({
        url:  '/quote/get-list/2/'+id_cat,
        type: "get",
        success: function(dataResponse){
          $('#type_item').html(dataResponse.html);
          add_item_watcher();
        },
        error: function(data){
          
        },
      });
      
  });

});

function add_item_watcher(){
  $('.data-select').on('change', function (e) {        
      var value=$(this).val();
      if(value!=0){
        $('.item-detail-panel').slideDown();
        $('.item-detail-panel-select').hide();
        var price=$('#option-item-'+value).attr('data-price');
        var description=$('#option-item-'+value).attr('data-description');
        var minutes=$('#option-item-'+value).attr('data-minutes');
        $('#price_item').val(parseFloat(price).toFixed(2));
        $('#description_item').val(description);
        $('#minutes_item').val(minutes);
        calculate_item();      
      }else{
        $('.item-detail-panel').hide();
        $('.item-detail-panel-select').show();
      }
  });

  $('.input-watch').on('change', function (e) {        
      calculate_item();      
  });

  $('#price_item').on('change', function (e) {   
      if($('#custom_item:checked').val()==1){     
        calculate_item();      
      }
  });

  $('#total_item').on('change', function (e) {   
    if($('#custom_item:checked').val()==1){     
      var qty=$('#qty_item').val();
      var total=$('#total_item').val();
      var price=parseFloat(total)/parseFloat(qty);
      $('#price_item').val(parseFloat(price).toFixed(2));
      calculate_item();      
    }
  });  


  

  $('.input-watch-detail').on('change', function (e) {        
      calculate_item_detail();      
      calculate_item();
  });

  
}

function calculate_item(){
  var qty=$('#qty_item').val();
  var price=$('#price_item').val();
  var tax=$('#tax_item').val();
  var discount=$('#discount_item').val();
  /*if($('#custom_item:checked').val()!=1){*/
    var base_total=0;
    var calc_tax=0;
    var calc_discount=0;
    base_total=parseFloat(qty)*parseFloat(price);
    if(tax>0){
        calc_tax=(base_total*parseFloat(tax/100));  
      }
     if(discount>0){
        calc_discount=(base_total*parseFloat(discount/100));
     }  

   calc=base_total+calc_tax-calc_discount;
    
    
    $('#total_item').val(parseFloat(calc).toFixed(2));
  /*}*/
}

function calculate_item_detail(){
  var labor         =$('#labor_item').val();
  var labor_hours   =$('#hours_labor_item').val();
  var material      =$('#material_item').val();
  var margin        =$('#margin_item').val();
  var sub_contractor=$('#sub_contractor_item').val();


  if($('#custom_item:checked').val()==1){
    if(labor!="" || material!="" || sub_contractor!="" || margin!=""){
      if(labor=="") labor=0;
      if(material=="") material=0;
      if(sub_contractor=="") sub_contractor=0;
      if(margin=="") margin=0;

      var total_detail=0;
      var tmp_margin=0;
      total_detail=parseFloat(labor)+parseFloat(material)+parseFloat(sub_contractor);
      tmp_margin=(margin/100);
      tmp_margin=total_detail*tmp_margin;
      total_detail= total_detail+tmp_margin;
      $('#price_item').attr('readonly', 'readonly');
      $('#price_item').val(parseFloat(total_detail).toFixed(2));  
    }else{
      $('#price_item').removeAttr('readonly', 'readonly');
    }
    
    
    
  }
}

function calculate_item_list(id){

  var qty=$('#quote-line-'+id+' .line-qty').val();
  var price=$('#quote-line-'+id+' .ln-price-item').val();
  var tax=$('#quote-line-'+id+' .line-tax').val();
  var discount=$('#quote-line-'+id+' .line-discount').val();
  var minutes=$('#quote-line-'+id+' .ln-minutes').val();
  var minutes_item_before=$('#quote-line-'+id+' .ln-minutes-item').val();
  var custom_item=$('#quote-line-'+id+' .ln-custom-item').val(); 
  var labor_hours=$('#quote-line-'+id+' .line-labor-hours').val(); 

  if(custom_item==0 || labor_hours==""){
    var minutes_total=$('#minutes').val();
    var minutes_item=parseFloat((minutes/1000)*qty);
    minutes_total=parseFloat(minutes_total)+minutes_item-minutes_item_before;

    $('#minutes').val(parseFloat(minutes_total).toFixed(2));
    $('#label-minutes').html(parseFloat(minutes_total/60).toFixed(2));

    $('#quote-line-'+id+' .ln-minutes-item').val(parseFloat(minutes_item).toFixed(2));
  }


  var calc=0;
  var base_total=0;
  var calc_tax=0;
  var calc_discount=0;
  base_total=parseFloat(qty)*parseFloat(price);
  if(tax>0){
      calc_tax=(base_total*parseFloat(tax/100));  
    }
   if(discount>0){
      calc_discount=(base_total*parseFloat(discount/100));
   }

  calc=base_total+calc_tax-calc_discount;

  /*calc=(parseFloat(qty)*parseFloat(price))+parseFloat(tax)-parseFloat(discount);*/
  /*if(custom_item!=1){*/
    $('#quote-line-'+id+' .ln-total-item').val(parseFloat(calc).toFixed(2));  
  /*}*/

}

function remove_item(id){
  var parent_item=$('#quote-line-'+id+' .parent-item').val();
  var count=$('#item-list').attr('data-count');
  if(parent_item==0)
      count=parseInt(count)-1;

  var minutes=$('#quote-line-'+id+' .ln-minutes').val();
  var qty=$('#quote-line-'+id+' .line-qty').val();
  var custom_item=$('#quote-line-'+id+' .ln-custom-item').val(); 
  var labor_hours=$('#quote-line-'+id+' .line-labor-hours').val();

  var minutes_total=$('#minutes').val();

  if(custom_item==0 || labor_hours==""){
    minutes_total=parseFloat(minutes_total)-parseFloat((minutes/1000)*qty);

    $('#minutes').val(parseFloat(minutes_total).toFixed(2));
    $('#label-minutes').html(parseFloat(minutes_total/60).toFixed(2));
  }else{
    if(parent_item==0){
      minutes_total=parseFloat(minutes_total)-parseFloat((labor_hours)*60);    
      $('#minutes').val(parseFloat(minutes_total).toFixed(2));
      $('#label-minutes').html(parseFloat(minutes_total/60).toFixed(2));
    }
  }
    

  $('#item-list').attr('data-count',count);
  if(count==0){
    $('#item-list').html('<tr><td colspan="7"><h4 class="text-center">No items added</h4></td></tr>');
  }
  $('#quote-line-'+id).remove();
  $('.child-item-'+id).remove();
}

function remove_sub_item(id){
  $('#quote-line-'+id).remove();
}


function sub_item(id){
  /*Get quote items*/
  var leng=$(".child-item-"+id).length;

  var counter=id+'-'+(leng+1);
  var readonly_field="";

  var total_item="0";
  var discount="0";
  var tax="0";
  var labor="0";
  var labor_hours=$('#quote-line-'+id+' .line-labor-hours').val();
  var material="0";
  var days="0";
  var sub_contractor="0";
  var margin="0";
  var price="0";
  var qty="0";
  var item_id="0";
  var minutes=$('#quote-line-'+id+' .ln-minutes').val();  

  var minutes_item="0";
  
  var custom_item=$('#quote-line-'+id+' .ln-custom-item').val();
  if(custom_item==0){
    readonly_field='readonly="readonly"';    
    price=$('#quote-line-'+id+' .ln-price-item').val();
  }  

  var description=$('#quote-line-'+id+' .description_item_solo').val();
  var description_field="_solo";
  var subitem_field="<a class='btn btn-warning btn-sm pull-left'><span class='ti-arrow-circle-up'></span></a>";


  var insert_html="";
  var line1="<td class='td-large'><input type='hidden'  class='parent-item' name='parent_item[]' value='"+id+"'><input type='hidden' name='quote_item_id[]' vale='0'><input type='hidden' name='id_item[]' value='0'><input type='hidden' class='ln-minutes' name='minutes_list[]' value='"+minutes+"'><input type='hidden' class='ln-minutes-item' name='minutes_item_list[]' value='"+minutes_item+"'><input type='hidden' class='ln-days-item' name='days_item[]' value='"+minutes_item+"'><input type='hidden' class='ln-custom-item' name='custom_item[]' value='"+custom_item+"'>"+subitem_field+"<input  name='description_item[]' value='"+description+"' class='form-control description_item"+description_field+" pull-left'></td>";
  var line2="<td><input  name='qty_item[]' value='"+qty+"' class='form-control line-qty'></td>";
  var line3="<td><input  type='hidden' name='labor_item[]' value='"+labor+"'><input  type='hidden' class='line-labor-hours' name='labor_hours_item[]' value='"+labor_hours+"'><input  type='hidden' name='material_item[]' value='"+material+"'><input  type='hidden' name='sub_contractor_item[]' value='"+sub_contractor+"'><input  type='hidden' name='margin_item[]' value='"+margin+"'><input  name='price_item[]' value='"+price+"' class='form-control ln-price-item' "+readonly_field+"></td>";
  var line4="<td><input  name='tax_item[]' value='"+tax+"' class='form-control line-tax'></td>";
  var line5="<td><input  name='discount_item[]' value='"+discount+"' class='form-control line-discount'></td>";
  var line6="<td><input class='form-control ln-total-item' name='total_item[]' value='"+total_item+"' "+readonly_field+"></td>";
  var line7="<td class='td-small'><a class='btn btn-danger' onclick='remove_sub_item(\""+counter+"\")'><span class='ti-close'></span></a></td>";

  insert_html="<tr class='child-item-"+id+"' data='"+counter+"' id='quote-line-"+counter+"'>"+line1+line2+line3+line4+line5+line6+line7+"</tr>";

  $('#quote-line-'+id).after(insert_html);
  $('#quote-line-'+counter+' input.ln-total-item').on('change', function (e) {
        var id=$(this).parent().parent().attr('data');
        var qty=$('#quote-line-'+id+' .line-qty').val();
        var total=$('#quote-line-'+id+' .ln-total-item').val();;
        var price=parseFloat(total)/parseFloat(qty);        
        $('#quote-line-'+id+' .ln-price-item').val(parseFloat(price).toFixed(2));  
        calculate_item_list(id);
    });

    $('#quote-line-'+counter+' input').on('change', function (e) {        
        calculate_item_list($(this).parent().parent().attr('data'));
    });
}