<?php
$mode = get_mode('mode');

$key = false;
if ($mode === 'pagegroups')
{
    $key = PageGroup::$TAXONOMY_KEY;
}
else if ($mode === 'pagetypes')
{
    $key = PageType::$TAXONOMY_KEY;
}
else if ($mode === 'pagetaxonomies')
{
    $key = PageTaxonomy::$TAXONOMY_KEY;
}
$objs = get_objtaxonomy_list($key);
?>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatable" id="list-objtaxonomy" data-order="<?php print get_admin_api_action_link(array('action'=>'order')); ?>" data-new="<?php print get_admin_action_link(array('action'=>'new')); ?>">
    <thead>
        <tr>
            <th><?php print_text('Order'); ?></th>
            <th><?php print_text('Name'); ?></th>
            <th></th>
        </tr>
    </thead>
    <?php $n=0; foreach($objs AS $id => $obj): ?>
    <tr data-position="<?php print $n; ?>" id="<?php print $id; ?>">
        <td>
            <?php print $n; ?>
        </td>
        <td>
            <?php print $obj->GetName(); ?>
        </td>
        <td class="text-right">
            
            <?php if ($mode === 'pagetaxonomies'): ?>
            <a href="<?php print get_admin_action_link(array('id'=>$id, 'action'=>'config')); ?>" class="btn btn-inverse"><i class="icon-wrench icon-white"></i></a>
            <?php endif; ?>
            
            <a href="<?php print get_admin_action_link(array('id'=>$id, 'action'=>'edit')); ?>" class="btn btn-inverse"><i class="icon-pencil icon-white"></i></a>
            <?php if ($obj->IsLocked()): ?>
            <button class="btn btn-danger disabled"><i class="icon-trash icon-white"></i></button>
            <?php else: ?>
            <a href="<?php print get_admin_action_link(array('id'=>$id, 'action'=>'remove')); ?>" class="btn btn-danger" confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><i class="icon-trash"></i></a>
            <?php endif; ?>
        </td>
    </tr>
    <?php $n++; endforeach; ?>
</table>