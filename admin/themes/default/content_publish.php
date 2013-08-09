<?php
$pagegroups = get_pagegroups();
if (empty($pagegroups))
{
    //ERROR
}
$pagegroup_id = get_request_group();
$pagegroup = _T('Pages');
if ($pagegroup_id === null)
{
    
    $pagegroup = current($pagegroups);
    $pagegroup_id = $pagegroup->GetID();
    $pagegroup = $pagegroup->GetName();
}
else
{
    $pagegroup_id = (int)$pagegroup_id;
    $pagegroup = isset($pagegroups[$pagegroup_id]) ? $pagegroups[$pagegroup_id]->GetName() : '';
}

$pages = get_pages(array(
    'group' => $pagegroup_id
));
?>

<div class="DTTT btn-group">
    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="icon-list-alt"></i> <?php print $pagegroup; ?>
        <b class="caret"></b>
    </a>
    <ul class="dropdown-menu" role="menu">
        <?php foreach ($pagegroups AS $pg): ?>
        <li>
            <a href="<?php print get_admin_action_link(array('group'=>$pg->GetID())); ?>">
                <?php print $pg->GetName(); ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="DTTT btn-group">
    <a href="<?php print get_admin_action_link(array('group'=>$pagegroup_id, 'action'=>'new')); ?>" class="btn">
        <i class="icon-plus"></i> <?php print_text('New'); ?>
    </a>
</div>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered treetable data-expandable data-selectable" data-order="<?php print get_admin_action_link(array('group'=>$pagegroup_id, 'action'=>'order')); ?>">
    <thead>
        <tr>
            <td><?php print_text('Name'); ?></td>
            <td><?php print_text('Type'); ?></td>
            <td><?php print_text('Taxonomy'); ?></td>
            <td></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pages AS $k => $page): ?>
        <tr data-tt-id="<?php print $page->id; ?>" <?php if (!is_null($page->parent)): ?>data-tt-parent-id="<?php print $page->parent; ?>"<?php endif; ?>>
            <td><span class="file"><?php print $page->GetTitle(); ?></span></td>
            <td><?php print $page->type; ?></td>
            <td><?php print $page->taxonomy; ?></td>
            <td class="text-right">
                <?php if ($page->status): ?>
                <a href="<?php print get_admin_action_link(array('id'=>$page->id, 'action'=>'unactive')); ?>" class="btn btn-success"><i class="icon-ok icon-white"></i></a>
                <?php else: ?>
                <a href="<?php print get_admin_action_link(array('id'=>$page->id, 'action'=>'active')); ?>" class="btn btn-inverse"><i class="icon-ok icon-white"></i></a>
                <?php endif; ?>
                <a href="<?php print get_admin_action_link(array('id'=>$page->id, 'action'=>'edit')); ?>" class="btn btn-inverse"><i class="icon-pencil icon-white"></i></a>
                <a href="<?php print get_admin_action_link(array('id'=>$page->id, 'action'=>'remove')); ?>" class="btn btn-danger" confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><i class="icon-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

