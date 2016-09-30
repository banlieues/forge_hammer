<span style="display:none" id="loading_dao<?php echo $token_js;?><?php echo $key_js;?>" class="form-control-static loading_dao<?php echo $token_js;?>"><i class="fa fa-spinner fa-pulse" aria-hidden="true"></i></span>  

<div style="display:none" id="update_dao<?php echo $token_js;?><?php echo $key_js;?>" class="update_dao update_dao<?php echo $token_js;?><?php echo $key_js;?>" >
   <span style="display:none;" id="error_dao<?php echo $token_js;?><?php echo $key_js;?>"  class="text-danger error_dao_<?php echo $token_js;?> error_dao<?php echo $token_js;?><?php echo $key_js;?>">
       <i class="fa fa-exclamation-triangle"></i>
   </span>
   <span style="display:none;" id="label_error_dao<?php echo $token_js;?><?php echo $key_js;?>" class="text-danger error_dao_<?php echo $token_js;?> error_dao<?php echo $token_js;?><?php echo $key_js;?>">
       Error!
   </span>
    <form key_js_dao="<?php echo $key_js;?>" id="form_update_dao<?php echo $token_js;?><?php echo $key_js;?>"  class="form-horizontal form form_update_dao<?php echo $token_js;?><?php echo $key_js;?> form_update_dao<?php echo $token_js;?>">
    
	<?php echo $value; ?>
    
      <button id="submit_update_dao<?php echo $token_js;?><?php echo $key_js;?>" type="submit" class="btn btn-success btn-xs"><i class="fa fa-thumbs-up"></i></button>
      <button key_js_dao="<?php echo $key_js;?>" type="button" class="btn btn-danger btn-xs close_update_dao<?php echo $token_js;?>"><i class="fa fa-times"></i></button>
  
    
    </form>
</div>