<?php
$objs = select_all_config_objects();
?>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatable" id="list-objtaxonomy" data-new="<?php print get_admin_action_link(array('action'=>'new')); ?>">
    <thead>
        <tr>
            <th><?php print_text('ID'); ?></th>
            <th><?php print_text('Name'); ?></th>
            <th><?php print_text('Value'); ?></th>
            <th></th>
        </tr>
    </thead>
    <?php $n=0; foreach($objs AS $id => $obj): ?>
    <tr id="<?php print $id; ?>">
        <td>
            <?php print $id; ?>
        </td>
        <td>
            <?php print $obj->name; ?>
        </td>
        <td>
            <?php print cut_text($obj->value, 90, true, '...', true); ?>
        </td>
        <td class="text-right">
            <a href="<?php print get_admin_action_link(array('id'=>$obj->name, 'action'=>'edit')); ?>" class="btn btn-inverse"><i class="icon-pencil icon-white"></i></a>
            <a href="<?php print get_admin_action_link(array('id'=>$obj->name, 'action'=>'remove')); ?>" class="btn btn-danger" confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><i class="icon-trash"></i></a>
        </td>
    </tr>
    <?php $n++; endforeach; ?>
</table>