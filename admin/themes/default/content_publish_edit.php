<?php
$group = get_request_group();
$id = get_request_id();
$is_new = !(bool)$id;
$submit = $is_new ? array('action'=>'insert', 'group'=>$group) : array('action'=>'update', 'id'=>$id, 'group'=>$group);
$back = array('group'=>$group);

$language = get_language_info();
$languages = get_active_langs();
?>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="publish-edit">
    <div class="row-fluid">
        <div class="span9">
            <div class="well">
                <header><?php print_text('Page'); ?></header>
                
                <p>
                    <label for="name"><?php print_text('Name'); ?>:</label>
                    <input type="text" name="name" id="name" class="input-block-level" value="<?php print_html_attr(''); ?>" permalink="#permalink" required />
                </p>
                
                <p>
                    <label for="permalink"><?php print_text('Permalink'); ?>:</label>
                    <input type="text" name="permalink" id="permalink" class="input-block-level" value="<?php print_html_attr(''); ?>" required />
                </p>
                
                <label for="text"><?php print_text('Text'); ?>:</label>
                <textarea class="ckeditor input-block-level" id="text" name="text" rows="10" cols="10"></textarea>
                
                
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
                <?php endif; ?>
                <?php endforeach; ?>
                <p class="text-right">
                    <a href="#" class="btn btn-inverse"><i class="icon-globe icon-white"></i> <?php print_text('Duplicate'); ?></a>
                </p>
            </div>
            <?php endif; ?>
            <div class="well">
                <header><?php print_text('Principal image'); ?></header>
                <div id="page-image">
                    <p>
                        <button type="button" id="page-image-button" class="btn btn-inverse"><?php print_text('Browse'); ?></button>
                        <input type="hidden" name="image" id="image" class="input-block-level" value="" />
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>
