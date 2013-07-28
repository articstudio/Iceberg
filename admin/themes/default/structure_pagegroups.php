<?php
$pagegroups = get_pagegroups();
?>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatable" id="languages-configuration-list" data-order="<?php print get_admin_api_action_link(array('action'=>'order')); ?>" data-new="<?php print get_admin_action_link(array('action'=>'new')); ?>">
    <thead>
        <tr>
            <th><?php print_text('Order'); ?></th>
            <th><?php print_text('Name'); ?></th>
            <th></th>
        </tr>
    </thead>
    <?php $n=0; foreach($pagegroups AS $id => $pagegroup): ?>
    <tr data-position="<?php print $n; ?>" id="<?php print $id; ?>">
        <td>
            <?php print $n; ?>
        </td>
        <td>
            <?php print $pagegroup->GetName(); ?>
        </td>
        <td class="text-right">
            <a href="<?php print get_admin_action_link(array('id'=>$id, 'action'=>'edit')); ?>" class="btn btn-inverse"><i class="icon-pencil icon-white"></i></a>
            <a href="<?php print get_admin_action_link(array('id'=>$id, 'action'=>'remove')); ?>" class="btn btn-danger" confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><i class="icon-trash"></i></a>
        </td>
    </tr>
    <?php $n++; endforeach; ?>
</table>