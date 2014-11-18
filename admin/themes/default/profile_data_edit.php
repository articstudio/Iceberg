<?php
$submit = array('action'=>'update');
$user = get_user();
?>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="users-edit" role="form" validate>
    <div class="well">
        <div class="row">
            <div class="col-md-6">
                <p class="form-group">
                    <label for="email" class="control-label"><?php print_text('E-mail'); ?></label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php print_html_attr($user->email); ?>" required>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p class="form-group">
                    <label for="username" class="control-label"><?php print_text('Username'); ?></label>
                    <input name="username" id="username" class="form-control" value="<?php print_html_attr($user->username); ?>" required>
                </p>
            </div>
            <div class="col-md-6">
                <p class="form-group">
                    <label for="password" class="control-label"><?php print_text('Password'); ?></label>
                    <input typ="password" name="password" id="password" class="form-control" value="">
                </p>
            </div>
        </div>
        <div class="form-actions text-right">
            <button type="submit" class="btn btn-large btn-success"><span class="glyphicon glyphicon-ok"></span> <?php print_text('Save'); ?></button>
        </div>
    </div>
</form>