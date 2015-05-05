<?php

/* Routing */
$action = get_action('action');
$mode = get_mode('mode');
$mode_group = explode('-', get_mode('mode'));

/* Language */
$language = get_language_info();
$languages = get_active_langs();

/* Translate language */
$translate_locale = get_request_gp('tlang');
$translate_language = get_language_info($translate_locale);

/* Translate / Duplicate */
$is_translate = (bool)($translate_locale===false ? $translate_locale : is_active_language($translate_locale));
$is_duplicate = ((bool)get_request_g('duplicate') && $is_translate);

/* Capabilities */
$capabilities = get_user_capabilities();

/* Page group */
$pagegroup_id = isset($mode_group[1]) ? (int)$mode_group[1] : -1;
$pagegroup = get_pagegroup($pagegroup_id);
$pagegroup_types = $pagegroup->GetTypeObject();
$pagegroup_types_count = count($pagegroup_types);
$pagegroup_taxonomies = $pagegroup->GetTaxonomyObjects();
$pagegroup_taxonomies_count = count($pagegroup_taxonomies);
$pagegroup_templates = $pagegroup->GetTemplates();
$pagegroup_templates_count = count($pagegroup_templates);

/* Page */
$page_id = (int)get_request_id();
$page_locale = ($is_translate && !$is_duplicate) ? $translate_locale : $language['locale'];
$page = get_page($page_id, $page_locale, false);
$page_id = $page->id;
$translate_page = $is_translate ? get_page($page_id, null, false) : $page;
$page_type = get_pagetype($page->type);
$page_taxonomy = get_pagetaxonomy($page->taxonomy);
$page_templates = $page_taxonomy->GetTemplates();
$page_parent = $page->parent;
$page_user = $page_id===-1 ? new User() : $page->GetUser();

/* Pagegroup taxonomies */
$pagegroup_taxonomy_ids_use_permalink = $pagegroup->GetTaxonomyUsePermalink();
$pagegroup_taxonomy_ids_use_permalink_count = count($pagegroup_taxonomy_ids_use_permalink);
$pagegroup_taxonomy_ids_use_text = $pagegroup->GetTaxonomyUseText();
$pagegroup_taxonomy_ids_use_text_count = count($pagegroup_taxonomy_ids_use_text);
$pagegroup_taxonomy_ids_use_image = $pagegroup->GetTaxonomyUseImage();
$pagegroup_taxonomy_ids_use_image_count = count($pagegroup_taxonomy_ids_use_image);
$pagegroup_taxonomy_ids_accept_childs = $pagegroup->GetTaxonomyChildsAllowed();
$pagegroup_taxonomy_ids_accept_childs_count = count($pagegroup_taxonomy_ids_accept_childs);
$pagegroup_taxonomy_ids_user_relation = $pagegroup->GetTaxonomyUserRelation();
$pagegroup_taxonomy_ids_user_relation_count = count($pagegroup_taxonomy_ids_user_relation);

/* Possible parents */
$possible_parents = array();
if ($pagegroup_taxonomy_ids_accept_childs_count > 0)
{
    $possible_parents = get_pages(
        array(
            'group' => $pagegroup_id,
            'taxonomy' => $pagegroup_taxonomy_ids_accept_childs,
            'order' => 'name'
        ),
        $page_locale,
        true,
        array(
            PageMeta::META_TITLE
        )
    );
    if (count($possible_parents) > 0)
    {
        foreach ($possible_parents AS $k => $v)
        {
            if ($v->id === $page_id)
            {
                unset($possible_parents[$k]);
                break;
            }
        }
    }
}
$possible_parents_count = count($possible_parents);


/* Is new action */
$is_new = (!(bool)$page_id || $page->id === -1 || $action === 'new');

/* Buttons Attributes */
$submit = array('action'=>'insert');
if (!$is_new)
{
    if ($is_translate)
    {
        $submit = array('action'=>'translate', 'id'=>$page_id, 'tlang'=>$translate_locale);
    }
    else
    {
       $submit = array('action'=>'update', 'id'=>$page_id);
    }
}
$back = array('action'=>'list');

$have_permalink = $is_new ? ($pagegroup_taxonomy_ids_use_permalink_count>0) : $page_taxonomy->UsePermalink();

$have_text = $is_new ? ($pagegroup_taxonomy_ids_use_text_count>0) : $page_taxonomy->UseText();


/* BYPASS TRANSLATE */
//$is_translate = true; $translate_locale = 'es_ES'; $translate_language = get_language_info($translate_locale);

?>

