<?php
$pagegroup_id = get_request_group();
if ($pagegroup_id === null)
{
    $pagegroups = get_pagegroups();
    $pagegroup = current($pagegroups);
    $pagegroup_id = $pagegroup->GetID();
}
else
{
    $pagegroup = get_pagegroup($pagegroup_id);
}

$language = get_language_info();
$languages = get_active_langs();

$id = (int)get_request_id();
$action = get_request_action();

$tlang = get_request_gp('tlang');
$tlanguage = get_language_info($tlang);

$is_duplicate = (bool)get_request_g('duplicate');
$is_translate = $tlang===false ? $tlang : is_active_language($tlang);

$plang = $is_translate && !$is_duplicate ? $tlang : $language['locale'];
$page = get_page($id, $plang);
$tpage = $is_translate ? get_page($id) : $page;

$is_new = !(bool)$id || $action === 'new' || $page->id === -1;

$submit = $is_new ? ($is_translate ? array('action'=>'translate', 'id'=>$id, 'group'=>$pagegroup_id, 'tlang'=>$tlang) : array('action'=>'insert', 'group'=>$pagegroup_id)) : ($is_translate ? array('action'=>'translate', 'id'=>$id, 'group'=>$pagegroup_id, 'tlang'=>$tlang) : array('action'=>'update', 'id'=>$id, 'group'=>$pagegroup_id));
$back = array('group'=>$pagegroup_id);

$p_type = get_pagetype($page->type);
$p_taxonomy = get_pagetaxonomy($page->taxonomy);
$p_templates = $p_taxonomy->GetTemplates();

$pages = get_pages(array(
    'group' => $pagegroup_id,
    'order' => 'name'
), $plang);

$types = $pagegroup->GetTypeObject();
$taxonomies = $pagegroup->GetTaxonomyObjects();
$templates = $pagegroup->GetTemplates();

$taxonomies_permalink = $pagegroup->GetTaxonomyUsePermalink();
$taxonomies_text = $pagegroup->GetTaxonomyUseText();
$taxonomies_image = $pagegroup->GetTaxonomyUseImage();

function printPagesHTMLTree($pages, $active = null, $actual = null, $parent = null)
{
    foreach ($pages AS $k => $page)
    {
        if (($parent === $page->parent || (is_null($parent) && !isset($pages[$page->parent]))) && $page->id != $actual && $page->GetTaxonomy()->ChildsAllowed())
        {
            ?>
            <li>
                <span class="click <?php print $page->id==$active ? 'active' : ''; ?>" data-click="<?php print $page->id; ?>"><?php print $page->GetTitle(); ?></span>
                
                <?php if ($page->GetTaxonomy()->ChildsAllowed()): ?>
                <ul>
                    <?php printPagesHTMLTree($pages, $active, $actual, $page->id); ?>
                </ul>
                <?php endif; ?>
            </li>
            <?php
        }
    }
}
?>

<?php if ($is_translate): ?>
<div class="alert alert-info">
    <img src="<?php print get_base_url() . $tlanguage['flag']; ?>" alt="<?php print_html_attr($tlanguage['name']); ?>" />
    <strong><?php print_text('Content is showing and editing in'); ?> <?php print $tlanguage['name']; ?></strong>
