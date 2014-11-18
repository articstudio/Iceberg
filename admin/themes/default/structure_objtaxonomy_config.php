<?php
$id = get_request_id();
$submit = $id ? array('action'=>'save', 'id'=>$id) : array('action'=>'list');
$obj = get_objtaxonomy($id);
$mode = get_mode('mode');
?>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="form-objtaxonomy">
    <div class="well">
        <?php /*<h4><?php print_text('Name'); ?>: <strong><?php print_html_attr($obj->GetName()); ?></strong></h4>*/ ?>
        
        <?php
        do_action('structure_objtaxonomy_config', $mode);
        do_action('structure_objtaxonomy_config_' . $mode);
        ?>
        
        <div class="form-actions text-right">
            <a href="<?php print get_admin_action_link(); ?>" class="btn btn-large btn-default"><span class="glyphicon glyphicon-ban-circle"></span> <?php print_text('Cancel'); ?></a>
            <button type="submit" class="btn btn-large btn-success"><span class="glyphicon glyphicon-ok"></span> <?php print_text('Save'); ?></button>
        </div>
    </div>
</form>