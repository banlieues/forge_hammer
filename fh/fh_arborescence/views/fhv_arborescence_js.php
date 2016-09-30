<script type="text/javascript">
    
 var is_open=0; 
 var position=0;
    
jQuery(document).ready(function()
{       
    $(document).on('click', ".collapsed",function () {
	//alert();
	var fh_loading=$(this).attr("fh-loading");
	var fh_niveau=$(this).attr("fh-niveau");
	var fh_position=$(this).attr("fh-position");
	var fh_is_open=$(this).attr("fh-is-open");
	
	
	
	
	position=position+1;
	var id=$(this).attr("fh-id");
	
	if(fh_is_open==0)
	{
	    $(".panel-rubrique"+fh_position).hide();
	    $(".fh_arbo_c_ajouter_bt"+fh_position).hide();
	    $(".fh_arbo_c_form_ajouter"+fh_position).hide();
	    $(".icon_position"+fh_position).show();
	    $(".icon_hiar"+fh_position).hide();
	    fh_is_open=$(this).attr("fh-is-open","1");
	}
	
	if(fh_is_open==1)
	{
	    $(".panel-rubrique"+fh_position).show();
	    $(".fh_arbo_c_ajouter_bt"+fh_position).show();
	    $(".fh_arbo_c_form_ajouter"+fh_position).hide();
	    $(".icon_position"+fh_position).hide();
	    $(".icon_hiar"+fh_position).show();
	    fh_is_open=$(this).attr("fh-is-open","0");
	}
	
	
	$("#panel-rubrique"+id).show();
	
	
	$(".panel-heading"+fh_niveau).attr("style","");
	$(".collapse"+fh_niveau).collapse('hide');
	
	//$(".panel-heading"+id).attr("style","background-color: #07C; color:white; border-color: #07C !important");
	//$(".panel-body"+id).attr("style","background-color: #31b0d5; color:white; border-color: #07C !important");

	var adresse="<?php echo base_url();?>fh/Fhc_arborescence/get_arborescence/"+id+"/<?php echo $nom_entity;?>/"+position;
		//alert(adresse);
		jQuery.ajax
		    ({	
			    type:'POST',
			    url: adresse,
			    cache: false,
			    success: function(html)
			    { 
			      $("#collapseloading"+fh_loading).html(html);
			      $("#collapse"+fh_loading).collapse('toggle');
			    }
		    }); 
	
    });
    
    
     $(document).on('click', ".fh_arbo_ajouter",function () {
	
	$(".arbo_container_button").show();
	$(".arbo_message_loading").hide();
	$(".arbo_message_error").hide();
	
	//$(".fh_arbo_c_form_ajouter").hide();
	//$(".fh_arbo_c_ajouter_bt").show();
	$(".fh_arbo_input_ajout").val("");
	var id_en_cours=$(this).attr("fh_id");
	//alert(id_en_cours);
	$("#bt_form_arbo_"+id_en_cours).hide();
	$("#form_arbo_"+id_en_cours).show();
	return false;
    });
    
    
     $(document).on('click', ".fh_arbo_annuler",function () {
	var id_en_cours=$(this).attr("fh_id");
	//alert(id_en_cours);
	$("#bt_form_arbo_"+id_en_cours).show();
	$("#form_arbo_"+id_en_cours).hide();
	$(".arbo_container_button"+id_en_cours).show();
	$(".arbo_message_loading"+id_en_cours).hide();
	$(".arbo_message_error"+id_en_cours).hide();
	return false;
    });
    
      $(document).on('submit', ".fh_arbo_submit",function () {
	$(".arbo_message_error").hide();   
	var dataString=$(this).serialize();
	var adresse="<?php echo base_url();?>fh/Fhc_arborescence/insert_arborescence/";
	var id_en_cours=$(this).attr("fh-id-en-cours");
	//var position=$(this).attr("fh-position");
	//alert(position);
	
	$(".arbo_container_button"+id_en_cours).hide();
	$(".arbo_message_loading"+id_en_cours).show();
	
	jQuery.ajax
	({	
		type:'POST',
		url: adresse,
		cache: false,
		data: dataString,
		dataType: 'json',
		success: function(data)
		{ 
		  //alert(data);
			if(data.is_error==1)
			{
			    $(".arbo_container_button"+id_en_cours).show();
			    $(".arbo_message_loading"+id_en_cours).hide();
			    $(".arbo_message_error"+id_en_cours).show();
			}
			else
			{
			    //alert(data.id_insert);
			    if(data.id_insert>0)
			    {
				var adresse="<?php echo base_url();?>fh/Fhc_arborescence/get_arborescence/"+id_en_cours+"/<?php echo $nom_entity;?>/"+position+"/"+data.id_insert+"/1";
				jQuery.ajax
				({	
					type:'POST',
					url: adresse,
					cache: false,
					success: function(html)
					{ 
					    $("#accordion"+id_en_cours).append(html);
					     $("#bt_form_arbo_"+id_en_cours).show();
					    $("#form_arbo_"+id_en_cours).hide();
					    $(".arbo_container_button"+id_en_cours).show();
					    $(".arbo_message_loading"+id_en_cours).hide();
					    $(".arbo_message_error"+id_en_cours).hide();
					    
					    
					}
				}); 
				
				
			    }
			    else
			    {
				alert("Erreur ! Impossible d'insérer dans la base de données!");
				 $(".arbo_container_button"+id_en_cours).show();
				 $(".arbo_message_loading"+id_en_cours).hide();
				 $(".arbo_message_error"+id_en_cours).hide();
			    }
			}
		}
	}); 
	return false;
    });
 
     $(document).on('input','.fh_arbo_input_ajout',function () {
	 
	 $(".arbo_message_error").hide(); 

     });
    
    

});
</script>