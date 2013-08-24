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
    'taxonomy' => $taxonomies
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


<div class="treetable" id="content-publish" data-order="<?php print get_admin_api_action_link(array('group'=>$pagegroup_id, 'action'=>'order')); ?>">
    <?php
    function printPagesHTMLTree($pages, $parent = null)
    {
        ?>
    <ol class="treetable-list">
        <?php
        foreach ($pages AS $k => $page)
        {
            if ($parent === $page->parent || (is_null($parent) && !isset($pages[$page->parent])))
            {
    ?>
        <li class="treetable-item treetable-collapsed" data-id="<?php print $page->id; ?>">
            <div class="treetable-actions">
                <?php if ($page->status): ?>
                <a href="<?php print get_admin_action_link(array('group'=>$page->group, 'id'=>$page->id, 'action'=>'unactive')); ?>" class="btn btn-success"><i class="icon-ok icon-white"></i></a>
                <?php else: ?>
                <a href="<?php print get_admin_action_link(array('group'=>$page->group, 'id'=>$page->id, 'action'=>'active')); ?>" class="btn btn-inverse"><i class="icon-ok icon-white"></i></a>
                <?php endif; ?>
                <a href="<?php print get_admin_action_link(array('group'=>$page->group, 'id'=>$page->id, 'action'=>'edit')); ?>" class="btn btn-inverse"><i class="icon-pencil icon-white"></i></a>
                <a href="<?php print get_admin_action_link(array('group'=>$page->group, 'id'=>$page->id, 'action'=>'remove')); ?>" class="btn btn-danger" confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><i class="icon-trash"></i></a>
            </div>
            <div class="treetable-handle">Drag</div>
            <div class="treetable-content"><?php print $page->GetTitle(); ?></div>
            <?php if ($page->GetTaxonomy()->ChildsAllowed()) { printPagesHTMLTree($pages, $page->id); } ?>
        </li>
    <?php
            }
        }
        ?>
    </ol>
        <?php
    }
    printPagesHTMLTree($pages);
    ?>
</div>

<?php /*
 * <?php if ($page->status): ?>
                <a href="<?php print get_admin_action_link(array('id'=>$page->id, 'action'=>'unactive')); ?>" class="btn btn-success"><i class="icon-ok icon-white"></i></a>
                <?php else: ?>
                <a href="<?php print get_admin_action_link(array('id'=>$page->id, 'action'=>'active')); ?>" class="btn btn-inverse"><i class="icon-ok icon-white"></i></a>
                <?php endif; ?>
                <a href="<?php print get_admin_action_link(array('id'=>$page->id, 'action'=>'edit')); ?>" class="btn btn-inverse"><i class="icon-pencil icon-white"></i></a>
                <a href="<?php print get_admin_action_link(array('id'=>$page->id, 'action'=>'remove')); ?>" class="btn btn-danger" confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><i class="icon-trash"></i></a>
 */ ?>

