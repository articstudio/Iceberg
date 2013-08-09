<?php
$id = get_request_id();
$submit = $id ? array('action'=>'update', 'id'=>$id) : array('action'=>'insert');
$obj = get_objtaxonomy($id);
?>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="form-objtaxonomy">
    <div class="well">
        <header><?php print_text('Object Taxonomy'); ?></header>
        
        <div class="row-fluid">
            <div class="span6">
                <p>
                    <label for="name"><?php print_text('Name'); ?>:</label>
                    <input name="name" id="name" class="input-block-level" value="<?php print_html_attr($obj->GetName()); ?>" required />
                </p>
            </div>
        </div>
        
        <?php action_event('structure_objtaxonomy_edit'); ?>
        
        <div class="form-actions text-right">
            <a href="<?php print get_admin_action_link(); ?>" class="btn btn-large btn-inverse"><?php print_text('Cancel'); ?> <i class="icon-remove-circle icon-white"></i></a>
            <button type="submit" class="btn btn-large btn-success"><?php print_text('Save'); ?> <i class="icon-ok-circle icon-white"></i></button>
        </div>
    </div>
</form>