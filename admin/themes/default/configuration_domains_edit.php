<?php
$id = get_request_id();
$submit = $id ? array('action'=>'update', 'id'=>$id) : array('action'=>'insert');

$canonical = get_domain();
$canonical_childs = get_domains_by_parent($canonical->id);
?>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="languages-edit">
    <div class="well">
        <header><?php print_text('Data domain'); ?></header>
        
        <div class="row-fluid">
            
            <div class="span6">
                <p>
                    <label for="canonical_name"><?php print_text('Name'); ?>:</label>
                    <input name="canonical_name" id="canonical_name" class="input-block-level" value="<?php print_html_attr($canonical->name); ?>" required />
                </p>
            </div>
            
        </div>
        
        
        <div class="row-fluid" data-push="alias" data-push-template="tpl-domain-list">
            <div class="span6">
                <div class="well">
                    <header><?php print_text('New alias'); ?></header>
                    <input type="text" name="newalias" id="newalias" data-push-value="title" class="input-block-level" placeholder="<?php print_text('Domain'); ?>" />

                    <div class="form-actions form-actions-mini">
                        <button title="<?php print_html_attr( _T('ADD') ); ?>" class=" btn-large btn-success btn-mini"><i class="icon-plus-sign icon-white"></i> <?php print_text( 'ADD' ); ?></button>
                    </div>
                </div>
            </div>
            
            <div class="span6">
                <div class="well">
                    <header><?php print_text('Alias'); ?></header>
                    <ul class="unstyled" id="alias" data-sortable="revert,droppable">
                        <?php foreach ($canonical_childs AS $domain_id => $domain): ?>
                        <li>
                            <div class="well widget collapsed">
                                <header><?php print $domain->name; ?></header>
                                <div class="btn-toolbar header">
                                    <a href="#" class="btn btn-inverse btn-mini" btn-action="collapse"><i class="icon-chevron-up icon-white"></i></a>
                                    <a href="#" class="btn btn-inverse btn-mini" btn-action="expand"><i class="icon-chevron-down icon-white"></i></a>
                                    <a href="#" class="btn btn-danger btn-mini" btn-action="remove"><i class="icon-trash"></i></a>
                                </div>
                                <input type="hidden" name="alias_id[]" value="<?php print_html_attr($domain_id); ?>">
                                <p>
                                    <input type="text" class="input-block-level" name="alias_name[]" value="<?php print_html_attr($domain->name); ?>" widget-action="title" placeholder="<?php print_text('Name'); ?>" required>
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
            <a href="<?php print get_admin_action_link(); ?>" class="btn btn-large btn-inverse"><?php print_text('Cancel'); ?> <i class="icon-remove-circle icon-white"></i></a>
            <button type="submit" class="btn btn-large btn-success"><?php print_text('Save'); ?> <i class="icon-ok-circle icon-white"></i></button>
        </div>
    </div>
</form>

<script type="text/template" id="tpl-domain-list">
    <li>
        <div class="well widget">
            <header>%data-title%</header>
            <div class="btn-toolbar header">
                <a href="#" class="btn btn-inverse btn-mini" btn-action="collapse"><i class="icon-chevron-up icon-white"></i></a>
                <a href="#" class="btn btn-inverse btn-mini" btn-action="expand"><i class="icon-chevron-down icon-white"></i></a>
                <a href="#" class="btn btn-danger btn-mini" btn-action="remove"><i class="icon-trash"></i></a>
            </div>
            <input type="hidden" name="alias_id[]" value="-1">
            <p>
                <input type="text" class="input-block-level" name="alias_name[]" value="%data-title%" widget-action="title" placeholder="<?php print_text('Name'); ?>" required>
            </p>
        </div>
    </li>
</script>