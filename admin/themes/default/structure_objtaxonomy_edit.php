<?php
$id = get_request_id();
$submit = $id ? array('action'=>'update', 'id'=>$id) : array('action'=>'insert');
$obj = get_objtaxonomy($id);
$mode = get_mode('mode');
?>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="form-objtaxonomy" role="form" validate>
    <div class="well">
        <div class="row">
            <div class="col-md-6">
                <p class="control-group">
                    <label for="name" class="control-label"><?php print_text('Name'); ?>:</label>
                    <input name="name" id="name" class="form-control" value="<?php print_html_attr($obj->GetName()); ?>" required >
                </p>
            </div>
        </div>
        
        <?php
        do_action('structure_objtaxonomy_edit', $mode);
        do_action('structure_objtaxonomy_edit_' . $mode);
        ?>
        
        <div class="form-actions text-right">
            <a href="<?php print get_admin_action_link(); ?>" class="btn btn-large btn-default"><span class="glyphicon glyphicon-ban-circle"></span> <?php print_text('Cancel'); ?></a>
            <button type="submit" class="btn btn-large btn-success"><span class="glyphicon glyphicon-ok"></span> <?php print_text('Save'); ?></button>
        </div>
    </div>
</form>