</div>
<?php endif; ?>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="publish-edit">
    <div class="row-fluid">
        <div class="span9">
            <div class="well">
                <header><?php print_text('Page'); ?></header>
                
                <p>
                    <label for="name"><?php print_text('Name'); ?>:</label>
                    <input type="text" name="name" id="name" class="input-block-level" value="<?php print_html_attr($page->GetTitle()); ?>" permalink="#permalink" required />
                </p>
                
                <p id="permalink-container" data-filter="taxonomy" data-filter-values="<?php print implode(',', $taxonomies_permalink); ?>">
                    <label for="permalink"><?php print_text('Permalink'); ?>:</label>
                    <input type="text" name="permalink" id="permalink" class="input-block-level" value="<?php print_html_attr($page->GetPermalink()); ?>" required />
                </p>
                
                <div id="text-container" data-filter="taxonomy" data-filter-values="<?php print implode(',', $taxonomies_text); ?>">
                    <label for="text"><?php print_text('Text'); ?>:</label>
                    <textarea class="ckeditor input-block-level" id="text" name="text" rows="10" cols="10"><?php print $page->GetText(); ?></textarea>
                </div>
                
                <?php if ($is_new): ?>
                <?php foreach ($taxonomies AS $taxonomy): ?>
                <div id="taxonomy-<?php print $taxonomy->GetID(); ?>" data-filter="taxonomy" data-filter-values="<?php print $taxonomy->GetID(); ?>">
                    <hr />
                    <h5><?php print $taxonomy->GetName(); ?></h5>
                    <?php $elements = $taxonomy->GetElements(); foreach ($elements AS $e_name => $element): ?>
                    <hr />
                    <h6><?php print_text('Attribute'); ?>: <?php print $e_name; ?></h6>
                    <?php $element->FormEdit($page); ?>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                
                <hr />
                <h5><?php print $p_taxonomy->GetName(); ?></h5>
                <?php $elements = $p_taxonomy->GetElements(); foreach ($elements AS $e_name => $element): ?>
                <hr />
                <h6><?php print_text('Attribute'); ?>: <?php print $e_name; ?></h6>
                <?php $element->FormEdit($page); ?>
                <?php endforeach; ?>
                
                <?php endif; ?>
                
                
                <div class="form-actions text-right">
                    <a href="<?php print get_admin_action_link($back); ?>" class="btn btn-large btn-inverse"><?php print_text('Cancel'); ?> <i class="icon-remove-circle icon-white"></i></a>
                    <?php if ($is_translate): ?>
                    <button type="submit" class="btn btn-large btn-success"><img src="<?php print get_base_url() . $tlanguage['flag']; ?>" alt="<?php print_html_attr($tlanguage['name']); ?>" /> <?php print_text('Save'); ?></button>
                    <?php else: ?>
                    <button type="submit" class="btn btn-large btn-success"><?php print_text('Save'); ?> <i class="icon-ok-circle icon-white"></i></button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="span3">
            
            <?php if (count($languages) > 1): ?>
            <?php if ($is_translate): ?>
            
            <div class="well widget">
                <header><?php print_text('Translation'); ?></header>
                <div class="btn-toolbar header">
                    <a href="#" class="btn btn-inverse btn-mini" btn-action="collapse"><i class="icon-chevron-up icon-white"></i></a>
                    <a href="#" class="btn btn-inverse btn-mini" btn-action="expand"><i class="icon-chevron-down icon-white"></i></a>
                </div>
                
                <p>
                    <img src="<?php print get_base_url() . $tlanguage['flag']; ?>" alt="<?php print_html_attr($tlanguage['name']); ?>" /> <?php print $tlanguage['name']; ?>
                </p>
                <p>
                    <i><?php print $tpage->GetTitle(); ?></i>
                </p>
                
                <?php if (count($languages) > 2): ?>
                <p>
                    <strong><?php print_text('Duplicate'); ?>:</strong>
                </p>
                <?php foreach ($languages AS $locale => $lang): ?>
                <?php if ($language['locale'] !== $locale && $tlang !== $locale): ?>
                <p>
                    
                    <label class="checkbox" for="duplicate_<?php print_html_attr($locale); ?>">
                        <input type="checkbox" name="duplicate[]" id="duplicate_<?php print_html_attr($lang['locale']); ?>" value="<?php print_html_attr($locale); ?>" <?php print !$page->IsTranslated($locale) ? 'checked' : ''; ?> />
                        <img src="<?php print get_base_url() . $lang['flag']; ?>" alt="<?php print_html_attr($lang['name']); ?>" />
                        <?php print $lang['name']; ?>
                        <?php if ($page->IsTranslated($locale)): ?>
                        <a href="<?php print get_admin_action_link(array('group'=>$page->group, 'id'=>$page->id, 'action'=>'edit', 'tlang'=>$locale)); ?>" class="">
                            <i class="icon-pencil"></i>
                        </a>
                        <?php endif; ?>
                    </label>
                </p>
                <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <?php else: ?>
            
            <div class="well widget">
                <header><?php print_text('Translations'); ?></header>
                <div class="btn-toolbar header">
                    <a href="#" class="btn btn-inverse btn-mini" btn-action="collapse"><i class="icon-chevron-up icon-white"></i></a>
                    <a href="#" class="btn btn-inverse btn-mini" btn-action="expand"><i class="icon-chevron-down icon-white"></i></a>
                </div>
                
                <p>
                    <strong><?php print_text('Duplicate'); ?>:</strong>
                </p>
                <?php foreach ($languages AS $locale => $lang): ?>
                <?php if ($language['locale'] !== $locale): ?>
                <p>
                    
                    <label class="checkbox" for="duplicate_<?php print_html_attr($locale); ?>">
                        <input type="checkbox" name="duplicate[]" id="duplicate_<?php print_html_attr($lang['locale']); ?>" value="<?php print_html_attr($locale); ?>" <?php print !$page->IsTranslated($locale) ? 'checked' : ''; ?> />
                        <img src="<?php print get_base_url() . $lang['flag']; ?>" alt="<?php print_html_attr($lang['name']); ?>" />
                        <?php print $lang['name']; ?>
                        <?php if ($page->IsTranslated($locale)): ?>
                        <a href="<?php print get_admin_action_link(array('group'=>$page->group, 'id'=>$page->id, 'action'=>'edit', 'tlang'=>$locale)); ?>" class="">
                            <i class="icon-pencil"></i>
                        </a>
                        <?php endif; ?>
                    </label>
                </p>
                <?php endif; ?>
                <?php endforeach; ?>
                
            </div>
            
            <?php endif; ?>
            <?php endif; ?>
            
            <div class="well widget">
                <header><?php print_text('Publish'); ?></header>
                <div class="btn-toolbar header">
                    <a href="#" class="btn btn-inverse btn-mini" btn-action="collapse"><i class="icon-chevron-up icon-white"></i></a>
                    <a href="#" class="btn btn-inverse btn-mini" btn-action="expand"><i class="icon-chevron-down icon-white"></i></a>
                </div>
                
                <?php /* if ($is_new): ?>
                <p>
                    <?php print_text('Created by'); ?>: <?php print get_user_name(); ?><br />
                    <?php print_text('Created on'); ?>: <?php print get_datetime(); ?>
                </p>
                <?php else: ?>
                <p>
                    <?php print_text('Created by'); ?>: <?php print get_user($page->created_uid)->username; ?><br />
                    <?php print_text('Created on'); ?>: <?php print get_datetime($page->created); ?>
                </p>
                <?php if (is_null($page->updated_uid)): ?>
                <p>
                    <?php print_text('Edited by'); ?>: <?php print get_user_name(); ?><br />
                    <?php print_text('Edited on'); ?>: <?php print get_datetime(); ?>
                </p>
                <?php else: ?>
                <p>
                    <?php print_text('Edited by'); ?>: <?php print get_user($page->updated_uid)->username; ?><br />
                    <?php print_text('Edited on'); ?>: <?php print get_datetime($page->updated); ?>
                </p>
                <?php endif; ?>
                <?php endif; */ ?>
                
                <div class="form-actions text-right">
                    <a href="<?php print get_admin_action_link($back); ?>" class="btn btn-inverse"><?php print_text('Cancel'); ?> <i class="icon-remove-circle icon-white"></i></a>
                    <?php if ($is_translate): ?>
                    <button type="submit" class="btn btn-success"><img src="<?php print get_base_url() . $tlanguage['flag']; ?>" alt="<?php print_html_attr($tlanguage['name']); ?>" /> <?php print_text('Save'); ?></button>
                    <?php else: ?>
                    <button type="submit" class="btn btn-success"><?php print_text('Save'); ?> <i class="icon-ok-circle icon-white"></i></button>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="well widget">
                <header><?php print_text('Settings'); ?></header>
                <div class="btn-toolbar header">
                    <a href="#" class="btn btn-inverse btn-mini" btn-action="collapse"><i class="icon-chevron-up icon-white"></i></a>
                    <a href="#" class="btn btn-inverse btn-mini" btn-action="expand"><i class="icon-chevron-down icon-white"></i></a>
                </div>
                
                <p>
                    <label><?php print_text('Group'); ?>: <strong><?php print $pagegroup->GetName(); ?></strong></label>
                </p>
                
                <label for="parent"><?php print_text('Parent'); ?>:</label>
                <div class="treeview-container mini">
                    <ul select-treeview="parent">
                        <?php
                        printPagesHTMLTree($pages, $page->parent, $id);
                        ?>
                    </ul>
                    <input type="hidden" name="parent" id="parent" value="<?php print is_null($page->parent) ? 'NULL' : $page->parent; ?>" />
                </div>
                
                <?php if ($is_new): ?>
                <p>
                    <label for="type"><?php print_text('Type'); ?>:</label>
                    <select name="type" id="type" class="input-block-level">
                        <?php foreach ($types AS $type): ?>
                        <option value="<?php print $type->GetID(); ?>" data-filter-values="<?php print implode(',', $type->GetTaxonomy()); ?>" <?php print ($type->GetID() == $page->type) ? 'selected' : ''; ?>><?php print $type->GetName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <label for="taxonomy"><?php print_text('Taxonomy'); ?>:</label>
                    <select name="taxonomy" id="taxonomy" class="input-block-level" data-filter="type">
                        <?php foreach ($taxonomies AS $taxonomy): ?>
                        <option value="<?php print $taxonomy->GetID(); ?>" data-filter-values="<?php print implode(',', $taxonomy->GetTemplates()); ?>" <?php print ($taxonomy->GetID() == $page->taxonomy) ? 'selected' : ''; ?>><?php print $taxonomy->GetName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <?php if (count($templates) > 0): ?>
                <p>
                    <label for="template"><?php print_text('Template'); ?>:</label>
                    <select name="template" id="template" class="input-block-level" data-filter="taxonomy">
                        <?php foreach ($templates AS $template): ?>
                        <option value="<?php print $template; ?>" <?php print ($template == $page->GetTemplate()) ? 'selected' : ''; ?>><?php print $template; ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <?php endif; ?>
                <?php else: ?>
                <p>
                    <label><?php print_text('Type'); ?>: <strong><?php print $p_type->GetName(); ?></strong></label>
                    <input type="hidden" name="type" value="<?php print $page->type; ?>" />
                </p>
                <p>
                    <label><?php print_text('Taxonomy'); ?>: <strong><?php print $p_taxonomy->GetName(); ?></strong></label>
                    <input type="hidden" name="taxonomy" value="<?php print $page->taxonomy; ?>" />
                </p>
                <?php if (count($p_templates) > 0): ?>
                <p>
                    <label for="template"><?php print_text('Template'); ?>:</label>
                    <select name="template" id="template" class="input-block-level" data-filter="taxonomy">
                        <?php foreach ($p_templates AS $template): ?>
                        <option value="<?php print $template; ?>" <?php print ($template == $page->GetTemplate()) ? 'selected' : ''; ?>><?php print $template; ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="well widget" id="image-container" data-filter="taxonomy" data-filter-values="<?php print implode(',', $taxonomies_image); ?>">
                <header><?php print_text('Principal image'); ?></header>
                <div class="btn-toolbar header">
                    <a href="#" class="btn btn-inverse btn-mini" btn-action="collapse"><i class="icon-chevron-up icon-white"></i></a>
                    <a href="#" class="btn btn-inverse btn-mini" btn-action="expand"><i class="icon-chevron-down icon-white"></i></a>
                </div>
                
                <div id="page-image">
                    <p>
                        <button type="button" id="page-image-button" class="btn btn-inverse"><?php print_text('Browse'); ?></button>
                        <input type="hidden" name="image" id="image" class="input-block-level" value="<?php print_html_attr($page->GetImage()); ?>" />
                        <span class="thumbnail"><img src="<?php print_html_attr($page->GetImage()); ?>" /></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>
