<?php
$id = get_request_id();
$submit = $id ? array('action'=>'update', 'id'=>$id) : array('action'=>'insert');

$canonical = get_domain();
$canonical_childs = get_domains_by_parent($canonical->id);
?>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="domains-edit" role="form" validate>
    <div class="well">
        <div class="row">
            
            <div class="col-md-6">
                <p class="form-group">
                    <label for="canonical_name" class="control-label"><?php print_text('Domain'); ?></label>
                    <input name="canonical_name" id="canonical_name" class="form-control" value="<?php print_html_attr($canonical->name); ?>" required>
                </p>
            </div>
            
        </div>
        
        
        <div class="row" data-push="alias" data-push-template="tpl-domain-list">
            <div class="col-md-6">
                <p class="form-group">
                    <label for="newalias" class="control-label"><?php print_text('New alias'); ?></label>
                    <input type="text" name="newalias" id="newalias" data-push-value="title" data-push-required="1" class="form-control" placeholder="<?php print_text('Alias'); ?>">
                </p>
                <p class="form-group">
                    <button title="<?php print_html_attr( _T('ADD') ); ?>" class="btn btn-success btn-sm btn-add"><span class="glyphicon glyphicon-plus"></span> <?php print_text( 'ADD' ); ?></button>
                </p>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label"><?php print_text('Alias'); ?></label>
                    <ul class="list-unstyled" id="alias" data-sortable="revert,droppable">
                        <?php foreach ($canonical_childs AS $domain_id => $domain): ?>
                        <li>
                            <div class="well widget collapsed">
                                <header><?php print $domain->name; ?></header>
                                <div class="btn-toolbar header">
                                    <a href="#" class="btn btn-default btn-xs" btn-action="collapse"><span class="glyphicon glyphicon-chevron-up"></span></a>
                                    <a href="#" class="btn btn-default btn-xs" btn-action="expand"><span class="glyphicon glyphicon-chevron-down"></span></a>
                                    <a href="#" class="btn btn-danger btn-xs" btn-action="remove"><span class="glyphicon glyphicon-trash"></span></a>
                                </div>
                                <input type="hidden" name="alias_id[]" value="<?php print_html_attr($domain_id); ?>">
                                <p class="form-group">
                                    <input type="text" class="form-control" name="alias_name[]" value="<?php print_html_attr($domain->name); ?>" widget-action="title" placeholder="<?php print_text('Name'); ?>" required>
                                </p>
                                <p>
                                    ID: <?php print $domain_id; ?>
                                </p>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        
        
        <div class="form-actions text-right">
            <a href="<?php print get_admin_action_link(array('action'=>'list')); ?>" class="btn btn-large btn-default"><span class="glyphicon glyphicon-ban-circle"></span> <?php print_text('Cancel'); ?></a>
            <button type="submit" class="btn btn-large btn-success"><span class="glyphicon glyphicon-ok"></span> <?php print_text('Save'); ?></button>
        </div>
    </div>
</form>

<script type="text/template" id="tpl-domain-list">
    <li>
        <div class="well widget">
            <header>%data-title%</header>
            <div class="btn-toolbar header">
                <a href="#" class="btn btn-default btn-xs" btn-action="collapse"><span class="glyphicon glyphicon-chevron-up"></span></a>
                <a href="#" class="btn btn-default btn-xs" btn-action="expand"><span class="glyphicon glyphicon-chevron-down"></span></a>
                <a href="#" class="btn btn-danger btn-xs" btn-action="remove"><span class="glyphicon glyphicon-trash"></span></a>
            </div>
            <input type="hidden" name="alias_id[]" value="-1">
            <p class="form-group">
                <input type="text" class="form-control" name="alias_name[]" value="%data-title%" widget-action="title" placeholder="<?php print_text('Name'); ?>" required>
            </p>
        </div>
    </li>
</script>