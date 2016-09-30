<script type="text/javascript">
    
    function hide_lecture_dao(key_js_dao)
    {
	$("#loading_dao<?php echo $token_js;?>").hide();
	$(".bt_lecture<?php echo $token_js;?>").hide();
	$(".value_lecture_dao<?php echo $token_js;?>").css("opacity", 0.4);
	$("#lecture_field<?php echo $token_js;?>"+key_js_dao).hide();
	$("#update_dao<?php echo $token_js;?>"+key_js_dao).show();
	$(".error_dao<?php echo $token_js ?>"+key_js_dao).hide();
	
    }
    
    function show_lecture_dao(key_js_dao)
    {	
	$("#loading_dao<?php echo $token_js;?>"+key_js_dao).hide();
	$(".bt_lecture<?php echo $token_js;?>").show();
	$(".value_lecture_dao<?php echo $token_js;?>").css("opacity", 1);
	$("#lecture_field<?php echo $token_js;?>"+key_js_dao).show();
	$("#update_dao<?php echo $token_js;?>"+key_js_dao).hide();
	$("#input_dao<?php echo $token_js;?>"+key_js_dao).val($("#copy_input_dao<?php echo $token_js;?>"+key_js_dao).val());
	$(".error_dao<?php echo $token_js ?>"+key_js_dao).hide();
    }
    
    
    jQuery(document).ready(function()
    {
	
	$(document).off("click",".click_lecture<?php echo $token_js;?>").on("click",".click_lecture<?php echo $token_js;?>", function(e) 
	{
		var key_js_dao=$(this).attr("key_js_dao");
		hide_lecture_dao(key_js_dao);
	  
	});
	
	$(document).off("click",".close_update_dao<?php echo $token_js;?>").on("click",".close_update_dao<?php echo $token_js;?>", function(e) 
	{
		var key_js_dao=$(this).attr("key_js_dao");
		show_lecture_dao(key_js_dao);

	});
	
	
	$(document).off("submit",".form_update_dao<?php echo $token_js;?>").on("submit",".form_update_dao<?php echo $token_js;?>", function(e) 
	{
	   
		var key_js_dao=$(this).attr("key_js_dao");
		$(".error_dao<?php echo $token_js ?>"+key_js_dao).hide();
		
		if($("#copy_input_dao<?php echo $token_js;?>"+key_js_dao).val()==$("#input_dao<?php echo $token_js;?>"+key_js_dao).val())
		{
		     show_lecture_dao(key_js_dao);
		}
		else
		{
		    var dataString=$(this).serialize();
		 
		    $("#update_dao<?php echo $token_js;?>"+key_js_dao).hide();
		    $("#loading_dao<?php echo $token_js;?>"+key_js_dao).show();
		    var adresse="<?php echo base_url()?>fh/fhc_dao/get_update";
		    jQuery.ajax
		    ({	
			    type:'POST',
			    url: adresse,
			    data: dataString,
			    dataType: 'json',
			    cache: false,

			    success: function(data)
			    { 
				//alert(data);
			       if(data.error==0)
			       {
				  
				    $("#copy_input_dao<?php echo $token_js;?>"+key_js_dao).val(data.new_value);
				    $("#value_lecture_dao<?php echo $token_js;?>"+key_js_dao).html(data.label_value);
				    show_lecture_dao(key_js_dao);
			       }
			       
			      else
			       {
				    $("#loading_dao<?php echo $token_js;?>"+key_js_dao).hide();
				    $("#update_dao<?php echo $token_js;?>"+key_js_dao).show();
				    $("#label_error_dao<?php echo $token_js ?>"+key_js_dao).html(data.msg_error).show();
				    $("#error_dao<?php echo $token_js ?>"+key_js_dao).show();
				
			       }
			    }
		    });
		
		}
		
		return false;
	});
	
        
    });
</script>

