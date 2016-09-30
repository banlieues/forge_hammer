<select
    name="value"   
    type="text" id="input_dao<?php echo $token_js; ?><?php echo $key_js;?>" 
    class="form-control input_dao<?php echo $token_js; ?><?php echo $key_js;?>"   
    placeholder="<?php echo $label;?>"  
>
    <option value="0">Choisir</option>
<?php foreach($list_select as $list): ?>
    <option <?php if($list->value==$value): echo "selected"; endif;?> value="<?php echo $list->value;?>"><?php echo $list->label;?></option>
    
<?php endforeach; ?>    
</select>    
<input type="hidden" value="<?php echo $value;?>" id="copy_input_dao<?php echo $token_js; ?><?php echo $key_js;?>">
<input type="hidden" value="<?php echo $key_id_field ?>" name="key_id_field"> 
<input type="hidden" value="<?php echo $index ?>" name="index"> 