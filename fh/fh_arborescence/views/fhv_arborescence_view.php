<?php if($is_append==0): ?>
<div class="panel-group" id="accordion<?php echo $id_en_cours;?>" role="tablist" aria-multiselectable="true">
 <?php endif; ?>
 <?php if(count($enfants)>0): ?>  
  <?php foreach($enfants as $enfant): ?>  
  <div id="panel-rubrique<?php echo $enfant->id_enfant;?>" class="panel panel-default panel-rubrique panel-rubrique<?php echo $position;?>">
    <div  class="panel-heading panel-heading<?php echo $enfant->id_enfant; ?> panel-headingniv<?php echo $id_en_cours; ?>" role="tab" id="heading<?php echo $enfant->id_enfant; ?>niv<?php echo $id_en_cours;?>">
      <h4 class="panel-title">
        <a class="collapsed" role="button" fh-niveau="niv<?php echo $id_en_cours;?>"  fh-is-open="0" fh-position="<?php echo $position;?>" fh-id="<?php echo $enfant->id_enfant; ?>" fh-loading="<?php echo $enfant->id_enfant; ?>niv<?php echo $id_en_cours;?>">
	   
	    <span style="display:none" class="text-danger  icon_position<?php echo $position;?>"> <i class="fa fa-reply"></i></span> 

	   <span class="icon_hiar<?php echo $position;?>">
		<?php if($position>0): ?>
	       <i class="fa fa-arrow-right"></i>
		   
		<?php else: ?>
			 <i class="fa fa-tree"></i>
		<?php endif; ?>
	   </span>
	    
	     <?php echo $enfant->nom_enfant; ?> <?php //echo $enfant->id_enfant; ?> 

        </a>
	  <small> <i class="fa fa-stack-overflow" aria-hidden="true"></i> <a href="#">  Déplacer</a></small>
	  <!-- <a class="btn">Toto</a> -->
      </h4>
    </div>
    <div id="collapse<?php echo $enfant->id_enfant; ?>niv<?php echo $id_en_cours;?>" class="panel-collapse collapse collapseniv<?php echo $id_en_cours;?>" role="tabpanel">
      <div class="panel-body panel-body<?php echo $enfant->id_enfant; ?> ">
	  <div id="collapseloading<?php echo $enfant->id_enfant; ?>niv<?php echo $id_en_cours;?>" class="collapseloading">
	      
	  </div>   
      </div>
    </div>
     
  </div>
  <?php endforeach; ?>
  <?php endif; ?> 
    
<?php if($is_append==0): ?>    
</div>
<?php endif; ?>

<?php if($is_append==0): ?> 
<div style="display:none" id="form_arbo_<?php echo $id_en_cours;?>" class="panel panel-default fh_arbo_c_form_ajouter fh_arbo_c_form_ajouter<?php echo $position;?>">
    <div class="panel-body">
	
	<form fh-arbo-position="<?php echo $position;?>" fh-id-en-cours="<?php echo $id_en_cours;?>" class="form-inline fh_arbo_submit">
	  <div class="form-group">
 
	     <input fh-id-en-cours="<?php echo $id_en_cours;?>" style='width: 300px' name="nom_arbo" type="text" class="form-control fh_arbo_input_ajout" id="exampleInputName2" placeholder="Créer <?php echo $nom_entity;?>">
	     <input type="hidden" name="id_parent" value="<?php echo $id_en_cours;?>">
	  </div>
	    <span class="container_button arbo_container_button arbo_container_button<?php echo $id_en_cours;?>">
		<button type="submit" class="btn btn-success"><i class="fa fa-thumbs-up"></i></button> 
		<button fh_id="<?php echo $id_en_cours; ?>" class="btn btn-danger fh_arbo_annuler"><i class="fa fa-times"></i></button>
		sera ajouté dans <b> <?php echo $nom_parent; ?></b>
	    </span>
	    <span style='display:none' class=" arbo_message_loading arbo_message_loading<?php echo $id_en_cours;?>">
		<i class="fa fa-refresh fa-spin fa-2x fa-fw" aria-hidden="true"></i> en cours d'ajout dans <b> <?php echo $nom_parent; ?></b>
	    </span>
	    <span style='display:none' class=" text-danger arbo_message_error arbo_message_error<?php echo $id_en_cours;?>">
		<br> <b> Erreur ! Ce champ ne peut être vide !</b>
	     </span>
	</form>
    </div>
</div>

<div id="bt_form_arbo_<?php echo $id_en_cours;?>" class="panel panel-default fh_arbo_c_ajouter_bt fh_arbo_c_ajouter_bt<?php echo $position;?>"> 
    <div class="panel-body">
	<button  fh_id="<?php echo $id_en_cours; ?>" class="btn btn-info fh_arbo_ajouter fh_arbo_ajouter<?php echo $id_en_cours;?>">
	    <i class="fa fa-plus-circle"></i> Ajouter dans <b><?php echo $nom_parent; ?></b>
	</button>
    </div>
</div>
 <?php endif; ?>    







