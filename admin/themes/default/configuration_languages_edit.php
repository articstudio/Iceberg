<?php
$id = get_request_id();
$submit = $id ? array('action'=>'update', 'id'=>$id) : array('action'=>'insert');
$language = $id ? I18N::GetLanguageInfo($id) : I18N::$LANGUAGE_DEFAULTS;
$flags = get_lang_flags();
?>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="languages-edit" role="form" validate>
    <div class="well">
        <div class="row">
            <div class="col-md-6">
                <p class="form-group">
                    <label for="name" class="control-label"><?php print_text('Name'); ?></label>
                    <input name="name" id="name" class="form-control" value="<?php print_html_attr($language['name']); ?>" required>
                </p>
                <p class="form-group">
                    <label for="locale" class="control-label"><?php print_text('Locale'); ?></label>
                    <input name="locale" id="locale" class="form-control" value="<?php print_html_attr($language['locale']); ?>" required>
                </p>
            </div>
            
            <div class="col-md-6">
                <p class="form-group">
                    <label for="iso" class="control-label"><?php print_text('ISO'); ?></label>
                    <input name="iso" id="iso" class="form-control" value="<?php print_html_attr($language['iso']); ?>" required>
                </p>
                <p class="form-group">
                    <label for="flag" class="control-label"><?php print_text('Flag'); ?></label>
                    <select name="flag" id="flag" class="form-control">
                        <?php foreach ($flags AS $key => $value ): ?>
                        <option value="<?php print_html_attr($key); ?>" data-icon="<?php print_html_attr(get_base_url() . $value); ?>" <?php print ($language['flag']===$key) ? 'selected' : '' ; ?>><?php print $key; ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
            </div>
            
        </div>
        <div class="form-actions text-right">
            <a href="<?php print get_admin_action_link(array('action'=>'list')); ?>" class="btn btn-large btn-default"><span class="glyphicon glyphicon-ban-circle"></span> <?php print_text('Cancel'); ?></a>
            <button type="submit" class="btn btn-large btn-success"><span class="glyphicon glyphicon-ok"></span> <?php print_text('Save'); ?></button>
        </div>
    </div>
</form>