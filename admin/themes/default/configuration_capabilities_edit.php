<?php
$id = get_request_id();
$submit = $id ? array('action'=>'update', 'id'=>$id) : array('action'=>'insert');
$capability = get_user_capability($id);
?>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="configuration-capability" role="form" validate>
    <div class="well">
        <div class="row">
            
            <div class="col-md-6">
                <p class="form-group">
                    <label for="name" class="control-label"><?php print_text('Name'); ?>:</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php print_html_attr($capability->GetName()); ?>" required>
                </p>
            </div>
            
            <div class="col-md-6">
                <p class="form-group">
                    <label for="capability" class="control-label"><?php print_text('Capability'); ?>:</label>
                    <input type="text" name="capability" id="capability" class="form-control" value="<?php print_html_attr($capability->GetCapability()); ?>" required>
                </p>
            </div>
            
        </div>
        
        
        
        <div class="form-actions text-right">
            <a href="<?php print get_admin_action_link(); ?>" class="btn btn-large btn-default"><span class="glyphicon glyphicon-ban-circle"></span> <?php print_text('Cancel'); ?></a>
            <button type="submit" class="btn btn-large btn-success"><span class="glyphicon glyphicon-ok"></span> <?php print_text('Save'); ?></button>
        </div>
    </div>
</form>

<script type="text/template" id="tpl-capability-list">
    <li>
        <div class="well widget">
            <header>%data-name%</header>
            <div class="btn-toolbar header">
                <a href="#" class="btn btn-default btn-xs" btn-action="collapse"><span class="glyphicon glyphicon-chevron-up"></span></a>
                <a href="#" class="btn btn-default btn-xs" btn-action="expand"><span class="glyphicon glyphicon-chevron-down"></span></a>
                <a href="#" class="btn btn-danger btn-xs" btn-action="remove"><span class="glyphicon glyphicon-trash"></span></a>
            </div>
            <p class="form-group">
                <input type="text" class="form-control" name="capabilities[]" value="%data-name%" widget-action="title" placeholder="<?php print_text('Name'); ?>" required>
            </p>
        </div>
    </li>
</script>
