<?php
$id = get_request_id();
$allconfig = (bool)get_request_gp('allconfig');
$language = get_language_info();
$config = select_config_object_by_id($id);
$is_new = !(bool)$id || $config->id==-1;
$submit = $is_new ? array('action'=>'insert') : array('action'=>'update', 'id'=>$id);

?>

<p class="alert alert-warning">
    <?php if ($allconfig): ?>
    <span class="glyphicon glyphicon-globe"></span> <strong><?php print_text('Global configuration'); ?></strong>
    <?php else: ?>
    <img src="<?php print get_base_url() . $language['flag']; ?>" alt="<?php print_html_attr($language['name']); ?>" class="flag"> <strong><?php print_text('Localized configuration'); ?></strong> (<?php print $language['name']; ?>)
    <?php endif; ?>
</p>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="configuration-detals" role="form" validate>
    <input type="hidden" name="allconfig" value="<?php echo $allconfig ? 1 : 0; ?>">
    <div class="well">
        <div class="row">
            
            <div class="col-md-6">
                <p class="form-group">
                    <label for="config_name" class="control-label"><?php print_text('Name'); ?>:</label>
                    <input type="text" name="config_name" id="config_name" class="form-control" value="<?php print_html_attr($config->name); ?>" <?php print $is_new ? 'required' : 'disabled'; ?> />
                </p>
            </div>
            
            <div class="col-md-6"></div>
            
        </div>
        
        <p class="form-group">
            <label for="config_value" class="control-label"><?php print_text('Value'); ?>:</label>
            <textarea class="form-control" id="config_value" name="config_value" rows="10" cols="10"><?php print $config->value; ?></textarea>
        </p>
        
        <div class="form-actions text-right">
            <a href="<?php print get_admin_action_link(array('action'=>'list')); ?>" class="btn btn-large btn-default"><span class="glyphicon glyphicon-ban-circle"></span> <?php print_text('Cancel'); ?></a>
            <button type="submit" class="btn btn-large btn-success"><span class="glyphicon glyphicon-ok"></span> <?php print_text('Save'); ?></button>
        </div>
    </div>
</form>