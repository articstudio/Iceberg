<?php
$users = get_user_list();
$users_by_name = array();
foreach ($users AS $uid => $user)
{
    if (!isset($users_by_name[$user->username]) || !is_array($users_by_name[$user->username]))
    {
        $users_by_name[$user->username] = array();
    }
    $users_by_name[$user->username][$uid] = $user;
    reset($users_by_name[$user->username]);
}
?>
<form action="<?php print get_admin_action_link(array('action'=>'clean')); ?>" method="post">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatable" id="users-list">
        <thead>
            <tr>
                <th><?php print_text('ID'); ?></th>
                <th><?php print_text('Username'); ?></th>
                <th><?php print_text('Page'); ?></th>
                <th><?php print_text('E-mail'); ?></th>
                <th><?php print_text('Level'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users_by_name AS $usarname => $username_users): ?>
            <?php $n=0; foreach($username_users AS $uid => $user): ?>
            <?php
            $user2page = $user->GetRelation(User::RELATION_KEY_PAGE);
            $pid = (int)reset($user2page);
            $page = get_page($pid);
            ?>
            <tr id="<?php print $uid; ?>">
                <td>
                    <?php print $user->id; ?>
                </td>
                <td>
                    <?php print $user->username; ?>
                </td>
                <td>
                    <?php print $page->GetTitle(); ?>
                </td>
                <td>
                    <?php print $user->email; ?>
                </td>
                <td>
                    <?php print $user->GetLevelName(); ?> (<?php print $user->level; ?>)
                </td>
                <td class="text-right">
                    <input type="checkbox" name="users[]" id="user_<?php print $user->id; ?>" value="<?php print $user->id; ?>" <?php echo ($n!==0)?'checked':''; ?> />
                </td>
            </tr>
            <?php ++$n; endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
        
    <div class="form-actions text-right">
        <button type="submit" class="btn btn-large btn-success"><?php print_text('Clean'); ?> <i class="icon-ok-circle icon-white"></i></button>
    </div>
</form>
