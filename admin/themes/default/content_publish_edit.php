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
$id = (int)get_request_id();
$is_new = !(bool)$id;
$submit = $is_new ? array('action'=>'insert', 'group'=>$pagegroup_id) : array('action'=>'update', 'id'=>$id, 'group'=>$pagegroup_id);
$back = array('group'=>$pagegroup_id);

$page = get_page($id);
var_dump($page);

$language = get_language_info();
$languages = get_active_langs();
$pages = get_pages(array(
    'group' => $pagegroup_id
));
$types = $pagegroup->GetTypeObject();
$taxonomies = $pagegroup->GetTaxonomyObjects();
$taxonomies_permalink = $pagegroup->GetTaxonomyUsePermalink();
$taxonomies_text = $pagegroup->GetTaxonomyUseText();
$taxonomies_image = $pagegroup->GetTaxonomyUseImage();
?>

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
                
                
                <div class="form-actions text-right">
                    <a href="<?php print get_admin_action_link($back); ?>" class="btn btn-large btn-inverse"><?php print_text('Cancel'); ?> <i class="icon-remove-circle icon-white"></i></a>
                    <button type="submit" class="btn btn-large btn-success"><?php print_text('Save'); ?> <i class="icon-ok-circle icon-white"></i></button>
                </div>
            </div>
        </div>
        <div class="span3">
            <div class="well">
                <header><?php print_text('Publish'); ?></header>
                <?php if ($is_new): ?>
                <p>
                    <?php print_text('Created by'); ?>: <?php print get_user_name(); ?><br />
                    <?php print_text('Created on'); ?>: <?php print get_datetime(); ?>
                </p>
                <?php endif; ?>
                
                <div class="form-actions text-right">
                    <a href="<?php print get_admin_action_link($back); ?>" class="btn btn-inverse"><?php print_text('Cancel'); ?> <i class="icon-remove-circle icon-white"></i></a>
                    <button type="submit" class="btn btn-success"><?php print_text('Save'); ?> <i class="icon-ok-circle icon-white"></i></button>
                </div>
            </div>
            
            <div class="well">
                <header><?php print_text('Settings'); ?></header>
                <p>
                    <label><?php print_text('Group'); ?>: <strong><?php print $pagegroup->GetName(); ?></strong></label>
                </p>
                <p>
                    <label for="parent"><?php print_text('Parent'); ?>:</label>
                    <select name="parent" id="parent" class="input-block-level">
                        <option value="NULL"></option>
                        <?php foreach ($pages AS $parent_page): ?>
                        <option value="<?php print $parent_page->id; ?>" <?php print ($parent_page->id == $page->parent) ? 'selected' : ''; ?>><?php print $parent_page->GetTitle(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
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
                        <option value="<?php print $taxonomy->GetID(); ?>" <?php print ($taxonomy->GetID() == $page->taxonomy) ? 'selected' : ''; ?>><?php print $taxonomy->GetName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
            </div>
            
            <?php if (count($languages) > 1): ?>
            <div class="well">
                <header><?php print_text('Translations'); ?></header>
                <?php foreach ($languages AS $locale => $lang): ?>
                <?php if ($locale !== $language['locale']): ?>
                <p>
                    <label class="checkbox" for="duplicate_<?php print_html_attr($lang['locale']); ?>">
                        <input type="checkbox" id="duplicate_<?php print_html_attr($lang['locale']); ?>" />
                        <img src="<?php print get_base_url() . $lang['flag']; ?>" alt="<?php print_html_attr($lang['name']); ?>" />
                        <?php print $lang['name']; ?>
                    </label>
                </p>
                <?php endif; ?>
                <?php endforeach; ?>
                <p class="text-right">
                    <a href="#" class="btn btn-inverse"><i class="icon-globe icon-white"></i> <?php print_text('Duplicate'); ?></a>
                </p>
            </div>
            <?php endif; ?>
            <div class="well" id="image-container" data-filter="taxonomy" data-filter-values="<?php print implode(',', $taxonomies_image); ?>">
                <header><?php print_text('Principal image'); ?></header>
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
