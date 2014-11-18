<?php
$id = get_request_id();
$submit = $id ? array('action'=>'update', 'id'=>$id) : array('action'=>'insert');
$user = $id ? get_user($id) : new User();
$roles = get_user_roles();
$capabilities = get_user_capabilities();
?>

<form action="<?php print get_admin_action_link($submit); ?>" method="post" id="users-edit" role="form" validate>
    <div class="well">
        <div class="row">
            <div class="col-md-6">
                <p class="form-group">
                    <label for="email" class="control-label"><?php print_text('E-mail'); ?></label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php print_html_attr($user->email); ?>" required>
                </p>
                <p class="form-group">
                    <label for="username" class="control-label"><?php print_text('Username'); ?></label>
                    <input name="username" id="username" class="form-control" value="<?php print_html_attr($user->username); ?>" required>
                </p>
                <p class="form-group">
                    <label for="password" class="control-label"><?php print_text('Password'); ?></label>
                    <input name="password" id="password" class="form-control" value="" <?php echo $id ? '' : 'required'; ?>>
                </p>
            </div>
            
            <div class="col-md-6">
                <p class="form-group">
                    <label for="status" class="control-label"><?php print_text('Status'); ?></label>
                    <select name="status" id="status" class="form-control">
                        <option value="<?php echo User::STATUS_ACTIVE; ?>" <?php echo $user->status===User::STATUS_ACTIVE ? 'selected' : ''; ?>><?php print_text('Active'); ?></option>
                        <option value="<?php echo User::STATUS_UNACTIVE; ?>" <?php echo $user->status===User::STATUS_UNACTIVE ? 'selected' : ''; ?>><?php print_text('Unactive'); ?></option>
                    </select>
                </p>
                <p class="form-group">
                    <label for="role" class="control-label"><?php print_text('Role'); ?></label>
                    <select name="role" id="role" class="form-control">
                        <?php foreach ($roles AS $key => $role): ?>
                        <option value="<?php print_html_attr($role->GetID()); ?>" <?php print ($user->role===$role->GetID()) ? 'selected' : '' ; ?>><?php echo $role->GetName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <div class="form-group">
                    <label class="control-label"><?php print_text('Capabilities'); ?></label>
                    <?php foreach ($capabilities AS $key => $capability): ?>
                    <p class="radio">
                        <label for="capability-<?php echo $key; ?>" class="checkbox">
                            <input type="checkbox" name="capabilities[]" id="capability-<?php echo $key; ?>" value="<?php print_html_attr($capability->GetCapability()); ?>" <?php echo in_array($capability->GetCapability(), $user->capabilities) ? 'checked' : ''; ?>>
                            <?php echo $capability->GetName(); ?>
                        </label>
                    </p>
                    <?php endforeach; ?>
                </div>
            </div>
            
        </div>
        <div class="form-actions text-right">
            <a href="<?php print get_admin_action_link(); ?>" class="btn btn-large btn-default"><span class="glyphicon glyphicon-ban-circle"></span> <?php print_text('Cancel'); ?></a>
            <button type="submit" class="btn btn-large btn-success"><span class="glyphicon glyphicon-ok"></span> <?php print_text('Save'); ?></button>
        </div>
    </div>
</form>