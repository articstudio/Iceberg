<?php
$id = get_request_id();
$config = select_config_object($id);
$is_new = !(bool)$id || $config->id==-1;
$submit = $is_new ? array('action'=>'insert') : array('action'=>'update', 'id'=>$id);

?>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="configuration-settings" validate>
    <div class="well">
        <header><?php print_text('Details'); ?></header>
        
        <div class="row-fluid">
            
            <div class="span6">
                <p>
                    <label for="config_name"><?php print_text('Name'); ?>:</label>
                    <input type="text" name="config_name" id="config_name" class="input-block-level" value="<?php print_html_attr($config->name); ?>" <?php print $is_new ? 'required' : 'disabled'; ?> />
                </p>
            </div>
            
        </div>
        
        <p>
            <label for="config_value"><?php print_text('Value'); ?>:</label>
            <textarea class="input-block-level" id="config_value" name="config_value" rows="10" cols="10"><?php print $config->value; ?></textarea>
        </p>
        
        <div class="form-actions text-right">
            <a href="<?php print get_admin_action_link(); ?>" class="btn btn-large btn-inverse"><?php print_text('Cancel'); ?> <i class="icon-remove-circle icon-white"></i></a>
            <button type="submit" class="btn btn-large btn-success"><?php print_text('Save'); ?> <i class="icon-ok-circle icon-white"></i></button>
        </div>
    </div>
</form>