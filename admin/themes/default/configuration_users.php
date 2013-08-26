<?php
$users = get_user_list();
?>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatable" id="users-list" data-new="<?php print get_admin_action_link(array('action'=>'new')); ?>">
    <thead>
        <tr>
            <th><?php print_text('ID'); ?></th>
            <th><?php print_text('Username'); ?></th>
            <th><?php print_text('E-mail'); ?></th>
            <th><?php print_text('Level'); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users AS $uid => $user): ?>
        <tr id="<?php print $uid; ?>">
            <td>
                <?php print $user->id; ?>
            </td>
            <td>
                <?php print $user->username; ?>
            </td>
            <td>
                <?php print $user->email; ?>
            </td>
            <td>
                <?php print $user->GetLevelName(); ?> (<?php print $user->level; ?>)
            </td>
            <td class="text-right">
                <?php if ($user->status): ?>
                <a href="<?php print get_admin_action_link(array('id'=>$locale, 'action'=>'unactive')); ?>" class="btn btn-success"><i class="icon-ok icon-white"></i></a>
                <?php else: ?>
                <a href="<?php print get_admin_action_link(array('id'=>$locale, 'action'=>'active')); ?>" class="btn btn-inverse"><i class="icon-ok icon-white"></i></a>
                <?php endif; ?>
                <a href="<?php print get_admin_action_link(array('id'=>$locale, 'action'=>'edit')); ?>" class="btn btn-inverse"><i class="icon-pencil icon-white"></i></a>
                <a href="<?php print get_admin_action_link(array('id'=>$locale, 'action'=>'remove')); ?>" class="btn btn-danger" confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><i class="icon-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>