<?php
$languages = get_langs();
$default = get_language_default();
?>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatable" id="languages-configuration-list" data-order="<?php print get_admin_api_action_link(array('action'=>'order')); ?>" data-new="<?php print get_admin_action_link(array('action'=>'new')); ?>">
    <thead>
        <tr>
            <th><?php print_text('Order'); ?></th>
            <th><?php print_text('Name'); ?></th>
            <th><?php print_text('Locale'); ?></th>
            <th><?php print_text('ISO'); ?></th>
            <th><?php print_text('Flag'); ?></th>
            <th></th>
        </tr>
    </thead>
    <?php $n=0; foreach($languages AS $locale => $lang): ?>
    <tr data-position="<?php print $n; ?>" id="<?php print $locale; ?>">
        <td>
            <?php print $n; ?>
        </td>
        <td>
            <?php print $lang['name']; ?>
        </td>
        <td>
            <?php print $locale; ?>
        </td>
        <td>
            <?php print $lang['iso']; ?>
        </td>
        <td>
            <img src="<?php print get_flag_url($lang['flag']); ?>" alt="<?php print $locale; ?>" />
        </td>
        <td class="text-right">
            <?php if ($locale==$default): ?>
            
            <button class="btn btn-success disabled"><i class="icon-star icon-white"></i></button>
            <button class="btn btn-success disabled"><i class="icon-eye-open icon-white"></i></button>
            <button class="btn btn-success disabled"><i class="icon-ok icon-white"></i></button>
            <a href="<?php print get_admin_action_link(array('id'=>$locale, 'action'=>'edit')); ?>" class="btn btn-inverse"><i class="icon-pencil icon-white"></i></a>
            <button class="btn btn-danger disabled"><i class="icon-trash icon-white"></i></button>
            
            <?php elseif ($lang['active']): ?>
            
            <a href="<?php print get_admin_action_link(array('id'=>$locale, 'action'=>'default')); ?>" class="btn btn-inverse"><i class="icon-star icon-white"></i></a>
            <?php if ($lang['visible']): ?>
            <a href="<?php print get_admin_action_link(array('id'=>$locale, 'action'=>'invisible')); ?>" class="btn btn-success"><i class="icon-eye-open icon-white"></i></a>
            <?php else: ?>
            <a href="<?php print get_admin_action_link(array('id'=>$locale, 'action'=>'visible')); ?>" class="btn btn-inverse"><i class="icon-eye-close icon-white"></i></a>
            <?php endif; ?>
            <a href="<?php print get_admin_action_link(array('id'=>$locale, 'action'=>'unactive')); ?>" class="btn btn-success"><i class="icon-ok icon-white"></i></a>
            <a href="<?php print get_admin_action_link(array('id'=>$locale, 'action'=>'edit')); ?>" class="btn btn-inverse"><i class="icon-pencil icon-white"></i></a>
            <a href="<?php print get_admin_action_link(array('id'=>$locale, 'action'=>'remove')); ?>" class="btn btn-danger" confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><i class="icon-trash"></i></a>
            
            <?php else: ?>
            
            <button class="btn btn-inverse disabled"><i class="icon-star icon-white"></i></button>
            <button class="btn btn-inverse disabled"><i class="icon-eye-open icon-white"></i></button>
            <a href="<?php print get_admin_action_link(array('id'=>$locale, 'action'=>'active')); ?>" class="btn btn-inverse"><i class="icon-ok icon-white"></i></a>
            <a href="<?php print get_admin_action_link(array('id'=>$locale, 'action'=>'edit')); ?>" class="btn btn-inverse"><i class="icon-pencil icon-white"></i></a>
            <a href="<?php print get_admin_action_link(array('id'=>$locale, 'action'=>'remove')); ?>" class="btn btn-danger" confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><i class="icon-trash"></i></a>
            
            <?php endif; ?>
        </td>
    </tr>
    <?php $n++; endforeach; ?>
</table>