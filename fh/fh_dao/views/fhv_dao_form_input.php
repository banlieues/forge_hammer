<input 
    name="value"  
    type="text" id="input_dao<?php echo $token_js; ?><?php echo $key_js;?>" 
    class="form-control input_dao<?php echo $token_js; ?><?php echo $key_js;?>" 
    value="<?php echo $value;?>"  
    placeholder="<?php echo $label;?>"  
>
<input type="hidden" value="<?php echo $value;?>" id="copy_input_dao<?php echo $token_js; ?><?php echo $key_js;?>">
<input type="hidden" value="<?php echo $key_id_field ?>" name="key_id_field"> 
<input type="hidden" value="<?php echo $index ?>" name="index"> 