<?php
$pagegroups = get_pagegroups();
if (empty($pagegroups))
{
    //ERROR
    die();
}
$pagegroup_id = get_request_group();
$pagegroup_name = _T('Pages');
if ($pagegroup_id === null)
{
    $pagegroup = current($pagegroups);
    $pagegroup_id = $pagegroup->GetID();
    $pagegroup_name = $pagegroup->GetName();
}
else
{
    $pagegroup_id = (int)$pagegroup_id;
    $pagegroup = isset($pagegroups[$pagegroup_id]) ? $pagegroups[$pagegroup_id] : null;
    if (is_null($pagegroup))
    {
        //ERROR
        die();
    }
    $pagegroup_name = $pagegroup->GetName();
}

$types = $pagegroup->GetType();
$taxonomies = $pagegroup->GetTaxonomy();


$pages = get_pages(array(
    'group' => $pagegroup_id,
    'type' => $types,
    'taxonomy' => $taxonomies,
    'order' => 'tree'
));
?>

<div class="DTTT btn-group">
    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="icon-list-alt"></i> <?php print $pagegroup_name; ?>
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

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered treetable data-expandable data-selectable" data-order="<?php print get_admin_api_action_link(array('group'=>$pagegroup_id, 'action'=>'order')); ?>">
    <thead>
        <tr>
            <td><?php print_text('Name'); ?></td>
            <td><?php print_text('Type'); ?></td>
            <td><?php print_text('Taxonomy'); ?></td>
            <td></td>
        </tr>
    </thead>
    <tbody>
        
        <?php
        function printPagesHTMLTree($pages, $parent = null)
        {
            foreach ($pages AS $k => $page)
            {
                if ($parent === $page->parent || (is_null($parent) && !isset($pages[$page->parent])))
                {
        ?>
        <tr data-tt-id="<?php print $page->id; ?>" <?php if (!is_null($page->parent) && isset($pages[$page->parent])): ?>data-tt-parent-id="<?php print $page->parent; ?>"<?php endif; ?>>
            <td><span class="folder"><?php print $page->GetTitle(); ?></span></td>
            <td><?php print $page->GetType()->GetName(); ?></td>
            <td><?php print $page->GetTaxonomy()->GetName(); ?></td>
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
        <?php
                    printPagesHTMLTree($pages, $page->id);
                }
            }
        }
        printPagesHTMLTree($pages);
        ?>
    </tbody>
</table>

