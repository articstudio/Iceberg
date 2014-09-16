<?php
$pagegroups = get_pagegroups();
if (empty($pagegroups))
{
    //ERROR
    die();
}

$user = get_user();
$user2page = $user->GetRelation(User::RELATION_KEY_PAGE);
$pages = array();
$childs_allowed = array();
foreach ($pagegroups AS $pagegroup_id => $pagegroup)
{
    $pages[$pagegroup_id] = get_pages(array(
        'group' => $pagegroup_id,
        'id' => $user2page
    ));
    $childs_allowed[$pagegroup_id] = false;
    foreach ($pages[$pagegroup_id] AS $page)
    {
        $childs_allowed[$pagegroup_id] = $page->GetTaxonomy()->ChildsAllowed() ? true : $childs_allowed[$pagegroup_id];
    }
}


?>


<?php foreach ($pagegroups AS $pagegroup_id => $pagegroup): ?>
<?php if (count($pages[$pagegroup_id])): ?>

<!--<h3><?php print $pagegroup->GetName(); ?></h3>-->
<?php if ($childs_allowed[$pagegroup_id]): ?>
<div class="DTTT btn-group">
    <a href="<?php print get_admin_action_link(array('group'=>$pagegroup_id, 'action'=>'new')); ?>" class="btn">
        <i class="icon-plus"></i> <?php print_text('New'); ?>
    </a>
</div>
<?php endif; ?>
<div class="treetable" id="content-translate-<?php print $pagegroup_id; ?>">
    <?php
    function printPagesHTMLTree($pages)
    {
        $languages = get_active_langs();
        $language = get_language_info();
        ?>
    <ol class="treetable-list">
        <?php
        foreach ($pages AS $k => $page):
            if (count($pages>0)):
            ?>
        <li class="treetable-item no-drag" data-id="<?php print $page->id; ?>">
            <div class="treetable-actions">
                
                
                <?php if (count($languages) > 1): ?>
                <?php foreach ($languages AS $locale => $lang): ?>
                <?php if ($locale !== $language['locale']): ?>
                <?php if ($page->IsTranslated($locale)): ?>
                <a href="<?php print get_admin_action_link(array('group'=>$page->group, 'id'=>$page->id, 'action'=>'edit', 'tlang'=>$locale)); ?>" class="btn">
                    <img src="<?php print get_base_url() . $lang['flag']; ?>" alt="<?php print_html_attr($lang['name']); ?>" />
                    <i class="icon-pencil"></i>
                </a>
                <?php else: ?>
                <div class="btn-group">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        <img src="<?php print get_base_url() . $lang['flag']; ?>" alt="<?php print_html_attr($lang['name']); ?>" />
                        <i class="icon-plus"></i>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="<?php print get_admin_action_link(array('group'=>$page->group, 'id'=>$page->id, 'action'=>'new', 'tlang'=>$locale, 'duplicate'=>1)); ?>">
                                <i class="icon-retweet"></i>
                                <?php print_text('Duplicate'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php print get_admin_action_link(array('group'=>$page->group, 'id'=>$page->id, 'action'=>'new', 'tlang'=>$locale)); ?>">
                                <i class="icon-globe"></i>
                                <?php print_text('Translate'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
                <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>
                
                <?php if ($page->status): ?>
                <a href="<?php print get_admin_action_link(array('group'=>$page->group, 'id'=>$page->id, 'action'=>'unactive')); ?>" class="btn btn-success"><i class="icon-ok icon-white"></i></a>
                <?php else: ?>
                <a href="<?php print get_admin_action_link(array('group'=>$page->group, 'id'=>$page->id, 'action'=>'active')); ?>" class="btn btn-inverse"><i class="icon-ok icon-white"></i></a>
                <?php endif; ?>
                <a href="<?php print get_admin_action_link(array('group'=>$page->group, 'id'=>$page->id, 'action'=>'edit')); ?>" class="btn btn-inverse"><i class="icon-pencil icon-white"></i></a>
                <a href="<?php print get_admin_action_link(array('group'=>$page->group, 'id'=>$page->id, 'action'=>'remove')); ?>" class="btn btn-danger" confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><i class="icon-trash"></i></a>
            </div>
            <div class="treetable-content"><small class="highlight">[<?php print $page->GetTaxonomy()->GetName(); ?>]</small> <?php print $page->GetTitle(); ?></div>
            <?php
            if ($page->GetTaxonomy()->ChildsAllowed())
            {
                $pages = get_pages(array(
                    'group' => $page->group,
                    'parent' => $page->id
                ));
                printPagesHTMLTree($pages);
            }
            ?>
        </li>
            <?php
            endif;
        endforeach;
        ?>
    </ol>
        <?php
    }
    printPagesHTMLTree($pages[$pagegroup_id]);
    ?>
</div>

<?php endif; ?>
<?php endforeach; ?>







