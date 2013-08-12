<?php
$id = get_request_id();
$submit = $id ? array('action'=>'save', 'id'=>$id) : array('action'=>'list');
$obj = get_objtaxonomy($id);
?>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="form-objtaxonomy">
    <div class="well">
        <header><?php print_text('Object Taxonomy'); ?></header>
        
        <p><?php print_text('Name'); ?>: <strong><?php print_html_attr($obj->GetName()); ?></strong></p>
        
        <?php action_event('structure_objtaxonomy_config'); ?>
        
        <div class="form-actions text-right">
            <a href="<?php print get_admin_action_link(); ?>" class="btn btn-large btn-inverse"><?php print_text('Cancel'); ?> <i class="icon-remove-circle icon-white"></i></a>
            <button type="submit" class="btn btn-large btn-success"><?php print_text('Save'); ?> <i class="icon-ok-circle icon-white"></i></button>
        </div>
    </div>
</form>