<?php if ($is_translate): ?>
<div class="alert alert-info">
    <img src="<?php print get_base_url() . $translate_language['flag']; ?>" alt="<?php print_html_attr($translate_language['name']); ?>" />
    <strong><?php print_text('Content is showing and editing in'); ?> <?php print $translate_language['name']; ?></strong>
</div>
<?php endif; ?>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="publish-edit" role="form" validate>
    <div class="row">
        <!-- EDIT COLUMN -->
        <div class="col-sm-9">
            
            <div id="page-edit-content-wrapper">
                <?php do_action('content_publish_edit_content_header', $action, $pagegroup_id, $page_id, $page_locale); ?>
            
                <p class="form-group">
                    <label for="name" class="control-label"><?php print_text('Name'); ?></label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php print_html_attr($page->GetTitle()); ?>" data-permalink="#permalink" required>
                    <?php if ($have_permalink): ?>
                    <a href="#" class="help-block" data-toggle="#permalink-wrapper" data-filter="taxonomy" data-filter-values="<?php print implode(',', $pagegroup_taxonomy_ids_use_permalink); ?>"><span class="glyphicon glyphicon-link"></span> <?php print_text('Show/hide permalink'); ?></a>
                    <?php endif; ?>
                </p>

                <?php if ($have_permalink): ?>
                <div id="permalink-wrapper" style="display:none;">
                    <p class="form-group" data-filter="taxonomy" data-filter-values="<?php print implode(',', $pagegroup_taxonomy_ids_use_permalink); ?>">
                        <label for="permalink" class="control-label"><?php print_text('Permalink'); ?></label>
                        <input type="text" name="permalink" id="permalink" class="form-control" value="<?php print_html_attr($page->GetPermalink()); ?>" data-language="<?php echo $page_locale; ?>" data-id="<?php echo $page_id; ?>" required />
                    </p>
                </div>
                <?php endif; ?>

                <?php if ($have_text): ?>
                <div class="form-group" data-filter="taxonomy" data-filter-values="<?php print implode(',', $pagegroup_taxonomy_ids_use_text); ?>">
                    <label for="text" class="control-label"><?php print_text('Text'); ?></label>
                    <textarea class="ckeditor form-control" id="text" name="text" rows="10" cols="10"><?php print $page->GetText(); ?></textarea>
                </div>
                <?php endif; ?>
                
                <?php do_action('content_publish_edit_content_middle', $action, $pagegroup_id, $page_id, $page_locale); ?>
                
                <?php if ($is_new): ?>
                
                <?php foreach ($pagegroup_taxonomies AS $taxonomy): ?>
                <div id="taxonomy-<?php print $taxonomy->GetID(); ?>" data-filter="taxonomy" data-filter-values="<?php print $taxonomy->GetID(); ?>">
                    <?php $elements = $taxonomy->GetElements(); foreach ($elements AS $e_name => $element): ?>
                    <div id="element-<?php print $taxonomy->GetID(); ?>-<?php print $element->GetAttrName(); ?>">
                        <?php $element->FormEdit($page); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
                
                <?php else: ?>
                
                <?php $elements = $page_taxonomy->GetElements(); foreach ($elements AS $e_name => $element): ?>
                <div id="element-<?php print $page_taxonomy->GetID(); ?>-<?php print $element->GetAttrName(); ?>">
                    <?php $element->FormEdit($page); ?>
                </div>
                <?php endforeach; ?>
                
                <?php endif; ?>

                <?php do_action('content_publish_edit_content_footer', $action, $pagegroup_id, $page_id, $page_locale); ?>
            </div>
            
        </div>
        <!-- /EDIT COLUMN -->
        
        <!-- SIDEBAR COLUMN -->
        <div class="col-sm-3">
            
            <?php do_action('content_publish_edit_sidebar_header', $action, $pagegroup_id, $page_id, $page_locale); ?>
            
            <?php if (count($languages) > 1): ?>
            <!-- TRANSLATION WIDGET -->
            <?php if ($is_translate): ?>
            <div class="well widget">
                <header><?php print_text($is_duplicate ? 'Duplication' : 'Translation'); ?></header>
                <div class="btn-toolbar header">
                    <a href="#" class="btn btn-default btn-xs" btn-action="collapse"><span class="glyphicon glyphicon-chevron-up"></span></a>
                    <a href="#" class="btn btn-default btn-xs" btn-action="expand"><span class="glyphicon glyphicon-chevron-down"></span></a>
                </div>
                <p>
                    <img src="<?php print get_base_url() . $translate_language['flag']; ?>" alt="<?php print_html_attr($translate_language['name']); ?>" /> <?php print $translate_language['name']; ?>
                </p>
                <p>
                    <i><?php print $translate_page->GetTitle(); ?></i>
                </p>
            </div>
            <?php endif; ?>
            
            <?php if (!$is_translate || count($languages)>2): ?>
            <div class="well widget">
                <header><?php print_text('Duplicate'); ?></header>
                <div class="btn-toolbar header">
                    <a href="#" class="btn btn-default btn-xs" btn-action="collapse"><span class="glyphicon glyphicon-chevron-up"></span></a>
                    <a href="#" class="btn btn-default btn-xs" btn-action="expand"><span class="glyphicon glyphicon-chevron-down"></span></a>
                </div>
                <?php foreach ($languages AS $duplicate_locale => $duplicate_lang): ?>
                <?php if (($language['locale'] !== $duplicate_locale) && (!$is_translate || $translate_locale !== $duplicate_locale)): ?>
                <p class="radio">
                    <label class="checkbox" for="duplicate_<?php print_html_attr($duplicate_locale); ?>" class="control-label">
                        <input type="checkbox" name="duplicate[]" id="duplicate_<?php print_html_attr($duplicate_lang['locale']); ?>" value="<?php print_html_attr($duplicate_locale); ?>" <?php print !$page->IsTranslated($duplicate_locale) ? 'checked' : ''; ?>>
                        <img src="<?php print get_base_url() . $duplicate_lang['flag']; ?>" alt="<?php print_html_attr($duplicate_lang['name']); ?>" />
                        <?php print $duplicate_lang['name']; ?>
                        <?php if ($page->IsTranslated($duplicate_locale)): ?>
                        <a href="<?php print get_admin_action_link(array('id'=>$page->id,'action'=>'edit','tlang'=>$duplicate_locale)); ?>" class="">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                        <?php endif; ?>
                    </label>
                </p>
                <?php endif; ?>
                <?php endforeach; ?>
                
            </div>
            <?php endif; ?>
            <!-- /TRANSLATION WIDGET -->
            <?php endif; ?>
            
            
            <!-- PUBLISH WIDGET -->
            <div class="well widget">
                <header><?php print_text('Publish'); ?></header>
                <div class="btn-toolbar header">
                    <a href="#" class="btn btn-default btn-xs" btn-action="collapse"><span class="glyphicon glyphicon-chevron-up"></span></a>
                    <a href="#" class="btn btn-default btn-xs" btn-action="expand"><span class="glyphicon glyphicon-chevron-down"></span></a>
                </div>
                <?php //@todo Owner / Created / Edited ?>
                <div class="text-right">
                    <a href="<?php print get_admin_action_link($back); ?>" class="btn btn-default"><span class="glyphicon glyphicon-ban-circle"></span> <?php print_text('Cancel'); ?></a>
                    <?php if ($is_translate): ?>
                    <button type="submit" class="btn btn-success"><img src="<?php print get_base_url() . $translate_language['flag']; ?>" alt="<?php print_html_attr($translate_language['name']); ?>" /> <?php print_text('Save'); ?></button>
                    <?php else: ?>
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> <?php print_text('Save'); ?></button>
                    <?php endif; ?>
                </div>
            </div>
            <!-- /PUBLISH WIDGET -->
            
            <!-- SETTINGS WIDGET -->
            <?php if ($possible_parents_count>0 || $pagegroup_types_count>1 || $pagegroup_taxonomies_count>1 || $pagegroup_templates_count>1): ?>
            
            <div class="well widget">
                <header><?php print_text('Settings'); ?></header>
                <div class="btn-toolbar header">
                    <a href="#" class="btn btn-default btn-xs" btn-action="collapse"><span class="glyphicon glyphicon-chevron-up"></span></a>
                    <a href="#" class="btn btn-default btn-xs" btn-action="expand"><span class="glyphicon glyphicon-chevron-down"></span></a>
                </div>
                
                <?php if ($possible_parents_count>0): ?>
                <!-- PARENT BLOCK -->
                <p class="control-group">
                    <label for="parent" class="control-label"><?php print_text('Parent'); ?></label>
                    <select name="parent" id="parent" class="form-control">
                        <option value="NULL"></option>
                        <?php foreach ($possible_parents AS $possible_parent): ?>
                        <?php if ($possible_parent->id !== $page_id): ?>
                        <option value="<?php echo $possible_parent->id; ?>" <?php echo $page_parent === $possible_parent->id ? 'selected' : ''; ?>><?php echo $possible_parent->GetTitle(); ?></option>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </p>
                <!-- /PARENT BLOCK -->
                <?php endif; ?>
                
                <?php if ($is_new): ?>
                
                <!-- TYPE BLOCK -->
                <?php if ($pagegroup_types_count>1): ?>
                <p class="control-group">
                    <label for="type" class="control-label"><?php print_text('Type'); ?>:</label>
                    <select name="type" id="type" class="form-control">
                        <?php foreach ($pagegroup_types AS $buffer_type): ?>
                        <option value="<?php print $buffer_type->GetID(); ?>" data-filter-values="<?php print implode(',', $buffer_type->GetTaxonomy()); ?>"><?php print $buffer_type->GetName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <?php elseif ($pagegroup_types_count===1): $buffer_type=reset($pagegroup_types); ?>
                <input type="hidden" name="type" id="type" value="<?php print $buffer_type->GetID(); ?>">
                <?php endif; ?>
                <!-- /TYPE BLOCK -->
                
                <!-- TAXONOMY BLOCK -->
                <?php if ($pagegroup_taxonomies_count>1): ?>
                <p class="control-group">
                    <label for="taxonomy" class="control-label"><?php print_text('Taxonomy'); ?>:</label>
                    <select name="taxonomy" id="taxonomy" class="form-control" data-filter="type">
                        <?php foreach ($pagegroup_taxonomies AS $buffer_taxonomy): ?>
                        <option value="<?php print $buffer_taxonomy->GetID(); ?>" data-filter-values="<?php print implode(',', $buffer_taxonomy->GetTemplates()); ?>"><?php print $buffer_taxonomy->GetName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <?php elseif ($pagegroup_taxonomies_count===1): $buffer_taxonomy=reset($pagegroup_taxonomies); ?>
                <input type="hidden" name="taxonomy" id="taxonomy" value="<?php print $buffer_taxonomy->GetID(); ?>">
                <?php endif; ?>
                <!-- /TAXONOMY BLOCK -->
                
                <?php else: ?>
                
                <input type="hidden" name="type" id="type" value="<?php print $page->type; ?>">
                <input type="hidden" name="taxonomy" id="taxonomy" value="<?php print $page->taxonomy; ?>">
                
                <?php endif; ?>
                
                <!-- /TEMPLATE BLOCK -->
                <?php if ($pagegroup_templates_count>1): ?>
                <p class="control-group">
                    <label for="template" class="control-label"><?php print_text('Template'); ?>:</label>
                    <select name="template" id="template" class="form-control" data-filter="taxonomy">
                        <?php foreach ($pagegroup_templates AS $buffer_template): ?>
                        <option value="<?php print $buffer_template; ?>" <?php echo $buffer_template===$page->GetTemplate() ? 'selected' : ''; ?>><?php print $buffer_template; ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <?php elseif ($pagegroup_templates_count===1): $buffer_template=reset($pagegroup_templates); ?>
                <input type="hidden" name="template" id="template" value="<?php print $buffer_template; ?>">
                <?php endif; ?>
                <!-- TEMPLATE BLOCK -->
                
                
            </div>
            
            <?php else: ?>
            
            <?php if ($is_new): ?>
            
            <?php if ($pagegroup_types_count===1): $buffer_type=reset($pagegroup_types); ?>
            <input type="hidden" name="type" id="type" value="<?php print $buffer_type->GetID(); ?>">
            <?php endif; ?>
            <?php if ($pagegroup_taxonomies_count===1): $buffer_taxonomy=reset($pagegroup_taxonomies); ?>
            <input type="hidden" name="taxonomy" id="taxonomy" value="<?php print $buffer_taxonomy->GetID(); ?>">
            <?php endif; ?>
            <?php if ($pagegroup_templates_count===1): $buffer_template=reset($pagegroup_templates); ?>
            <input type="hidden" name="template" id="template" value="<?php print $buffer_template; ?>">
            <?php endif; ?>
            
            <?php else: ?>
                
            <input type="hidden" name="parent" id="parent" value="<?php print is_null($page->parent) ? 'NULL' : $page->parent; ?>">
            <input type="hidden" name="type" id="type" value="<?php print $page->type; ?>">
            <input type="hidden" name="taxonomy" id="taxonomy" value="<?php print $page->taxonomy; ?>">
            <input type="hidden" name="template" id="template" value="<?php print $page->GetTemplate(); ?>">

            <?php endif; ?>
            
            <?php endif; ?>
            <!-- /SETTINGS WIDGET -->
            
            <?php if (($is_new && $pagegroup_taxonomy_ids_use_image_count) || (!$is_new && $page_taxonomy->UseImage())): ?>
            <!-- IMAGE WIDGET -->
            <div class="well widget" id="page-edit-image-wrapper" data-filter="taxonomy" data-filter-values="<?php print implode(',', $pagegroup_taxonomy_ids_use_image); ?>">
                <header><?php print_text('Principal image'); ?></header>
                <div class="btn-toolbar header">
                    <a href="#" class="btn btn-default btn-xs" btn-action="collapse"><span class="glyphicon glyphicon-chevron-up"></span></a>
                    <a href="#" class="btn btn-default btn-xs" btn-action="expand"><span class="glyphicon glyphicon-chevron-down"></span></a>
                </div>
                
                <p class="control-group">
                    <button class="btn btn-default" data-elfinder-file="image"><span class="glyphicon glyphicon-picture"></span> <?php print_text('Browse'); ?></button>
                    <input type="hidden" name="image" id="image" value="<?php print_html_attr($page->GetImage()); ?>" data-thumbnail="#page-image-thumbnail">
                </p>
                <p class="control-group" id="page-image-thumbnail">
                    <?php if ($page->GetImage()): ?>
                    <span class="thumbnail"><img src="<?php print_html_attr($page->GetImage()); ?>"></span>
                    <?php endif; ?>
                </p>
                <p class="control-group">
                    <?php if ($is_new): ?>
                    <?php foreach ($pagegroup_taxonomies AS $buffer_taxonomy): ?>
                    <span class="help-block" id="image-comments-<?php print $buffer_taxonomy->GetID(); ?>" data-filter="taxonomy" data-filter-values="<?php print $buffer_taxonomy->GetID(); ?>"><?php print $buffer_taxonomy->ImageComments(); ?></span>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <span class="help-block"><?php print $page_taxonomy->ImageComments(); ?></span>
                    <?php endif; ?>
                </p>
            </div>
            <!-- /IMAGE WIDGET -->
            <?php endif; ?>
            
            <?php if (($is_new && $pagegroup_taxonomy_ids_user_relation_count) || (!$is_new && $page_taxonomy->UserRelation() && $page_user->id!==get_user_id())): ?>
            <!-- USER WIDGET -->
            <div class="well widget" id="page-edit-user-wrapper" data-filter="taxonomy" data-filter-values="<?php print implode(',', $pagegroup_taxonomy_ids_user_relation); ?>">
                <header><?php print_text('User'); ?></header>
                <div class="btn-toolbar header">
                    <a href="#" class="btn btn-default btn-xs" btn-action="collapse"><span class="glyphicon glyphicon-chevron-up"></span></a>
                    <a href="#" class="btn btn-default btn-xs" btn-action="expand"><span class="glyphicon glyphicon-chevron-down"></span></a>
                </div>

                <input type="hidden" name="user-id" value="<?php echo $page_user->id; ?>" />
                <input type="hidden" name="user-password-encrypted" value="<?php echo $page_user->password; ?>" />
                
                <p class="control-group">
                    <label for="user-email" class="control-label"><?php print_text('E-mail'); ?></label>
                    <input type="text" name="user-email" id="user-email" class="form-control" value="<?php echo $page_user->email; ?>">
                </p>
                <p class="control-group">
                    <label for="user-username" class="control-label"><?php print_text('Username'); ?></label>
                    <input type="text" name="user-username" id="user-username" class="form-control" value="<?php echo $page_user->username; ?>">
                </p>
                <p class="control-group">
                    <label for="user-password" class="control-label"><?php print_text('Password'); ?></label>
                    <input type="password" name="user-password" id="user-password" class="form-control" value="<?php echo $page_user->password; ?>">
                </p>
                <?php if (!empty($capabilities)): ?>
                <p class="radio">
                    <?php foreach ($capabilities AS $buffer_capability): ?>
                    <?php if (User::HasCapability($buffer_capability->GetCapability())): ?>
                    <label for="user-capability-<?php echo $buffer_capability->GetCapability(); ?>" class="checkbox">
                        <input type="checkbox" name="user-capabilities[]" id="user-capability-<?php echo $buffer_capability->GetCapability(); ?>" value="<?php print_html_attr($buffer_capability->GetCapability()); ?>" <?php echo in_array($buffer_capability->GetCapability(), $page_user->capabilities) ? 'checked' : ''; ?>>
                        <?php echo $buffer_capability->GetName(); ?>
                    </label>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </p>
                <?php endif; ?>
            </div>
            <!-- /USER WIDGET -->
            <?php endif; ?>
            
            <?php do_action('content_publish_edit_sidebar_footer', $action, $pagegroup_id, $page_id, $page_locale); ?>
            
        </div>
        <!-- /SIDEBAR COLUMN -->
    </div>
</form>





