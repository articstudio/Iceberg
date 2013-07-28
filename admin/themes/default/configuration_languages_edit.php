<?php
$id = get_request_id();
$submit = $id ? array('action'=>'update', 'id'=>$id) : array('action'=>'insert');
$language = I18N::GetLanguageInfo($id);
?>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="languages-edit">
    <div class="well">
        <header><?php print_text('Data language'); ?></header>
        
        <div class="row-fluid">
            
            <div class="span6">
                <p>
                    <label for="name"><?php print_text('Name'); ?>:</label>
                    <input name="name" id="name" class="input-block-level" value="<?php print_html_attr($language['name']); ?>" required />
                </p>
                <p>
                    <label for="locale"><?php print_text('Locale'); ?>:</label>
                    <input name="locale" id="locale" class="input-block-level" value="<?php print_html_attr($language['locale']); ?>" required />
                </p>
            </div>
            
            <div class="span6">
                <p>
                    <label for="iso"><?php print_text('ISO'); ?>:</label>
                    <input name="iso" id="iso" class="input-block-level" value="<?php print_html_attr($language['iso']); ?>" required />
                </p>
                <p>
                    <label for="flag"><?php print_text('Flag'); ?>:</label>
                    <select name="flag" id="flag" class="input-block-level">
                        <?php foreach (get_lang_flags() AS $key => $value ): ?>
                        <option value="<?php print_html_attr($key); ?>" data-icon="<?php print_html_attr($value); ?>" <?php print ($language['flag']===$key) ? 'selected' : '' ; ?>><?php print $key; ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
            </div>
            
        </div>
        <div class="form-actions text-right">
            <a href="<?php print get_admin_action_link(); ?>" class="btn btn-large btn-inverse"><?php print_text('Cancel'); ?> <i class="icon-remove-circle icon-white"></i></a>
            <button type="submit" class="btn btn-large btn-success"><?php print_text('Save'); ?> <i class="icon-ok-circle icon-white"></i></button>
        </div>
    </div>
</form>