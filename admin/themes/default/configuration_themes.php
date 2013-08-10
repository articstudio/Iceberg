<?php
$themes = get_frontend_themes();
$frontend_theme =  Theme::GetFrontendTheme();
?>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatable" id="list-themes">
    <thead>
        <tr>
            <th><?php print_text('Name'); ?></th>
            <th><?php print_text('Description'); ?></th>
            <th><?php print_text('Version'); ?></th>
            <th><?php print_text('Author'); ?></th>
            <th></th>
        </tr>
    </thead>
    <?php $n=0; foreach($themes AS $theme): ?>
    <tr>
        <td>
            <?php print $theme['name']; ?>
        </td>
        <td>
            <?php print $theme['description']; ?>
        </td>
        <td>
            <?php print $theme['version']; ?>
        </td>
        <td>
            <?php print $theme['author']; ?>
        </td>
        <td class="text-right">
            <?php if ($frontend_theme['dirname'] === $theme['dirname']): ?>
            <button class="btn btn-success disabled"><i class="icon-ok icon-white"></i></button>
            <?php else: ?>
            <a href="<?php print get_admin_action_link(array('id'=>$theme['dirname'], 'type'=>'frontend', 'action'=>'active')); ?>" class="btn btn-inverse"><i class="icon-ok icon-white"></i></a>
            <?php endif; ?>
        </td>
    </tr>
    <?php $n++; endforeach; ?>
</table>